<?php
/*
Plugin Name: Ebor Page Builder
Plugin URI: http://www.madeinebor.com
Description: Easily create custom page templates with intuitive drag-and-drop interface. Requires PHP5 and WP3.5. Ebor Page Builder is built upon Aqua Page Builder.
Version: 1.1.3
Author: TommusRhodus
Author URI: http://www.madeinebor.com
*/

/**
 * Updater
 */
include_once('updater.php');

if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => plugin_basename(__FILE__),
        'proper_folder_name' => 'plugin-name', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/username/repository-name', // the github API url of your github repo
        'raw_url' => 'https://raw.github.com/username/repository-name/master', // the github raw url of your github repo
        'github_url' => 'https://github.com/username/repository-name', // the github url of your github repo
        'zip_url' => 'https://github.com/username/repository-name/zipball/master', // the zip url of the github repo
        'sslverify' => true,
        'requires' => '3.8',
        'tested' => '3.8.1',
        'readme' => 'README.md',
        'access_token' => ''
    );
    new WP_GitHub_Updater($config);
}

/**
 * All class names & definitions have been left the same for simplicity
 */

//definitions
if(!defined('AQPB_VERSION')) define( 'AQPB_VERSION', '1.1.2' );
if(!defined('AQPB_PATH')) define( 'AQPB_PATH', plugin_dir_path(__FILE__) );
if(!defined('AQPB_DIR')) define( 'AQPB_DIR', plugin_dir_url(__FILE__) );

//required functions & classes
require_once(AQPB_PATH . 'functions/aqpb_config.php');
require_once(AQPB_PATH . 'classes/class-aq-page-builder.php');
require_once(AQPB_PATH . 'classes/class-aq-block.php');
require_once(AQPB_PATH . 'functions/aqpb_functions.php');

//some default blocks
require_once(AQPB_PATH . 'blocks/aq-text-block.php');
require_once(AQPB_PATH . 'blocks/aq-column-block.php');
require_once(AQPB_PATH . 'blocks/aq-clear-block.php');
require_once(AQPB_PATH . 'blocks/aq-widgets-block.php');
require_once(AQPB_PATH . 'blocks/aq-alert-block.php');
require_once(AQPB_PATH . 'blocks/aq-tabs-block.php');

//register default blocks
aq_register_block('AQ_Text_Block');
aq_register_block('AQ_Clear_Block');
aq_register_block('AQ_Column_Block');
aq_register_block('AQ_Widgets_Block');
aq_register_block('AQ_Alert_Block');
aq_register_block('AQ_Tabs_Block');

//fire up page builder
$aqpb_config = aq_page_builder_config();
$aq_page_builder = new AQ_Page_Builder($aqpb_config);
if(!is_network_admin()) $aq_page_builder->init();