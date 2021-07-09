<?php
namespace showAndTell;

require_once(plugin_dir_path(__FILE__) . './vendor/autoload.php');

use showAndTell\includes\Show_And_Tell;
use showAndTell\includes\Activator;
use showAndTell\includes\Deactivator;
use showAndTell\includes\SatLoadReactApp;

// Temporary way of integrating react app
// Setting react app path constants
define('SAT_REACT_DIR_URL', plugin_dir_url(__FILE__) . 'frontend/');
define('SAT_REACT_APP_BUILD', SAT_REACT_DIR_URL . 'build/');
define('SAT_MANIFEST_URL', SAT_REACT_APP_BUILD . 'asset-manifest.json');

define('SAT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) . 'frontend/');
define('SAT_REACT_PATH_BUILD', SAT_PLUGIN_DIR_PATH . 'build/');
define('SAT_MANIFEST_PATH', SAT_REACT_PATH_BUILD . 'asset-manifest.json');

/**
 * Calling the plugin class with parameters
 */
function rp_load_plugin()
{
    // Loading the app in WordPress chosen screen
    $load_in_page_hook = 'toplevel_page_admin-showandtell';
    new SatLoadReactApp('admin_enqueue_scripts', $load_in_page_hook, false, '#sat-admin-dashboard');
    // Loading the app in WordpPress front end page (in the footer)
    // new RpLoadReactapp( 'wp_enqueue_scripts', '', 'is_front_page', '#site-footer' );
}

add_action('init', 'showandtell\rp_load_plugin');




/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Show_And_Tell
 *
 * @wordpress-plugin
 * Plugin Name:       Show And Tell
 * Plugin URI:        https://zaleman.com/showandtell
 * Description:       Show and Tell enables students to submit their digital work to be published into a beautiful group presentation.
 * Version:           1.0.0
 * Author:            Regis Zaleman
 * Author URI:        https://zaleman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       show-and-tell
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SHOW_AND_TELL_VERSION', '1.0.0');
define('SHOW_AND_TELL_DBVERSION', '1.0.1');

/** Define the base path to this plugin */
define('SAT_PLUGIN_PATH', plugin_dir_path(__FILE__));

/** Define form submission statuses constants */
define('SAT_SUBMISSION_STATUS_SUBMITTED', 'submitted');
define('SAT_SUBMISSION_STATUS_PUBLISHED', 'published');
define('SAT_SUBMISSION_STATUS_EDITED', 'edited');

/** Custom shortcode types */
define('SAT_INPUT_PULLDOWN', 'sat-input-pulldown');
define('SAT_INPUT_RICHTEXT', 'sat-input-richtext');
define('SAT_INPUT_TEXT', 'sat-input-text');
define('SAT_INPUT_TEXTAREA', 'sat-input-textarea');
define('SAT_INPUT_UPLOADIMAGE', 'sat-input-uploadimage');
define('SAT_INPUT_VIDEO', 'sat-input-video');

/** Define admin notices types */
define('SAT_ADMIN_NOTICE_ERROR', 'notice-error');
define('SAT_ADMIN_NOTICE_INFO', 'notice-info');
define('SAT_ADMIN_NOTICE_SUCCESS', 'notice-success');
define('SAT_ADMIN_NOTICE_WARNING', 'notice-warning');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-Activator.php
 */
function activate_show_and_tell()
{
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-Deactivator.php
 */
function deactivate_show_and_tell()
{
    Deactivator::deactivate();
}

register_activation_hook(__FILE__, '\showAndTell\activate_show_and_tell');
register_deactivation_hook(__FILE__, '\showAndTell\deactivate_show_and_tell');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_show_and_tell()
{
    $plugin = new Show_And_Tell();
    $plugin->run();
}
run_show_and_tell();
