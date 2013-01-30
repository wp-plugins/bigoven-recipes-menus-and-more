<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

if (!class_exists('BOBigOven')) {

class BOBigOven {
  var $conf;
  
  var $db_version = 2; 		//The required version of the plugin's DB schema.
  
  var $api_client = null;

  /**
   * BOBigOven::BOBigOven()
   * Class constructor
   *
   * @param boConfigurationManager $conf An instance of the configuration manager
   * @return void
   */

  function BOBigOven ( $loader, &$conf ) {
      global $wpdb;

      global $BO_directory;
      $BO_directory = dirname( BO_get_plugin_file() );
      
      $this->conf = &$conf;
        $this->loader = $loader;

      // TODO internationalize...
      //$this->load_language();
      
      // Unlike the activation hook, the deactivation callback *can* be registered in this file
      //  because deactivation happens after this class has already been instantiated (during the 
	    //  'init' action). 
      //register_deactivation_hook($loader, array(&$this, 'deactivation'));
      
      add_action('admin_menu', array(&$this,'admin_menu'));
  }

    /**
     * Create the plugin's menu items and enqueue their scripts and CSS.
     * Callback for the 'admin_menu' action. 
     * 
     * @return void
     */
    function admin_menu() {
      $menu_title = __('BigOven', 'bigoven');
      $tools_page_hook = add_management_page(
          __('View BigOven', 'bigoven'), 
          $menu_title, 
          'edit_others_posts',
          'bigoven-tool-data',
          array(&$this, 'bigoven_data_page')
      );
       
    }


    function options_page(){
      global $bo_directory;
      
      // Make sure the DB is all set up 
      if ( $this->db_version != $this->conf->options['current_db_version'] ) {
          printf(__("Error: The plugin's database tables are not up to date! (Current version : %d, expected : %d)", 'bigoven'),
            $this->conf->options['current_db_version'],
            $this->db_version);
      }

      // TODO: If submit ... save

      //Show a confirmation message when settings are saved. 
      if ( !empty($_GET['settings-updated']) ) {
          echo '<div id="message" class="updated fade"><p><strong>',__('Settings saved.', 'bigoven'), '</strong></p></div>';
      }
        
      ?>

      <div class="wrap"><?php screen_icon(); ?><h2><?php _e('BigOven Options', 'bigoven'); ?></h2>
        <form name="bigoven_options" id="bigoven_options" method="post" action="<?php 
          echo admin_url('options-general.php?page=tool-bigoven-data&noheader=1'); 
          ?>">
        <?php wp_nonce_field('bigoven-options'); ?>


        <table class="form-table">
          <tr><td>BigOven User</td><td><input type="text" name="bigoven_username" id="bigoven_username" 
                    value="" size="50" maxlength="50" placeholder="BigOven account e-mail address." /></td></tr>
        </table>

        <p class="submit"><input type="submit" name="submit" class='button-primary' value="<?php _e('Save Changes') ?>" /></p>
      </form>

      <?php

    }


    /**
     * Display the "Recipes" & "Menus" page.
     * 
     * @return void
     */
    function bigoven_data_page() {
      global $wpdb;

      //Sanity check : make sure the DB is all set up 
      if ( $this->db_version != $this->conf->options['current_db_version'] ) {
          printf(__("Error: The plugin's database tables are not up to date! (Current version : %d, expected : %d)", 'bigoven'),
            $this->conf->options['current_db_version'],
            $this->db_version);
      }

      $rtable_name = $wpdb->prefix . 'bigoven_recipes';
      $mtable_name = $wpdb->prefix . 'bigoven_menus';

      $reset_cache = !empty($_GET['reset_cache']);

      if ( $reset_cache ) {
        $wpdb->query('DELETE FROM ' . $rtable_name );
        $wpdb->query('DELETE FROM ' . $mtable_name );
        ?>
        <p>Your cache cache has been reset. Recipes and menus will load from BigOven.com on demand for your viewership.</p>
        <?php
      }
      else {
        // Look up...
        $has_data = false;
        $db_results = $wpdb->get_results('SELECT id,name,bigoven_id FROM ' . $rtable_name . ' ORDER BY id');

        ?>
          <div id="icon-tools" class="icon32"><br /></div><h2>BigOven</h2>
          <div class="tool-box">
            <h3 class="title">BigOven Recipes</h3>
        <?php
        if ( $db_results ) {
          ?>
          <p>These recipes have been cached locally from BigOven.</p>
          <table width="80%">
            <tr><td>Recipe Title</td><td>BigOven ID</td></tr>
            <?php 
              foreach ($db_results as $row) {
                ?>
                  <tr><td><a href="http://www.bigoven.com/recipe/<?php echo $row->bigoven_id ?>"><?php echo $row->name ?></a></td><td width="20%"><a href="http://www.bigoven.com/recipe/<?php echo $row->bigoven_id ?>"><?php echo $row->bigoven_id ?></a></td></td></tr>
                <?php
                $has_data = true;
              }
              ?>
          </table>
          <?php
        }
        else {
          ?>
          <p>No recipes have been cached from BigOven.</p>
          <?php
        }
          ?>
          </div>
          <?php
                // Look up...
        $db_results = $wpdb->get_results('SELECT id,name,bigoven_id  FROM ' . $mtable_name . ' ORDER BY id');

        ?>
          <div class="tool-box">
            <h3 class="title">BigOven Menus</h3>
        <?php
        if ( $db_results ) {
          ?>
          <p>These menus have been cached locally from BigOven.</p>
          <table width="80%">
            <tr><td>Menu Title</td><td>BigOven ID</td></tr>
            <?php 
              foreach ($db_results as $row) {
                ?>
                  <tr><td><a href="http://www.bigoven.com/menu/<?php echo $row->bigoven_id ?>"><?php echo $row->name ?></a></td><td width="20%"><a href="http://www.bigoven.com/menu/<?php echo $row->bigoven_id ?>"><?php echo $row->bigoven_id ?></a></td></tr>
                <?php
                $has_data = true;
              }
              ?>
          </table>
          <?php
        }
        else {
          ?>
          <p>No menus have been cached from BigOven.</p>
          <?php
        }
          ?>
          </div>
          <?php

          // Allow a cache reset, if there is data...
          if ( $has_data ) {
        ?>

        <h3><?php _e('BigOven Local Cache', 'bigoven'); ?></h3>
          <p>Remove locally cached recipes and/or menus. This does NOT affect any of your data at BigOven and is safe to perform.<br/>
            Typically one would reset the cache after editing a recipe or menu at BigOven.com in order for the latest information to show here.</p>
          <form name="bigoven_cache" id="bigoven_cache" method="post" action="<?php 
            echo admin_url('tools.php?page=bigoven-tool-data&reset_cache=1'); 
            ?>">
          <?php wp_nonce_field('bigoven-data_reset_cache'); ?>
          <p class="submit" style="text-align:center"><input type="submit" name="submit" class='button-primary' value="<?php _e('Clear Local Cache') ?>" /></p>
        </form>


        <h3><?php _e('Notes on connecting back to BigOven.com', 'bigoven'); ?></h3>
        <p>
   To perform it's task this plugin makes an HTTP connection to the BigOven API at api.bigoven.com,
   stores this data in your local database, and generates HTML to present the recipe or menu. This
   occurs typically once per recipe or menu, and does not occur on a per page load basis. The local
   cache stores a copy of the recipe or menu for highest performance viewing.
  </p>

  <p>
   The HTML that is generated by this plugin includes photographs of the recipe or menu that are 
   hosted by BigOven.com, hence link to the BigOven domain. The recipes and menus also links to 
   your pages on BigOven.com enabling users to get rich functionality such as "add to grocery list",
   "add to menu plan" and "print" and so on.
        </p>

        <p>Read more and/or ask questions or get support at <a href="http://wordpress.bigoven.com">wordpress.bigovne.com</a>.</p>

        <?php

          }
      }
    } 
	
}//class ends here

} // if class_exists...

?>