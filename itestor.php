<?php

/*
Plugin Name: Itestor
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Jesus
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/
// Include our updater file
include_once(plugin_dir_path(__FILE__) . '/updater/iTestor_Updater');

$updater = new iTestor_Updater(__FILE__);
$updater->set_username('cro22');
$updater->set_repository('castflow');

$updater->initialize();
