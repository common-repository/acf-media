<?php

/*
Plugin Name: Advanced Custom Fields: Media
Description: Creates Media field type.
Version: 1.1.0
Author: 300FeetOut
Author URI: https://300feetout.com
*/

// exit if accessed directly
if (!defined('ABSPATH')) exit;


// check if class already exists
if (!class_exists('threefo_acf_plugin_media')):

class threefo_acf_plugin_media {

	// vars
	var $settings;


	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/

	function __construct() {

		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.1.0',
			'url'		=> plugin_dir_url(__FILE__),
			'path'		=> plugin_dir_path(__FILE__)
		);


		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}


	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to 4
	*  @return	void
	*/

	function include_field ($version = 4) {

		// load textdomain
		load_plugin_textdomain('TEXTDOMAIN', false, plugin_basename(dirname( __FILE__ )) . '/lang');


		// include
		include_once('fields/class-threefo-acf-field-media-v' . $version . '.php');
	}

}


// initialize
new threefo_acf_plugin_media();


// class_exists check
endif;

?>