<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/jamainrex
 * @since             1.0.0
 * @package           Mfsplugin
 *
 * @wordpress-plugin
 * Plugin Name:       Mind Full Solutions Plugin
 * Plugin URI:        https://github.com/jamainrex
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Jerex Lennon
 * Author URI:        https://github.com/jamainrex
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mfsplugin
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
define( 'MFSPLUGIN_VERSION', '1.0.0' );
define( 'MFSPLUGIN_SLUG', 'mfsplugin' );
define( 'MFSPLUGIN_PLUGIN_NAME', 'mfsplugin' );
define( 'MFSPLUGIN_NAME', 'Mind Full Solutions Plugin' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mfsplugin-activator.php
 */
function activate_mfsplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mfsplugin-activator.php';
	Mfsplugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mfsplugin-deactivator.php
 */
function deactivate_mfsplugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mfsplugin-deactivator.php';
	Mfsplugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mfsplugin' );
register_deactivation_hook( __FILE__, 'deactivate_mfsplugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mfsplugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mfsplugin() {

	$plugin = new Mfsplugin();
	$plugin->run();

}
run_mfsplugin();
