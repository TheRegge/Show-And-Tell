<?php
namespace showAndTell\admin;

use showAndTell\admin\Forms;
use showAndTell\includes\Shortcodes;
use showAndTell\includes\Utils;
use showAndTell\includes\db\Forms as DBForms;
use showAndTell\includes\Textstrings;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/admin
 * @author     Regis Zaleman <TheRegge>
 */
class Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $show_and_tell    The ID of this plugin.
     */
    private $show_and_tell;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Holds an instance of Admin_Forms
     *
     * @since   1.0.0
     * @access  private
     * @var     \showAndTell\admin\Forms    $forms    An instance of Admin_Forms
     */
    private $forms;


    /**
     * Initialize the class and set its properties.
     *
     * @since      1.0.0
     * @param      string    $show_and_tell       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($show_and_tell, $version)
    {
        $this->show_and_tell = $show_and_tell;
        $this->version         = $version;
        // $this->forms           = new Forms();
        // $this->dbforms         = new DBForms();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->show_and_tell . '-react_app', plugin_dir_url(__FILE__) . 'css/show-and-tell-react-app.css', array(), $this->version, 'all');
        wp_enqueue_style('googlefonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@200;400&display=swap', array(), 'all');
        // Add icon font for material ui
        wp_enqueue_style($this->show_and_tell . '-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // Add localized data to script:
        $nonce = wp_create_nonce('wp_rest');
        $api_url = get_rest_url();
        $textstrings = Textstrings::getall();
        // Create a fake script path to enable appending the inline script
        // Since we are in 'admin', the path resolves to <plugin_dir_url>/admin/index.php
        // which outputs nothing (Silence is golden) but that is enough for WordPress
        // to output the localized javascript.
        $fakePath = plugin_dir_url(__FILE__);
        wp_enqueue_script('sat-wpapisettings', $fakePath, null, $this->version, true);
        wp_add_inline_script('sat-wpapisettings', "var satWpApiSettings = {'nonce': '{$nonce}', 'apiurl': '{$api_url}'}; var sat_textstrings = {$textstrings}", 'before');
    }


    public function register_dashboard_page()
    {
        if (! current_user_can('manage-options')) {
            return;
        }

        add_menu_page(
            esc_html__('Dashboard', 'show-and-tell'),
            esc_html__('Show & Tell', 'show-and-tell'),
            'edit_private_posts',
            'admin-showandtell',
            array( $this, 'include_dashboard_partial' ),
            'dashicons-welcome-learn-more',
            9999
        );
    }


    public function include_dashboard_partial()
    {
        if (! current_user_can('manage_options')) {
            return;
        }
        echo '<div id="sat-admin-dashboard"></div>';
    }
}
