<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/sagarlakhani/
 * @since      1.0.0
 *
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/includes
 * @author     Sagar Lakhani <sagarlakhani024@gmail.com>
 */
class Sync_Post_With_Language_Addon_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sync-post-with-language-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
