<?php

if(!defined('ABSPATH')) { exit; }

function bo_recipes_get_print_url($recipe_id) {
	return apply_filters(__FUNCTION__, BO_Recipes_Components_Display_Print::get_print_url($recipe_id), $recipe_id);
}
