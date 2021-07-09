<?php
namespace showAndTell\includes;

use showAndTell\includes\Database;

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Show_And_Tell
 * @subpackage Show_And_Tell/includes
 * @author     Regis Zaleman <TheRegge>
 */
class Activator
{

    /**
     * Fired during plugin activation.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $db = new Database();
        $db->install_db();
    }
}
