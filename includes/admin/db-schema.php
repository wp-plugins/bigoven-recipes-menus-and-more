<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

if( !function_exists('BO_get_db_schema') ){

function BO_get_db_schema(){
	global $wpdb;
	
	//Use the character set and collation that's configured for WP tables
	$charset_collate = '';
	if ( !empty($wpdb->charset) ){
		$charset = str_replace('-', '', $wpdb->charset);		
		$charset_collate = "DEFAULT CHARACTER SET {$charset}";
	}
	if ( !empty($wpdb->collate) ){
		$charset_collate .= " COLLATE {$wpdb->collate}";
	}
	
	$BO_db_schema = array();

	$BO_recipe_db_schema = <<<RECIPE_SCHEMA
	
	CREATE TABLE {$wpdb->prefix}bigoven_menus (
		id int(10) unsigned NOT NULL AUTO_INCREMENT,
		bigoven_id int(10) unsigned NOT NULL,
		name varchar(100) NOT NULL,
		context text NOT NULL,
		
		PRIMARY KEY  (id), 
			UNIQUE (bigoven_id) 
	) {$charset_collate};
RECIPE_SCHEMA;
	array_push($BO_db_schema, $BO_recipe_db_schema);

	$BO_menu_db_schema = <<<MENU_SCHEMA

	CREATE TABLE {$wpdb->prefix}bigoven_recipes (
		id int(10) unsigned NOT NULL AUTO_INCREMENT,
		bigoven_id int(10) unsigned NOT NULL,
		name varchar(100) NOT NULL,
		context text NOT NULL,
		
		PRIMARY KEY  (id), 
			UNIQUE (bigoven_id) 
	) {$charset_collate};

MENU_SCHEMA;
	array_push($BO_db_schema, $BO_menu_db_schema);
	
	return $BO_db_schema;
}

}

?>