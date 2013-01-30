<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

class BODatabaseUpgrader {
	
  /**
   * Create and/or upgrade the plugin's database tables.
   *
   * @return bool
   */
    function upgrade_database(){
		global $wpdb;
		
		$conf = &BO_get_configuration();
		$current = $conf->options['current_db_version'];
		
		//Create/update the plugin's tables
		if ( !BODatabaseUpgrader::make_schema_current() ) {
			return false;
		}
				
		$conf->options['current_db_version'] = 2;
		$conf->save_options();
		
		return true;
	}
	
  /**
   * Create or update the plugin's DB tables.
   *
   * @return bool
   */
	function make_schema_current(){
		if ( !function_exists('BO_get_db_schema') ){
			require 'db-schema.php';
		}
		$have_errors = false;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		foreach (BO_get_db_schema() as $tableSchema) {
        	dbDelta($tableSchema);
    	}
    	unset($tableSchema);

		return !$have_errors;
	}
	
	/**
	 * Drop the plugin's tables.
	 * 
	 * @return bool
	 */
	function drop_tables(){
		global $wpdb;
		
		$tables = array(
			$wpdb->prefix . 'bigoven_recipes',
			$wpdb->prefix . 'bigoven_menus',
			$wpdb->prefix . 'BO_recipes', // Historical...
			$wpdb->prefix . 'BO_menus', // Historical...
		);
		
		$q = "DROP TABLE IF EXISTS " . implode(', ', $tables);
		$rez = $wpdb->query( $q );
		
		if ( $rez === false ){
			$error = sprintf(
				__("Failed to delete old DB tables. Database error : %s", 'bigoven'),
				$wpdb->last_error
			); 
		}
		
		return true;
	}	
}

?>