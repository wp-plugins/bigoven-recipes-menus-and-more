<?php

/*
  @author bigoven
*/

if ( !class_exists('BORecipe') ):

global $BO_directory;
require $BO_directory . '/includes/attributed.php';

global $BO_directory;
require $BO_directory . '/includes/chef.php';

class BORecipe extends BOAttributedBase {
	
	const NO_RECIPE_IMAGE	=   'http://mda.bigoven.com/pics/rs/256/recipe-no-image.png';

	function recipeID() { return $this->contentValue('RecipeID',0); }
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
		return $this->hasContentValue('ImageURL');
	}

	function imageURL() { return $this->contentValue('ImageURL',self::NO_RECIPE_IMAGE); }
	
	function mediumImageURL() {
		$imageURL = $this->imageURL();
		return ($imageURL == self::NO_RECIPE_IMAGE) ? 
					self::NO_RECIPE_IMAGE : 
					str_replace('/pics/','/pics/rs/256/',$imageURL);
	}

	function thumbnailImageURL() {
		$imageURL = $this->imageURL();
		return ($imageURL == self::NO_RECIPE_IMAGE) ? 
					self::NO_RECIPE_IMAGE : 
					str_replace('/pics/','/pics/rs/64/',$imageURL);
	}
}

class BOIngredient extends BOAttributedBase {

	function name() { return $this->contentValue('Name','Unnamed Ingredient Item'); }
	function displayQuantity() { return $this->contentValue('DisplayQuantity',null); }
	function unit() { return $this->contentValue('Unit',null); }

	function display() { return  $this->displayQuantity() . ' ' . $this->unit() . ' ' . $this->name(); }	
}

endif;

?>