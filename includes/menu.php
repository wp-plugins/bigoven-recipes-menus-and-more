<?php

/*
  @author bigoven
*/

if ( !class_exists('BOMenu') ):

global $BO_directory;
require $BO_directory . '/includes/attributed.php';

global $BO_directory;
require $BO_directory . '/includes/chef.php';

class BOMenu extends BOAttributedBase {
	
	const NO_MENU_IMAGE	=   'http://mda.bigoven.com/pics/rs/256/menu-no-image.png';

	function menuID() { return $this->contentValue('ID',0); }
	function title() { return $this->contentValue('Title','Unnamed Recipe'); }
	function webURL() { return $this->contentValue('WebURL',null); }
	function cuisine() { return $this->contentValue('Cuisine',null); }
	function category() { return $this->contentValue('Category',null); }
	function instructions() { return $this->contentValue('Instructions',null); }
	function description() { return $this->contentValue('Description',null); }
	function yieldNumber() { return $this->contentValue('YieldNumber',0); }
	function yieldUnits() { return $this->contentValue('YieldUnits',null); }
	function totalMinutes() { return $this->contentValue('TotalMinutes',0); }
	function activeMinutes() { return $this->contentValue('ActiveMinutes',0); }

	function hasPoster() {
		return $this->hasContentValue('Poster');
	}
	
	function poster() { 
		$posterValue = $this->contentValue('Poster',null);
		$posterObject = null;
		if ( $posterValue ) {
			$posterObject = new BOChef();
			$posterObject->initFromArray($posterValue);
		}
		return $posterObject; 
	}

	function ingredients() {
		$ingredients = $this->contentValue('Ingredients',null);
		$ingredientsArray = array();
		$count = count($ingredients);
		for ($i = 0; $i < $count; $i++) {
			$ingredientValue = $ingredients[$i];
			if ( $ingredientValue ) {
				$ingredientObject = new BOIngredient();
				$ingredientObject->initFromArray($ingredientValue);
				array_push($ingredientsArray, $ingredientObject);
			}
		}
		return $ingredientsArray;
	}

	// Images...
	function hasImageURL() {
		return $this->hasContentValue('MenuImageURL');
	}

	function imageURL() { return $this->contentValue('MenuImageURL',self::NO_MENU_IMAGE); }
	
	function columnImageURL() {
		$imageURL = $this->imageURL();
		return ($imageURL == self::NO_MENU_IMAGE) ? 
					self::NO_MENU_IMAGE : 
					str_replace('/pics/menu/128h/','/pics/menu/256v/',$imageURL);
	}

	function mediumImageURL() {
		$imageURL = $this->imageURL();
		return ($imageURL == self::NO_MENU_IMAGE) ? 
					self::NO_MENU_IMAGE : 
					$imageURL;
	}

	function thumbnailImageURL() {
		$imageURL = $this->imageURL();
		return ($imageURL == self::NO_MENU_IMAGE) ? 
					self::NO_MENU_IMAGE : 
					str_replace('/pics/menu/128h/','/pics/menu/64h/',$imageURL);
	}
}

class menuMenuItem extends BOAttributedBase {


}

endif;

?>