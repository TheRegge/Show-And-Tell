<?php
namespace showAndTell\includes\db;

use showAndTell\includes\Utils;

class Entries extends Base
{
    /**
     * Class constructor
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct()
    {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'show_and_tell_entries';
        $this->primary_key = 'id';

        if (defined('SHOW_AND_TELL_DBVERSION')) {
            $this->version = SHOW_AND_TELL_DBVERSION;
        } else {
            $this->version = '1.0.0';
        }
    }
    /**
     * Create the dabase table
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function create_table()
    {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $charset_collate = $wpdb->get_charset_collate();

        $sql =
        "CREATE TABLE IF NOT EXISTS {$this->table_name} (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL,
        user_id bigint(20) unsigned DEFAULT NULL,
        field varchar(30) NOT NULL,
        value longtext NOT NULL,
        params longtext DEFAULT NULL,
        date datetime NOT NULL,
        PRIMARY KEY  (id)
        ) {$charset_collate};";

        dbDelta($sql);
    }

    /**
     * Get columns and formats
     *
     * @return array    A keyed array. The key is the column name and the value is the format.
     */
    public function get_columns()
    {
        return array(
            'id'      => '%d',
            'form_id' => '%d',
            'user_id' => '%d',
            'field'   => '%s',
            'value'   => '%s',
            'params'  => '%s',
            'date'    => '%s',
        );
    }

    /**
     * Get default column values
     *
     * @return array    A keyed array. The key is the column name, the value is the column default value.
     */
    public function get_column_defaults()
    {
        return array(
            'id'      => 0,
            'form_id' => 0,
            'user_id' => 0,
            'field'   => '',
            'value'   => '',
            'params'  => '',
            'date'    => date('Y-m-d H:i:s'),
        );
    }

    public function get_entries($args = array(), $count = false)
    {
        global $wpdb;

        $defaults = array(
            'number'   => 20,
            'offset'   => 0,
            'id'       => 0,
            'form_id'  => 0,
            'user_id'  => get_current_user_id(),
            'order_by' => 'form_id',
            'order'    => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);

        $where = '';

        // Querying multiple entries by id
        if (!empty($args['id'])) {
            if (is_array($args['id'])) {
                $ids = implode(',', $args['id']);
            } else {
                $ids = intval($args['id']);
            }

            $where .= "WHERE `id` IN( {$ids} ) ";
        }

        // Querying entries for one or multiple forms
        if (!empty($args['form_id'])) {
            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['form_id'])) {
                $form_ids = implode(',', $args['form_id']);
            } else {
                $form_ids = intval($args['form_id']);
            }

            $where .= " `form_id` IN( {$form_ids} ) ";
        }

        // Querying entries by user_id(s)
        if (!empty($args['user_id'])) {
            if (empty($where)) {
                $where .= ' WHERE';
            } else {
                $where .= " AND";
            }

            if (is_array($args['user_id'])) {
                $user_ids = implode(',', $args['user_id']);
            } else {
                $user_ids = intval($args['user_id']);
            }

            $where .= " `user_id` IN( {$user_ids} ) ";
        }

        // Query filter by date
        $this->make_where_date($args, $where);

        // Order by
        $args['order_by'] = ! array_key_exists($args['order_by'], $this->get_columns())
        ?
        $this->primary_key
        :
        $args['order_by'];

        $cache_key = (true === $count) ? md5('ds_entries_count' . serialize($args)) : md5('ds_entries_' . serialize($args));

        $results = wp_cache_get($cache_key, 'entries');

        if (false === $results) {
            if (true === $count) {
                $results = absint($wpdb->get_var("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};"));
            } else {
                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['order_by']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );
            }

            wp_cache_set($cache_key, $results, 'entries', 3600);
        }

        // Remove escape sequences
        foreach ($results as $entry => $entry_items) {
            foreach ($entry_items as $key => $value) {
                $results[$entry]->$key = Utils::remove_slashes($value);
            }
        }
        return $results;
    }

    public function count($args=array())
    {
        return $this->get_entries($args, true);
    }
}
