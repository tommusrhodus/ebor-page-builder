<?php
/**
 * Aqua Page Builder Config
 *
 * This file handles various configurations
 * of the page builder page
 *
 */
function aq_page_builder_config() {

	$config = array(); //initialise array
	
	/* Page Config */
	$config['menu_title'] = __('Ebor Template Builder', 'framework');
	$config['page_title'] = __('Ebor Template Builder', 'framework');
	$config['page_slug'] = __('ebor-template-builder', 'framework');
	
	/* Debugging */
	$config['debug'] = false;
	
	return $config;
	
}