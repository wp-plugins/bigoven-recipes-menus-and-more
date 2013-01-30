<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

/* Store variables as one setting in the database, not many. */

if ( !class_exists('BOConfigurationHelper') ){

class BOConfigurationHelper {
	
	var $option_name;
	
	var $options;
	var $defaults;
	var $loaded_values;
	
	function BOConfigurationHelper( $option_name = '', $default_settings = null ){
		$this->option_name = $option_name;
		
		if ( is_array($default_settings) ){
			$this->defaults = $default_settings;
		} else {
			$this->defaults = array();
		}
		$this->loaded_values = array();
		
		$this->options = $this->defaults;
		
		if ( !empty( $this->option_name ) )
			$this->load_options();		
	}
	
  /**
   * BOConfigurationHelper::load_options()
   * Load plugin options from the database. The current $options values are not affected
   * if this function fails.
   *
   * @param string $option_name
   * @return bool True if options were loaded, false otherwise. 
   */
	function load_options( $option_name = '' ){
		if ( !empty($option_name) ){
			$this->option_name = $option_name;
		}
		
		if ( empty($this->option_name) ) return false;
		
		$new_options = get_option($this->option_name);
        if( !is_array( $new_options ) ){
            return false;
        } else {
        	$this->loaded_values = $new_options;
            $this->options = array_merge( $this->defaults, $this->loaded_values );
            return true;
        }
	}
	
  /**
   * BOConfigurationHelper::save_options()
   * Save plugin options to the databse. 
   *
   * @param string $option_name (Optional) Save the options under this name 
   * @return bool True on success, false on failure
   */
	function save_options( $option_name = '' ){
		if ( !empty($option_name) ){
			$this->option_name = $option_name;
		}
		
		if ( empty($this->option_name) ) return false;
		
		update_option( $this->option_name, $this->options );
		return true;		
	}
}

}
?>