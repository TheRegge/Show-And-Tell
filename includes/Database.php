<?php
namespace showAndTell\includes;

use showAndTell\includes\db\Entries;
use showAndTell\includes\db\Forms;
use showAndTell\includes\db\Fields;

/**
 * Handles the plugin interactions with
 * custom tables on the WordPress database.
 *
 * @since       1.0.0
 * @package     Show_And_Tell
 * @subpackage  Show_And_Tell/includes
 */

 /**
  * Handles the plugin interactions with
  * custom tables on the WordPress database.
  *
  * @package    Show_And_Tell
  * @subpackage Show_And_Tell/includes
  * @author     Regis Zaleman <TheRegge>
  */
class Database
{
    /**
     * The current version of the plugin's database schema.
     *
     * @since   1.0.0
     * @access  protected
     * @var     string     $dbversion   The current version of the plugin's database schema.
     */
    protected $dbversion;

    public $forms;

    public $entries;

    public $fields;

    public function __construct()
    {
        if (defined('SHOW_AND_TELL_DBVERSION')) {
            $this->dbversion = SHOW_AND_TELL_DBVERSION;
        } else {
            $this->dbversion = '1.0.0';
        }

        $this->forms   = new Forms();
        $this->entries = new Entries();
        $this->fields  = new Fields();
    }

    /**
     * Create/Update the plugin's custom database tables.
     *
     * @since 1.0.0
     * @return void
     */
    public function install_db()
    {
        $this->forms->create_table();
        $this->entries->create_table();
        $this->fields->create_table();
    }

    /**
     * Retrieve the installed version of the plugin's database schema.
     *
     * @since   1.0.0
     * @return  string|false    The installed version number of the plugin's database schema.
     *                          Returns false if no value was installed.
     */
    public function get_installed_dbversion()
    {
        $option_name = Utils::get_option_basename() . '_dbversion';
        return get_option($option_name);
    }

    /**
     * Runs the database update code if installed version
     * is earlier that plugin version.
     *
     * @since 1.0.0
     * @return void
     */
    public function update_db()
    {
        $installed_dbversion = $this->get_installed_dbversion();
        $installed_is_earlier = version_compare($installed_dbversion, $this->dbversion, "<");

        if ($installed_is_earlier) {
            $this->install_db();
        }
    }
}
