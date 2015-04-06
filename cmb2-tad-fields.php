<?php
	/**
	 * Plugin Name: CMB2 theAverageDev additional fields
	 * Plugin URI: http://theAverageDev.com
	 * Description: Custom Meta Boxes 2 additional fields.
	 * Version: 1.0
	 * Author: theAverageDev
	 * Author URI: http://theAverageDev.com
	 * License: GPL2
	 */

	require dirname( __FILE__ ) . '/vendor/autoload_52.php';

	define( 'TAD_CMB2_ROOT', __FILE__ );

	tad_cmb2_Fields::instance()->hook();
	tad_cmb2_Scripts::instance()->hook();

	// Include the example file to have a demo of the fields on pages.
	// include 'examples.php';
