<?php
namespace showAndTell\includes\db;

use showAndTell\includes\Utils;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * DB base class
 *
 * @since 1.0.0
 */
abstract class Base
{
    /**
     * The name of our database table
     *
     * @access  public
     * @since   1.0.0
     *
     * @var     string
     */
    public $table_name;

    /**
     * The version of our database table
     *
     * @access  public
     * @since   1.0.0
     *
     * @var     string
     */
    public $version;

    /**
     * The name of the primary column
     *
     * @access  public
     * @since   1.0.0
     *
     * @var     string
     */
    public $primary_key;

    /**
     * Class constructor
     *
     * @access public
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Whitelist of columns
     *
     * @access  public
     * @since   1.0.0
     * @return  array
     */
    public function get_columns()
    {
        return array();
    }

    /**
     * Default column values
     *
     * @access  public
     * @since   1.0.0
     *
     * @return  array
     */
    public function get_column_defaults()
    {
        return array();
    }

    /**
     * Retrieve a row by the primary key
     *
     * @access  public
     * @since   1.0.0
     *
     * @param   integer $row_id
     * @return  object
     */
    public function get($row_id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id));
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @access  public
     * @since   1.0.0
     *
     * @param   string $column
     * @param   mixed $row_id
     * @return  object
     */
    public function get_by($column, $row_id, $remove_slashes=true)
    {
        global $wpdb;
        $column = esc_sql($column);
        $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id));

        if ($remove_slashes) {
            foreach ($results as $key => $value) {
                $results->$key = Utils::remove_slashes($value);
            }
        }

        return $results;
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @access  public
     * @since   1.0.0
     *
     * @param   string $column
     * @param   integer $row_id
     * @return  string
     */
    public function get_column($column, $row_id)
    {
        global $wpdb;
        $column = esc_sql($column);
        return $wpdb->get_var($wpdb->prepare("SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id));
    }

    /**
     * Retrieve a specific column's value by the specified column / value
     *
     * @access  public
     * @since   1.0.0
     *
     * @param   string $column
     * @param   string $column_where
     * @param   mixed $column_value
     * @return  string
     */
    public function get_column_by($column, $column_where, $column_value)
    {
        global $wpdb;
        $column_where = esc_sql($column_where);
        $column       = esc_sql($column);
        return $wpdb->get_var($wpdb->prepare("SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value));
    }

    /**
     * Insert a new row
     *
     * @access  public
     * @since   1.0.0
     *
     * @return int
     */
    public function insert($data, $type='')
    {
        global $wpdb;

        // Set default values
        $data = wp_parse_args($data, $this->get_column_defaults());
        do_action('show_and_tell_pre_insert_' . $type, $data);

        // Initialize column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);
        $wpdb->replace($this->table_name, $data, $column_formats);

        do_action('show_and_tell_post_insert_' . $type, $wpdb->insert_id, $data);

        return $wpdb->insert_id;
    }

    /**
     * Update a row
     *
     * @access  public
     * @since   1.0.0
     * @return  bool
     */
    public function update($row_id, $data=array(), $where='')
    {
        global $wpdb;

        // Row ID must be a positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (empty($where)) {
            $where = $this->primary_key;
        }

        // Initialise column formats array
        $column_formats = $this->get_columns();

        // Force fields to be lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Reorder $columns_formats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        if (false === $wpdb->update($this->table_name, $data, array($where => $row_id), $column_formats)) {
            return false;
        }

        return true;
    }


    /**
     * Delete a row identified by the primary key
     *
     * @access  public
     * @since   1.0.0
     * @return  bool
     */
    public function delete($row_id = 0)
    {
        global $wpdb;

        if (empty($row_id)) {
            return false;
        }

        // Row ID must be a positive integer
        $row_id = absint($row_id);

        $row = $this->get_by('form_id', $row_id);

        if (!$row) {
            return false;
        }

        if (false === $wpdb->query($wpdb->prepare("DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id))) {
            return false;
        }

        return array(
            "action" => 'delete',
            "status" => 'success',
            "item"   => $row,
        );
    }

    /**
     * Check if the given table exists
     *
     * @since   1.0.0
     * @access  public
     * @param   string  $table The table name
     * @return  bool    if the table name exists
     */
    public function table_exists($table)
    {
        global $wpdb;
        $table = sanitize_text_field($table);

        return $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $table)) === $table;
    }

    protected function make_where_date($args, &$where)
    {
        if (!empty($args['date'])) {
            if (is_array($args['date'])) {
                // start date
                if (!empty($args['date']['start'])) {
                    if (false !== strpos($args['date']['start'], ':')) {
                        $format = 'Y-m-d H:i:s';
                    } else {
                        $format = 'Y-m-d 00:00:00';
                    }

                    $start = date($format, strtotime($args['date']['start']));

                    if (!empty($where)) {
                        $where .= " AND `date` >= '{$start}'";
                    } else {
                        $where .= " WHERE `date` >= '{$start}'";
                    }
                }

                // end date
                if (!empty($args['date']['end'])) {
                    if (false !== strpos($args['date']['end'], ':')) {
                        $format = 'Y-m-d H:i:s';
                    } else {
                        $format = 'Y-m-d 23:59:59';
                    }

                    $end = date($format, strtotime($args['date']['end']));

                    if (!empty($where)) {
                        $where .= " AND `date` <= '{$end}'";
                    } else {
                        $where .= " WHERE `date` <= '{$end}'";
                    }
                }
            } else {
                // date is not an array, but as string
                $year  = date('Y', strtotime($args['date']));
                $month = date('m', strtotime($args['date']));
                $day   = date('d', strtotime($args['date']));

                if (empty($where)) {
                    $where .= " WHERE";
                } else {
                    $where .= " AND";
                }

                $where .= " $year = YEAR ( date ) AND $month = MONTH ( date ) AND $day = DAY ( date )";
            }
        }
    }
}
