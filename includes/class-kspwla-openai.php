<?php
/**
 * Handles translation of WordPress post content using OpenAI API.
 *
 * This file contains the KSPWLA_OpenAI class, which is responsible for
 * translating post content while preserving its HTML structure.
 *
 * @link       https://profiles.wordpress.org/sagarlakhani/
 * @since      1.0.0
 *
 * @package    Kamaldhari_Sync_Post_With_Language_Addon
 * @subpackage Kamaldhari_Sync_Post_With_Language_Addon/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class KSPWLA_OpenAI
 *
 * This class integrates with the OpenAI API to translate WordPress post content
 * in a structured manner while maintaining its HTML structure.
 *
 * @since      1.0.0
 * @package    Kamaldhari_Sync_Post_With_Language_Addon
 * @subpackage Kamaldhari_Sync_Post_With_Language_Addon/includes
 * @author     Sagar Lakhani <sagarlakhani024@gmail.com>
 */
class KSPWLA_OpenAI {

	/**
	 * The OpenAI API key.
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * Constructor method.
	 *
	 * Retrieves the OpenAI API key from the WordPress options table.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->api_key = get_option( 'kspwla_openai_api_key', '' );
	}

	/**
	 * Translates the entire post content by processing it in blocks.
	 *
	 * This method splits the content into identifiable blocks and translates
	 * only textual content while keeping HTML structure intact.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The original post content.
	 * @return string|false The translated content or false on failure.
	 */
	public function translate_content( $content ) {
		if ( empty( $this->api_key ) ) {
			return false;
		}

		$selected_language = get_option( 'kspwla_translation_language', '' );
		$language_map      = KSPWLA_Languages::get_supported_languages();

		$target_language = $language_map[ $selected_language ] ?? '';

		if ( ! $target_language ) {
			return false;
		}

		$blocks             = preg_split( '/(<!--\s*wp:.?-->.?<!--\s*\/wp:.*?-->)/s', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
		$translated_content = '';
		foreach ( $blocks as $block ) {

			// Skip empty blocks.
			if ( empty( trim( $block ) ) ) {
				$translated_content .= $block;
				continue;
			}

			// Check if block has actual textual content (ignores pure-html and images).
			if ( ! empty( trim( wp_strip_all_tags( $block ) ) ) ) {
				// Has some real textual content, translate this block.
				$translated_block    = $this->send_openai_request( $block, $target_language );
				$translated_content .= $translated_block;
			} else {
				// No real textual content detected, do not translate.
				$translated_content .= $block;
			}
		}

		// Update the content before sending.
		if ( ! empty( $translated_content ) ) {
			$content = $translated_content;
		}

		return $content;
	}

	/**
	 * Sends text to OpenAI API for translation.
	 *
	 * This method communicates with OpenAI's GPT model to perform translations
	 * while preserving the HTML structure.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text            The text to be translated.
	 * @param string $target_language The target language name.
	 * @return string|false The translated text or false on failure.
	 */
	private function send_openai_request( $text, $target_language ) {

		$api_url = 'https://api.openai.com/v1/chat/completions';

		$message = array(
			array(
				'role'    => 'system',
				'content' => "Translate the following text into {$target_language} while keeping the HTML structure intact",
			),
			array(
				'role'    => 'user',
				'content' => $text,
			),
		);

		$body = wp_json_encode(
			array(
				'model'       => 'gpt-4o',
				'messages'    => $message,
				'temperature' => 0.7,
			)
		);

		$response = wp_remote_post(
			$api_url,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $this->api_key,
				),
				'body'    => $body,
				'timeout' => 30,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			return false;
		}

		return $data['choices'][0]['message']['content'] ?? false;
	}
}
