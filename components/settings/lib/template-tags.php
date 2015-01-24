<?php

if(!defined('ABSPATH')) { exit; }

function bo_recipes_get_setting($setting_name, $default = false) {
	return apply_filters(__FUNCTION__, BO_Recipes_Components_Settings::get_setting($setting_name, $default), $setting_name, $default);
}

