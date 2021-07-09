<?php
namespace showAndTell\includes\db;

use showAndTell\includes\Utils;

class Fields extends Base
{
    public function __construct()
    {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'show_and_tell_fields';
        $this->primary_key = 'id';
        $this->caching_time = 3600;

        if (defined('SHOW_AND_TELL_DBVERSION')) {
            $this->version = SHOW_AND_TELL_DBVERSION;
        } else {
            $this->version = '1.0.0';
        }
    }

    public function create_table()
    {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $charset_collate = $wpdb->get_charset_collate();

        $sql =
        "CREATE TABLE IF NOT EXISTS {$this->table_name} (
        `id` mediumint(11) NOT NULL AUTO_INCREMENT,
        `form_id` mediumint(11) NOT NULL,
        `name` longtext,
        `type` longtext,
        `label` longtext,
        `label_pos` varchar(15) DEFAULT NULL,
        `default_value` longtext,
        `order` int(11) DEFAULT NULL,
        `required` bit(1) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
        ) {$charset_collate};";

        dbDelta($sql);
    }

    public function get_columns()
    {
        return array(
            'id'            => '%d',
            'form_id'       => '%d',
            'name'          => '%s',
            'type'          => '%s',
            'label'         => '%s',
            'label_pos'     => '%s',
            'default_value' => '%s',
            'order'         => '%d',
            'required'      => '%d',
            'created_at'    => '%s',
            'updated_at'    => '%s',
        );
    }

    public function get_column_defaults()
    {
        return array(
            'id'            => 0,
            'form_id'       => 0,
            'type'          => '',
            'name'          => '',
            'label'         => '',
            'label_pos'     => '',
            'default_value' => '',
            'order'         => 0,
            'required'      => 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => '',
        );
    }

    public function get_entries($args = array(), $count = false)
    {
        global $wpdb;

        $defaults = array(
            'id'       => 0,
            'form_id'  => 0,
            'order_by' => 'order',
            'order'    => 'ASC'
        );

        $args = wp_parse_args($args, $defaults);

        $where = '';

        // Quyery fields by form id
        $where .= "WERE `form_id` = {$args['form_id']} ";

        // Order by
        $args['order_by'] = ! array_key_exists($args['order_by'], $this->get_columns())
        ?
        $this->primary_key
        :
        $args['order_by'];

        // Caching
        $cache_key = md5('ds_fields_' . $args['form_id']);

        $results = wp_cache_get($cache_key, 'fields');

        if (false === $results) {
            $results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['order_by']} {$args['order']}"
                )
            );

            wp_cache_set($cache_key, $results, 'fields', $this->caching_time);
        }

        // Remove escape sequences
        foreach ($results as $field => $field_items) {
            foreach ($field_items as $key => $value) {
                $results[$field]->$key = Utils::remove_slashes($value);
            }
        }
    }
}
