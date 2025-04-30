<?php
/**
 * Handles the supported language list for Sync Post with Language Addon.
 *
 * This class provides a central location to define the supported languages
 * for the translation functionality. By managing the languages separately,
 * future modifications (adding/removing languages) become easier.
 *
 * @link       https://profiles.wordpress.org/sagarlakhani/
 * @since      1.0.0
 *
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles the supported language list for Sync Post with Language Addon.
 *
 * This class defines and manages the list of supported languages for the plugin.
 * It provides a centralized method to retrieve the available language options,
 * ensuring easy maintenance and future updates.
 *
 * By managing the languages separately, adding or removing support for new
 * languages can be done efficiently without modifying multiple parts of the codebase.
 *
 * @since      1.0.0
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/includes
 * @author     Sagar Lakhani <sagarlakhani024@gmail.com>
 */
class SPWLA_Languages {

	/**
	 * Retrieves the list of supported languages.
	 *
	 * @return array An associative array of language codes and their corresponding names.
	 */
	public static function get_supported_languages() {
		return array(
			'en' => 'English',
			'es' => 'Spanish',
			'fr' => 'French',
			'de' => 'German',
			'it' => 'Italian',
			'pt' => 'Portuguese',
			'ru' => 'Russian',
			'zh' => 'Chinese',
			'ja' => 'Japanese',
		);
	}
}
