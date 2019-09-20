<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/includes
 * @author     Rahul Gandhi <rahulgandhi1010@gmail.​com>
 */
class Wp_Slideshow_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-slideshow',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
