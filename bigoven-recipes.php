<?php

/*
Plugin Name: BigOven - Recipes
Plugin URI: http://www.bigoven.com/
Description: Create, manage, and display SEO-friendly recipes for your site with an easy interface.
Version: 1.0.4
Author: BigOven (c) 2014
Author URI: http://www.bigoven.com/
*/

if(!defined('ABSPATH')) { exit; }

// Plugin constants

if(!defined('BO_RECIPES_VERSION')) {
	define('BO_RECIPES_VERSION', '1.0.4');
}

if(!defined('BO_RECIPES_CACHE_PERIOD')) {
	define('BO_RECIPES_CACHE_PERIOD', (24 * HOUR_IN_SECONDS));
}

if(!defined('BO_RECIPES_PATH')) {
	define('BO_RECIPES_PATH', dirname(__FILE__));
}

// Data
require_once(path_join(BO_RECIPES_PATH, 'components/data/recipe/recipe.php'));

// Display
require_once(path_join(BO_RECIPES_PATH, 'components/display/shortcode/shortcode.php'));

// Editor

/// Buttons
require_once(path_join(BO_RECIPES_PATH, 'components/editor/buttons/buttons.php'));

/// Popup (for find and insert)
require_once(path_join(BO_RECIPES_PATH, 'components/editor/popup/popup.php'));

// Settings
require_once(path_join(BO_RECIPES_PATH, 'components/settings/settings.php'));

// Activation
function bo_recipes_activation() {
	flush_rewrite_rules();

	do_action('bo_recipes_activation');
}
register_activation_hook(__FILE__, 'bo_recipes_activation');

// Deactivation
function bo_recipes_deactivation() {
	flush_rewrite_rules();

	do_action('bo_recipes_deactivation');
}
register_deactivation_hook(__FILE__, 'bo_recipes_deactivation');

// Debugging
function bo_recipes_debug() {
	if(defined('BO_RECIPES_DEBUG') && 'LOG' === BO_RECIPES_DEBUG) {
		foreach(func_get_args() as $arg) {
			if(is_scalar($arg)) {
				error_log($arg);
			} else {
				error_log(print_r($arg, true));
			}
		}
	}
}

// Redirection
function bo_recipes_redirect($url, $code = 302) {
	wp_redirect($url, $code);
	exit;
}

// Utilities
function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
