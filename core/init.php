<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

//To prevent conflicts, only one version of the plugin can be activated at any given time.
if ( defined('BO_ACTIVE') ){
	trigger_error(
		'Another version of BigOven is already active. Please deactivate it before activating this one.',
		E_USER_ERROR
	);
} else {
	
define('BO_ACTIVE', true);

//Fail fast if the WP version is unsupported. The $wp_version variable may be obfuscated by other
//plugins, so use function detection to determine the version. get_post_stati was introduced in WP 3.0.0
if ( !function_exists('get_post_stati') ){
	trigger_error(
		'This version of BigOven requires WordPress 3.0 or later!',
		E_USER_ERROR
	);
}

// TODO: Check for PHP 5.2? for json?

/***********************************************
				Debugging stuff
************************************************/

define('BO_DEBUG', true);

/***********************************************
				Constants
************************************************/

/***********************************************
				Configuration
************************************************/

//Load and initialize the plugin's configuration
global $BO_directory;
$BO_directory = dirname( BO_get_plugin_file() );
require $BO_directory . '/includes/config-helper.php';

global $BO_config_helper;
$BO_config_helper = new BOConfigurationHelper(
	//Save the plugin's configuration into this DB option
	'BO_options', 
	//Initialize default settings
	array(
        'chef_name' 			=> NULL, 		// The BigOven Chef username		
		'installation_complete' => false,
   )
);

/***********************************************
				Global functions
************************************************/

/**
 * Get the configuration object used by BigOven.
 *
 * @return BOConfigurationHelper
 */
function &BO_get_configuration(){
	return $GLOBALS['BO_config_helper'];
}

/***********************************************
				Utility hooks
************************************************/

/***********************************************
				Main functionality
************************************************/

//Execute the installation/upgrade script when the plugin is activated.
function BO_activation_hook(){
	global $BO_directory;
	require $BO_directory . '/includes/activation.php';
}
register_activation_hook(plugin_basename(BO_get_plugin_file()), 'BO_activation_hook');

//Load the plugin if installed successfully
if ( $BO_config_helper->options['installation_complete'] ){
	function BO_init(){
		global $BO_directory, $BO_config_helper, $BO_bigoven;
		
		static $init_done = false;
		if ( $init_done ){
			return;
		}
		$init_done = true;
		
		//Load the base classes and utilities
		//require $BO_directory . '/includes/utility-class.php';
		
		if ( is_admin() ){	
			//It's an admin-side or Cron request. Load the core.
			require $BO_directory . '/core/core.php';

			$BO_bigoven = new BOBigOven( BO_get_plugin_file() , $BO_config_helper );
			
		} else {
			require $BO_directory . '/core/client.php';
			$BO_bigoven = new BOBigOvenClient( BO_get_plugin_file() , $BO_config_helper );
		}
	}
	add_action('init', 'BO_init', 2000);	
} else {
	//Display installation errors (if any) on the Dashboard.
	function BO_print_installation_errors(){
		$messages = array('<strong>' . __('BigOven installation failed', 'bigoven') . '</strong>');
		echo "<div class='error'><p>", implode("<br>\n", $messages), "</p></div>";
	}
	add_action('admin_notices', 'BO_print_installation_errors');
}

}
?>