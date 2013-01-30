<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

/**
 *
 * The uninstallation script.
 */

if( defined( 'ABSPATH') && defined('WP_UNINSTALL_PLUGIN') ) {

	// Remove the plugin's settings
	delete_option('BO_options');

	//Remove the database tables
	$mywpdb = $GLOBALS['wpdb'];    
	if( isset($mywpdb) ) { 
		//EXTERMINATE!
		$mywpdb->query( "DROP TABLE IF EXISTS {$mywpdb->prefix}bigoven_recipes" );
		$mywpdb->query( "DROP TABLE IF EXISTS {$mywpdb->prefix}bigoven_menus" );
	}
}

?>