<?php

if(!defined('ABSPATH')) { exit; }

function bo_recipes_get_shortcode_for_recipe($recipe_id) {
	return apply_filters(__FUNCTION__, sprintf('[%s id="%d"]', BO_RECIPES_RECIPE_SHORTCODE, $recipe_id));
}
