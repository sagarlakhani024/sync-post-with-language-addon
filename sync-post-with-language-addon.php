<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/sagarlakhani/
 * @since             1.0.0
 * @package           Sync_Post_With_Language_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Sync Post with Language Addon
 * Plugin URI:        https://profiles.wordpress.org/sagarlakhani/
 * Description:       This Plugin is used to extend the functionality of the plugin "Sync Post With Other Site" and allows syncing posts with different languages by using chatgpt-4o-latest API.
 * Version:           1.0.0
 * Author:            Sagar Lakhani
 * Author URI:        https://profiles.wordpress.org/sagarlakhani/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sync-post-with-language-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SYNC_POST_WITH_LANGUAGE_ADDON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sync-post-with-language-addon-activator.php
 */
function activate_sync_post_with_language_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sync-post-with-language-addon-activator.php';
	Sync_Post_With_Language_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sync-post-with-language-addon-deactivator.php
 */
function deactivate_sync_post_with_language_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sync-post-with-language-addon-deactivator.php';
	Sync_Post_With_Language_Addon_Deactivator::deactivate();
}

/**
 * Checks if the "Sync Post with Other Site" plugin is installed and activated.
 *
 * This function ensures that the AI Sync Translator plugin only works when
 * "Sync Post with Other Site" is active. If the required plugin is missing,
 * it deactivates AI Sync Translator and displays an admin notice.
 */
function kspwla_sync_translator_check_dependency() {
	// Check if the required plugin is not active.
	if ( ! is_plugin_active( 'sync-post-with-other-site/SyncPostWithOtherSite.php' ) ) {
		// Deactivate this plugin to prevent issues.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// Display an admin notice about the missing dependency.
		add_action( 'admin_notices', 'kspwla_sync_translator_admin_notice' );
	}
}

// Hook the dependency check into the WordPress admin initialization process.
add_action( 'admin_init', 'kspwla_sync_translator_check_dependency' );

/**
 * Displays an admin notice if the required "Sync Post with Other Site" plugin is not installed.
 *
 * This function outputs an error message in the WordPress admin dashboard,
 * informing users that they need to install and activate the required plugin
 * for AI Sync Translator to function properly.
 */
function kspwla_sync_translator_admin_notice() {
	echo '<div class="error"><p><strong>Sync Post with Language Addon</strong> requires the <a href="https://wordpress.org/plugins/sync-post-with-other-site/" target="_blank">Sync Post with Other Site</a> plugin to be installed and activated. Please install and activate it first.</p></div>';
}

register_activation_hook( __FILE__, 'activate_sync_post_with_language_addon' );
register_deactivation_hook( __FILE__, 'deactivate_sync_post_with_language_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sync-post-with-language-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sync_post_with_language_addon() {

	$plugin = new Sync_Post_With_Language_Addon();
	$plugin->run();
}
run_sync_post_with_language_addon();
