<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/sagarlakhani/
 * @since      1.0.0
 *
 * @package    Kamaldhari_Sync_Post_With_Language_Addon
 * @subpackage Kamaldhari_Sync_Post_With_Language_Addon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Kamaldhari_Sync_Post_With_Language_Addon
 * @subpackage Kamaldhari_Sync_Post_With_Language_Addon/includes
 * @author     Sagar Lakhani <sagarlakhani024@gmail.com>
 */
class Kamaldhari_Sync_Post_With_Language_Addon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kamaldhari_Sync_Post_With_Language_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * OpenAI instance for handling translations.
	 *
	 * This property holds an instance of the KSPWLA_OpenAI class,
	 * which is responsible for communicating with the OpenAI API
	 * to translate content while maintaining its structure.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var KSPWLA_OpenAI
	 */
	protected $openai;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'KAMALDHARI_SYNC_POST_WITH_LANGUAGE_ADDON_VERSION' ) ) {
			$this->version = KAMALDHARI_SYNC_POST_WITH_LANGUAGE_ADDON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'kamaldhari-sync-post-with-language-addon';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kamaldhari_Sync_Post_With_Language_Addon_Loader. Orchestrates the hooks of the plugin.
	 * - Kamaldhari_Sync_Post_With_Language_Addon_I18n. Defines internationalization functionality.
	 * - Kamaldhari_Sync_Post_With_Language_Addon_Admin. Defines all hooks for the admin area.
	 * - Kamaldhari_Sync_Post_With_Language_Addon_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-kamaldhari-sync-post-with-language-addon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-kamaldhari-sync-post-with-language-addon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-kamaldhari-sync-post-with-language-addon-admin.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-kspwla-languages.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-kspwla-openai.php';

		$this->openai = new KSPWLA_OpenAI();

		$this->loader = new Kamaldhari_Sync_Post_With_Language_Addon_Loader();

		$this->loader->add_filter( 'spsp_before_send_data_args', $this, 'kspwla_spsp_before_send_data_args', 15, 1 );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kamaldhari_Sync_Post_With_Language_Addon_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kamaldhari_Sync_Post_With_Language_Addon_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Kamaldhari_Sync_Post_With_Language_Addon_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'kspwla_add_openai_api_menu', 15 ); // added priority to initialize the menu after main plugin load.
	}


	/**
	 * Modify post content before it is sent via `sps_remote_post()`.
	 * This function intercepts the post content, translates it using OpenAI,
	 * and updates `$args['post_content']` before it is sent.
	 *
	 * @since 1.0.0
	 * @param  array $args The data being sent remotely, including the post content.
	 * @return array Modified `$args` with translated content.
	 */
	public function kspwla_spsp_before_send_data_args( $args ) {

		if ( isset( $args['post_content'] ) ) {
			$translated_content = $this->openai->translate_content( $args['post_content'] );
			if ( $translated_content ) {
				$args['post_content'] = $translated_content;
			}
		}

		return $args;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Kamaldhari_Sync_Post_With_Language_Addon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
