<?php
/*
Plugin Name: BigOven
Plugin URI: http://wordpress.bigoven.com/
Description: Allows you to take your recipes and menus hosted at BigOven to your WordPress site.
Tags: recipes, menus, food, hRecipe, bigoven, 
Version: 0.6
Author: BigOven
Author URI: http://www.bigoven.com
Text Domain: bigoven

 Derived (in part) from the excellent Broken Link Checker plugin:
   http://wordpress.org/extend/plugins/broken-link-checker/
*/

if ( !function_exists( 'add_action' ) ) {
	echo "BigOven WordPress plugin: See more at http://wordpress.bigoven.com";
	exit;
}

if ( !function_exists('BO_get_plugin_file') ){
	/**
	 * Retrieve the fully qualified filename of BO's main PHP file.
	 * 
	 * @return string
	 */
	function BO_get_plugin_file(){
		return __FILE__; 
	}
}

//Load the actual plugin
require 'core/init.php';

?>
