<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/bhavsinh-ker
 * @since      1.0.0
 *
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/includes
 * @author     Bhavsinh Ker <ker.bhavik@gmail.com>
 */
class Wordpress_Contributors_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wordpress-contributors',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
