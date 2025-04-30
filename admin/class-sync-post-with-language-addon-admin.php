<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://profiles.wordpress.org/sagarlakhani/
 * @since 1.0.0
 *
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sync_Post_With_Language_Addon
 * @subpackage Sync_Post_With_Language_Addon/admin
 * @author     Sagar Lakhani <sagarlakhani024@gmail.com>
 */
class Sync_Post_With_Language_Addon_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sync_Post_With_Language_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sync_Post_With_Language_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sync-post-with-language-addon-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sync_Post_With_Language_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sync_Post_With_Language_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}


	/**
	 * Create a submenu page OpenAI API to save API
	 *
	 * @since 1.0.0
	 */
	public function kspwla_add_openai_api_menu() {

		add_submenu_page(
			'sps_setting',           // Parent menu slug.
			'OpenAI API Settings',   // Page title.
			'OpenAI API',            // Menu title.
			'manage_options',        // Capability.
			'kspwla_openai_settings', // Menu slug.
			array( $this, 'openai_api_settings_page' ) // Callback function.
		);

		add_submenu_page(
			'sps_setting',
			'Translation Settings',
			'Translation Settings',
			'manage_options',
			'kspwla_translation_settings',
			array( $this, 'translation_settings_page' )
		);
	}


	/**
	 * Display the settings page for OpenAI API key
	 *
	 * @since 1.0.0
	 */
	public function openai_api_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_POST['kspwla_openai_api_key'] ) ) {
			check_admin_referer( 'kspwla_save_openai_api_key' );
			update_option( 'kspwla_openai_api_key', sanitize_text_field( wp_unslash( $_POST['kspwla_openai_api_key'] ) ) );
			echo '<div class="updated"><p>API key saved successfully!</p></div>';
		}

		$api_key = get_option( 'kspwla_openai_api_key', '' );
		?>
		<div class="wrap">
			<h2>OpenAI API Settings</h2>
			<form method="post">
		<?php wp_nonce_field( 'kspwla_save_openai_api_key' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="kspwla_openai_api_key">OpenAI API Key</label></th>
						<td>
							<input type="text" id="kspwla_openai_api_key" name="kspwla_openai_api_key" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text">
						</td>
					</tr>
				</table>
		<?php submit_button( 'Save API Key' ); ?>
			</form>
		</div>
		<?php
	}


	/**
	 * Translation Settings Page
	 *
	 * @since 1.0.0
	 */
	public function translation_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Handle form submission.
		if ( isset( $_POST['kspwla_translation_language'] ) ) {
			check_admin_referer( 'kspwla_save_translation_settings' );

			update_option( 'kspwla_translation_language', sanitize_text_field( wp_unslash( $_POST['kspwla_translation_language'] ) ) );

			echo '<div class="updated"><p>Translation settings saved successfully!</p></div>';
		}

		// Get the currently selected language.
		$selected_language = get_option( 'kspwla_translation_language', '' );

		// Available languages.
		$available_languages = SPWLA_Languages::get_supported_languages();
		?>

		<div class="wrap">
			<h2>Translation Settings</h2>
			<form method="post">
		<?php wp_nonce_field( 'kspwla_save_translation_settings' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="kspwla_translation_language">Select Default Translation Language</label></th>
						<td>
							<select name="kspwla_translation_language" id="kspwla_translation_language">
								<?php foreach ( $available_languages as $code => $language ) : ?>
									<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $selected_language, $code ); ?>>
									<?php echo esc_html( $language ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</table>
		<?php submit_button( 'Save Translation Settings' ); ?>
			</form>
		</div>

		<?php
	}
}
