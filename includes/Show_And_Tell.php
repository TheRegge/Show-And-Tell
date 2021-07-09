<?php
namespace showAndTell\includes;

use showAndTell\admin\Admin;
use showAndTell\includes\Loader;
use showAndTell\api\admin\Routes as Admin_Routes;
use showAndTell\open\Main;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/includes
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
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/includes
 * @author     Regis Zaleman <TheRegge>
 */
class Show_And_Tell
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $show_and_tell    The string used to uniquely identify this plugin.
     */
    protected $show_and_tell;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The current version of the database schema
     *
     * @since   1.0.0
     * @access  protected
     * @var     string      $dbversion    The current version of the plugin's database schema.
     */
    protected $dbversion;

    /**
     * The custom database interface.
     *
     * @var Database  $db  Interface with the WordPress database for the plugin's custom tables.
     */
    protected $db;

    protected $admin_routes;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('SHOW_AND_TELL_VERSION')) {
            $this->version = SHOW_AND_TELL_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        if (defined('SHOW_AND_TELL_DBVERSION')) {
            $this->dbversion = SHOW_AND_TELL_DBVERSION;
        } else {
            $this->dbversion = '1.0.0';
        }


        $this->show_and_tell = 'show-and-tell';
        $this->db = new Database();
        $this->admin_routes = new Admin_Routes();

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_shortcodes();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Loader. Orchestrates the hooks of the plugin.
     * - I18n. Defines internationalization functionality.
     * - Admin. Defines all hooks for the admin area.
     * - Show_And_Tell_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        $this->loader = new Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new I18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Admin($this->get_show_and_tell(), $this->get_version());

        // $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_dashboard_page');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Main($this->get_show_and_tell(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    private function define_shortcodes()
    {
        $shortcodes_class = new Shortcodes($this->get_show_and_tell(), $this->get_version());

        $this->loader->add_shortcode('ds_start_form', $shortcodes_class, 'shortcode_start_form');
        $this->loader->add_shortcode('ds_end_form', $shortcodes_class, 'shortcode_end_form');
        $this->loader->add_shortcode('ds_input_text', $shortcodes_class, 'shortcode_input_text');
        $this->loader->add_shortcode('ds_output_text', $shortcodes_class, 'shortcode_output_text');
        $this->loader->add_shortcode('ds_input_textarea', $shortcodes_class, 'shortcode_input_textarea');
        $this->loader->add_shortcode('ds_input_imageupload', $shortcodes_class, 'shortcode_input_uploadimage');
        $this->loader->add_shortcode('ds_input_video', $shortcodes_class, 'shortcode_input_video');
        $this->loader->add_shortcode('ds_output_video', $shortcodes_class, 'shortcode_output_video');
        $this->loader->add_shortcode('ds_input_pulldown', $shortcodes_class, 'shortcode_input_pulldown');
        $this->loader->add_shortcode('ds_show_posts_grid', $shortcodes_class, 'shortcode_show_posts_grid');
        $this->loader->add_shortcode('ds_show_filters', $shortcodes_class, 'shortcode_show_filters');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        add_action('rest_api_init', array( $this->admin_routes, 'register_routes'));
        $this->loader->run();
        $this->db->update_db();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_show_and_tell()
    {
        return $this->show_and_tell;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Retrieve the version number of the plugin's
     * database schema.
     *
     * @since 1.0.0
     * @return string   The version number of the plugin's database schema.
     */
    public function get_dbversion()
    {
        return $this->dbversion;
    }
}
