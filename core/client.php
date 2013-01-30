<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

if (!class_exists('BOBigOvenClient')) {

class BOBigOvenClient {
    var $conf;
    
	  var $loader;
    var $my_basename = '';	
    
    var $db_version = 2; 		//The required version of the plugin's DB schema.
  
  /**
   * BOBigOven::BOBigOvenClient()
   * Class constructor
   *
   * @param string $loader The fully qualified filename of the loader script that WP identifies as the "main" plugin file.
   * @param boConfigurationManager $conf An instance of the configuration manager
   * @return void
   */
    function BOBigOvenClient ( $loader, &$conf ) {
        global $wpdb;
        
        $this->conf = &$conf;
        $this->loader = $loader;
        $this->my_basename = plugin_basename( $this->loader );

        //$this->load_language();
        
        global $BO_directory;
        $BO_directory = dirname( BO_get_plugin_file() );
      
        // Establish a client...
        require $BO_directory . '/includes/api-client.php';
        $this->api_client = new BOAPIClient();
        $this->api_client->init($this->conf);
        
        //add_action('admin_menu', array(&$this,'admin_menu'));

        add_shortcode('bigoven-recipe', array(&$this,'bigoven_recipe_shortcode'));
        add_shortcode('bigoven-tile-recipe', array(&$this,'bigoven_recipe_tile_shortcode'));
        add_shortcode('bigoven-menu', array(&$this,'bigoven_menu_shortcode'));
        add_shortcode('bigoven-tile-menu', array(&$this,'bigoven_menu_tile_shortcode'));

        // BigOven style...
        wp_enqueue_style('bo-bigoven', plugins_url('css/bigoven.css', $this->loader), array(), '0.1' );
    }

    function get_recipe_by_id($id,$url) {

      // Get an ID from the URL
      if ( !$id && $url ) {
        $parts = explode("/",$url);
        $id_part = $parts[count($parts)-2];
        $id = intval($id_part);
      }

      // Nope ... didn't get an ID...
      if ( !$id ) {
        return null;
      }

      // Look up...
      global $wpdb;
      $table_name = $wpdb->prefix . 'bigoven_recipes';
      $db_row = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE bigoven_id = ' . $id);

      if ( $db_row == null ) {
        // Make an API call ...
        $results = $this->api_client->apiCall('http://api.bigoven.com/recipe/'.$id.'?api_key=jm1fr3mf804lID9DV6JeJB8XN5e4rD82');
        $data = $results['data'];
        $jsonData = json_decode($data, true);
        if ( $jsonData ) {
          $recipe = new BORecipe();
          $recipe->initFromArray($jsonData);

          // Stash to disk...
          $insert = "INSERT INTO " . $table_name . " (bigoven_id, name, context) " .
                  "VALUES ("  . $recipe->recipeID() . ",'" . $wpdb->escape($recipe->title()) . "','" . 
                    $wpdb->escape(serialize($recipe)) . "')";
          $db_results = $wpdb->query( $insert );
        }
      }
      else {
          // Extract from dayabase... 
          $recipe = unserialize($db_row->context);
      }

      return $recipe;
    }


    function get_menu_by_id($id,$url) {

      // Get an ID from the URL
      if ( !$id && $url ) {
        $parts = explode("/",$url);
        $id_part = $parts[count($parts)-2];
        $id = intval($id_part);
      }

      // Nope ... didn't get an ID...
      if ( !$id ) {
        return null;
      }

      // Look up...
      global $wpdb;
      $table_name = $wpdb->prefix . 'bigoven_menus';
      $db_row = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE bigoven_id = ' . $id);

      if ( $db_row == null ) {
        // Make an API call ...
        $results = $this->api_client->apiCall('http://api.bigoven.com/menu/'.$id.'?api_key=jm1fr3mf804lID9DV6JeJB8XN5e4rD82');
        $data = $results['data'];
        $jsonData = json_decode($data, true);
        if ( $jsonData ) {
          $menu = new BOMenu();
          $menu->initFromArray($jsonData);

          // Stash to disk...
          $insert = "INSERT INTO " . $table_name . " (bigoven_id, name, context) " .
                  "VALUES ("  . $menu->menuID() . ",'" . $wpdb->escape($menu->title()) . "','" . 
                    $wpdb->escape(serialize($menu)) . "')";
          $db_results = $wpdb->query( $insert );
        }
      }
      else {
          // Extract from dayabase... 
          $menu = unserialize($db_row->context);
      }

      return $menu;
    }

/***********************************************
    Shortcodes
************************************************/

  function bigoven_recipe_shortcode( $atts ) {
    extract( shortcode_atts( array(
                    'id' => '0',
                    'url' => null
                ), $atts ) );

    global $BO_directory;
    require $BO_directory . '/includes/recipe.php';

    // Find the recipe...
    $recipe = $this->get_recipe_by_id($id,$url);

    // Generate the output...
    $output = null;
    if ( $recipe )
      $output = $this->format_recipe($recipe);
    else
      $output = $this->format_error('Unable to access BigOven recipe for [id:' . $id . ', url:' . $url . ']');

    return $output;
  }

  function bigoven_recipe_tile_shortcode( $atts ) {
    extract( shortcode_atts( array(
                    'id' => '0',
                    'url' => null
                ), $atts ) );

    global $BO_directory;
    require $BO_directory . '/includes/recipe.php';

    // Find the recipe...
    $recipe = $this->get_recipe_by_id($id,$url);

    // Generate the output...
    $output = null;
    if ( $recipe )
      $output = $this->format_tile_recipe($recipe);
    else
      $output = $this->format_error('Unable to access BigOven recipe for [id:' . $id . ', url:' . $url . ']');

    return $output;
  }

  function format_recipe( $recipe ) {

    $output = '<div class="bigoven-recipe">';
    #$output .= '<div class="bigoven-options">Add to&nbsp;' 
    #            . '<a href="http://www.bigoven.com/link/gl?recipe_id=' . $recipe->recipeID() .'">Grocery List</a>&nbsp;-&nbsp;'
    #            . '<a href="http://www.bigoven.com/link/mp?recipe_id=' . $recipe->recipeID() .'">Menu Plan</a>&nbsp;-&nbsp;'
    #            . '<a href="http://www.bigoven.com/link/ts?recipe_id=' . $recipe->recipeID() .'">Recipes</a> - '
    #            . '<a href="http://www.bigoven.com/link/pr?recipe_id=' . $recipe->recipeID() .'">PRINT</a>'
    #            . '</div>';
    $output .= '<div itemscope itemtype="http://schema.org/Recipe" class="bigoven-title-block bigoven-right-col">
                <strong class="bigoven-recipe-title" itemprop="name">' . $recipe->title() . '</strong>';


    // Format metadata...
    $metadataPieces = array();

    // Cuisine
    if ( $recipe->cuisine() ) {
      array_push($metadataPieces, '<span itemProp="recipeCuisine">' . $recipe->cuisine() . '</span>');
    }

    // Category
    if ( $recipe->category() ) {
      array_push($metadataPieces, $recipe->category());
    }

    // Yield...
    if ( $recipe->yieldNumber() && $recipe->yieldUnits() ) {
      array_push($metadataPieces, '<span itemprop="recipeYield">' .
                                  $recipe->yieldNumber() . ' ' . $recipe->yieldUnits() .
                                  '</span>');
    }

    // Timing...
    if ( $recipe->activeMinutes() && $recipe->totalMinutes()  ) {
      array_push($metadataPieces, '<meta itemprop="totalTime" content="PT'. $recipe->totalMinutes().'M">' .
                                    $recipe->totalMinutes() . ' minutes (' . 
                                    '<meta itemprop="prepTime" content="PT'. $recipe->activeMinutes().'M">' .
                                    $recipe->activeMinutes() . ' active)');
    }
    else if ( $recipe->totalMinutes() ) {
      array_push($metadataPieces, '<meta itemprop="totalTime" content="PT'. $recipe->totalMinutes().'M">' . 
                                        $recipe->totalMinutes() . ' minutes');
    }
    else if ( $recipe->activeMinutes() ) {
      array_push($metadataPieces, '<meta itemprop="prepTime" content="PT'. $recipe->activeMinutes().'M">' . 
                                    $recipe->activeMinutes() . ' minutes');
    }

    $metadata = join('&nbsp;-&nbsp;', $metadataPieces);

    if ( $metadata ) {
      $output .= '<div class="bigoven-recipe-metadata">';
      $output .= $metadata;
      $output .= '</div>';      
    }
    $output .= '</div>';

    $output .= '<div class="bigoven-column-container">';

    if ( $recipe->hasImageURL()) {
      // Description...
      $output .= '<div class="bigoven-recipe-image bigoven-left-col"><a href="' . $recipe->webURL() . '"><img itemprop="image" src="'.$recipe->mediumImageURL().'" 
                                alt="'.$recipe->title().'" title="'.$recipe->title().'"/></a></div>';
    }

    $output .= '<div itemprop="description" class="bigoven-description bigoven-right-col">'. $this->nl2p($recipe->description()) .'</div>';


    $output .= '<div class="bigoven-ingredients-column bigoven-left-col">';
    $output .= '<div class="bigoven-sub-title-block">Ingredients</div>';
    $output .= '<ul class="bigoven-ingredients">';
    foreach ($recipe->ingredients() as $ingredient) {
      $output .= '<li itemprop="ingredients">' . $ingredient->display() . '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';

    $output .= '<div class="bigoven-instructions-column bigoven-right-col">';
    $output .= '<div class="bigoven-sub-title-block">'.$recipe->title().' Instructions</div>';
    $output .= '<span itemprop="recipeInstructions" class="bigoven-instructions">'. $this->nl2p($recipe->instructions()) .'</span>';
    $output .= '</div>';


    $output .= '<div class="bigoven-clear"></div>';
    $output .= '</div>';
  
    $output .= '<div class="bigoven-hosted"><a href="' . $recipe->webURL() . '" title="Recipe hosted at BigOven">' . $recipe->title() . ' at BigOven</a></div>';

    $output .= '</div>';

    return $output;
  }
	

  function format_tile_recipe( $recipe ) {

    $output  = '<div class="bigoven-recipe-tile">';
    $output .= '<div class="bigoven-image-tile">';
    $output .= '<a href="' . $recipe->webURL() . '"><img itemprop="image" src="'.$recipe->mediumImageURL().'" 
                                alt="'.$recipe->title().'" title="'.$recipe->title().'"/></a>';

    $extraCSSClass = strlen($recipe->title()) > 30 ? 'bigoven-recipe-title-long' : '';                                 
    $output .= '<div class="bigoven-recipe-title-box '.$extraCSSClass.'">';
    $output .= '</div>';
    $output .= '<a href="' . $recipe->webURL() . '" class="bigoven-recipe-title '.$extraCSSClass.'" itemprop="name">' . $recipe->title() . '</a>';    
    $output .= '</div>';
    $output .= '</div>';

    return $output;
  }


function nl2p($string, $line_breaks = false, $xml = true)
{
    // Remove existing HTML formatting to avoid double-wrapping things
    $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
    
    // It is conceivable that people might still want single line-breaks
    // without breaking into a new paragraph.
    if ($line_breaks == true)
        return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '<br'.($xml == true ? ' /' : '').'>'), trim($string)).'</p>';
    else 
        return '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", trim($string)).'</p>';
}  

 /******************************************************************************************************************************************/
  /*                                                                                                                             MENUs      */
  /******************************************************************************************************************************************/
  /******************************************************************************************************************************************/
  /******************************************************************************************************************************************/

  function bigoven_menu_shortcode( $atts ) {
    extract( shortcode_atts( array(
                    'id' => '0',
                    'url' => null
                ), $atts ) );

    global $BO_directory;
    require $BO_directory . '/includes/menu.php';

    // Find the menu...
    $menu = $this->get_menu_by_id($id,$url);

    // Generate the output...
    $output = null;
    if ( $menu )
      $output = $this->format_menu($menu);
    else
      $output = $this->format_error('Unable to access BigOven menu for [id:' . $id . ', url:' . $url . ']');

    return $output;
  }

  function bigoven_menu_tile_shortcode( $atts ) {
    extract( shortcode_atts( array(
                    'id' => '0',
                    'url' => null
                ), $atts ) );

    global $BO_directory;
    require $BO_directory . '/includes/menu.php';

    // Find the menu...
    $menu = $this->get_menu_by_id($id,$url);

    // Generate the output...
    $output = null;
    if ( $menu )
      $output = $this->format_tile_menu($menu);
    else
      $output = $this->format_error('Unable to access BigOven menu for [id:' . $id . ', url:' . $url . ']');

    return $output;
  }

  function format_menu( $menu ) {

    $output = '<div class="bigoven-menu">';
    #$output .= '<div class="bigoven-options">Add to&nbsp;' 
    #            . '<a href="http://www.bigoven.com/link/gl?menu_id=' . $menu->menuID() .'">Grocery List</a>&nbsp;-&nbsp;'
    #            . '<a href="http://www.bigoven.com/link/mp?menu_id=' . $menu->menuID() .'">Menu Plan</a>&nbsp;-&nbsp;'
    #            . '<a href="http://www.bigoven.com/link/ts?menu_id=' . $menu->menuID() .'">Recipes</a> - '
    #            . '<a href="http://www.bigoven.com/link/pr?menu_id=' . $menu->menuID() .'">PRINT</a>'
    #            . '</div>';
    $output .= '<div itemscope itemtype="http://schema.org/Recipe" class="bigoven-title-block bigoven-right-col">
                <strong class="bigoven-menu-title" itemprop="name">' . $menu->title() . '</strong>';


    // Format metadata...
    $metadataPieces = array();

    /*
    // Cuisine
    if ( $menu->cuisine() ) {
      array_push($metadataPieces, '<span itemProp="menuCuisine">' . $menu->cuisine() . '</span>');
    }

    // Category
    if ( $menu->category() ) {
      array_push($metadataPieces, $menu->category());
    }

    // Yield...
    if ( $menu->yieldNumber() && $menu->yieldUnits() ) {
      array_push($metadataPieces, '<span itemprop="menuYield">' .
                                  $menu->yieldNumber() . ' ' . $menu->yieldUnits() .
                                  '</span>');
    }
    */

    // Timing...
    /*
    if ( $menu->activeMinutes() && $menu->totalMinutes()  ) {
      array_push($metadataPieces, '<meta itemprop="totalTime" content="PT'. $menu->totalMinutes().'M">' .
                                    $menu->totalMinutes() . ' minutes (' . 
                                    '<meta itemprop="prepTime" content="PT'. $menu->activeMinutes().'M">' .
                                    $menu->activeMinutes() . ' active)');
    }
    else if ( $menu->totalMinutes() ) {
      array_push($metadataPieces, '<meta itemprop="totalTime" content="PT'. $menu->totalMinutes().'M">' . 
                                        $menu->totalMinutes() . ' minutes');
    }
    else if ( $menu->activeMinutes() ) {
      array_push($metadataPieces, '<meta itemprop="prepTime" content="PT'. $menu->activeMinutes().'M">' . 
                                    $menu->activeMinutes() . ' minutes');
    }
    */
    $metadata = join('&nbsp;-&nbsp;', $metadataPieces);

    if ( $metadata ) {
      $output .= '<div class="bigoven-menu-metadata">';
      $output .= $metadata;
      $output .= '</div>';      
    }
    $output .= '</div>';

    $output .= '<div class="bigoven-column-container">';

    // Description...
    $output .= '<div itemprop="description" class="bigoven-description bigoven-right-col">'. $this->nl2p($menu->description()) .'</div>';

    if ( $menu->hasImageURL()) {
      $output .= '<div class="bigoven-menu-image bigoven-left-col"><a href="' . $menu->webURL() . '"><img itemprop="image" src="'.$menu->mediumImageURL().'" 
                                stylealt="'.$menu->title().'" title="'.$menu->title().'"/></a></div>';
    }


    $output .= '<div class="bigoven-hosted">';

    $output .= '<div class="bigoven-clear"></div>';

    if ( $menu->hasPoster() ) {
      $output .= '<a href="' . $menu->poster()->webURL() . '" title="Menu hosted at BigOven">Posted by ' . $menu->poster()->username() . '</a><br>';
    }
    $output .= '<a href="' . $menu->webURL() . '" title="Menu hosted at BigOven">' . $menu->title() . ' at BigOven</a>';

    $output .= '</div>';


    $output .= '<div class="bigoven-clear"></div>';

    $output .= '</div>';

    return $output;
  }
  
  function format_tile_menu( $menu ) {

    $output  = '<div class="bigoven-menu-tile">';
    $output .= '<div class="bigoven-image-tile">';
    $output .= '<a href="' . $menu->webURL() . '"><img itemprop="image" src="'.$menu->mediumImageURL().'" 
                                alt="'.$menu->title().'" title="'.$menu->title().'"/></a>';

    $extraCSSClass = strlen($menu->title()) > 30 ? 'bigoven-menu-title-long' : '';                                 
    $output .= '<div class="bigoven-menu-title-box '.$extraCSSClass.'">';
    $output .= '</div>';
    $output .= '<a href="' . $menu->webURL() . '" class="bigoven-menu-title '.$extraCSSClass.'" itemprop="name">' . $menu->title() . '</a>';    
    $output .= '</div>';
    $output .= '</div>';

    return $output;
  }


  /******************************************************************************************************************************************/
  /*                                                                                                                             BOTH       */
  /******************************************************************************************************************************************/
  /******************************************************************************************************************************************/
  /******************************************************************************************************************************************/

  function format_error( $error ) {
    $output = '<div class="error">' . ($error ? $error  : 'Unknown error') . '</div>';
    return $output;
  }
  
}//class ends here

} // if class_exists...

?>