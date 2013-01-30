<?php

/*
  @author bigoven
  derived from http://wordpress.org/extend/plugins/broken-link-checker/
*/

global $BO_directory, $BO_config_helper, $wpdb;
$queryCnt = $wpdb->num_queries;

//Reset the "installation_complete" flag
$BO_config_helper->options['installation_complete'] = false;
$BO_config_helper->save_options();

//Prepare the database.
require_once $BO_directory . '/includes/admin/db-upgrade.php';
BODatabaseUpgrader::upgrade_database();

$BO_config_helper->options['installation_complete'] = true;
$BO_config_helper->save_options();

?>