<?php
namespace showAndTell\includes\db;

use showAndTell\includes\Utils;

class Forms extends Base
{
    /**
     * Class constructor
     *
     * @access public
     * @since 1.0.0
     */
    public function __construct()
    {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'show_and_tell_forms';
        $this->primary_key = 'form_id';

        if (defined('SHOW_AND_TELL_DBVERSION')) {
            $this->version = SHOW_AND_TELL_DBVERSION;
        } else {
            $this->version = '1.0.0';
        }
    }

    /**
     * Create the database table.
     * And aslo creates/updates the dbversion option.
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
        `form_id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `title` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `user_id` bigint(20) unsigned DEFAULT NULL,
        `multiplesubmits` tinyint(1) unsigned DEFAULT 0,
        `status` varchar(30) NOT NULL,
        `disabledate` datetime DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (form_id)
        ) {$charset_collate};";

        dbDelta($sql);

        $option_name = Utils::get_option_basename() . '_dbversion';
        update_option($option_name, $this->version);
    }

    /**
     * Get columns and formats
     *
     * @access  public
     * @since   1.0.0
     *
     * @return  array   A keyed array. The key is the column name and the value is the format.
     */
    public function get_columns()
    {
        return array(
            'form_id'         => '%d',
            'title'           => '%s',
            'multiplesubmits' => '%d',
            'user_id'         => '%d',
            'status'          => '%s',
            'disabledate'     => '%s',
            'created_at'      => '%s',
            'updated_at'      => '%s',
        );
    }

    /**
     * Get default column values
     *
     * @access  public
     * @since   1.0.0
     * @return  array    A keyed array. The key is the column name, the value is the column default value.
     */
    public function get_column_defaults()
    {
        return array(
            'form_id'         => 0,
            'title'           => '',
            'multiplesubmits' => 0,
            'user_id'         => 0,
            'status'          => 'draft',
            'disabledate'     => null,
            'created_at'      => null,
            'updated_at'      => null,

        );
    }

    /**
     * Retrieve forms from the database
     *
     * @access  public
     * @since   1.0.0
     *
     * @param   array   $args
     * @param   boolean $count  Return only the total number of results found (optional)
     * @return  mixed   Either an array of forms or an integer count of number of result found for a given query
     */
    public function get_forms($args = array(), $count = false)
    {
        global $wpdb;

        $defaults = array(
            'number'      => 20,
            'offset'      => 0,
            'form_id'     => 0,
            'user_id'     => get_current_user_id(),
            'status'      => '',
            'disabledate' => '',
            'order_by'    => 'form_id',
            'order'       => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);

        if ($args['number'] < 1) {
            $args['number'] = 999999999999;
        }

        $where = '';

        // specific referrals
        if (!empty($args['form_id'])) {
            if (is_array($args['form_id'])) {
                $form_ids = implode(',', $args['form_id']);
            } else {
                $form_ids = intval($args['form_id']);
            }

            $where .= "WHERE `form_id` IN( {$form_ids} ) ";
        }

        if (!empty($args['status'])) {
            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['status'])) {
                $where .= " `status` IN('" . implode("','", $args['status']) . "') ";
            } else {
                $where .= " `status` = '" . $args['status'] . "' ";
            }
        }

        // Query filter by date
        $this->make_where_date($args, $where);

        $args['order_by'] = ! array_key_exists($args['order_by'], $this->get_columns())
        ?
        $this->primary_key
        :
        $args['order_by'];

        $cache_key = (true === $count) ? md5('ds_forms_count' . serialize($args)) : md5('ds_forms_' . serialize($args));

        $results = wp_cache_get($cache_key, 'forms');

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

            wp_cache_set($cache_key, $results, 'forms', 3600);
        }

        // Remove escape sequences
        if (is_array($results)) {
            foreach ($results as $form => $form_items) {
                foreach ($form_items as $key => $value) {
                    $results[$form]->$key = Utils::remove_slashes($value);
                }
            }
        }

        return $results;
    }

    /**
     * Overide base method to format the disabledate field
     *
     * @access  public
     * @since   1.0.0
     * @return  int
     */
    // public function insert($data, $type='')
    // {
    //     if (isset($data['disabledate'])) {
    //         if ($data['disabledate'] === '') {
    //             unset($data['disabledate']);
    //         } else {
    //             $data['disabledate'] = date('Y-m-d H:i:s', strtotime($data['disabledate']));
    //         }
    //     }

    //     return parent::insert($data, $type);
    // }

    /**
     * Return the number of results found for a given query
     */
    public function count($args=array())
    {
        return $this->get_forms($args, true);
    }

    public function accepts_multiple_submissions($form_id)
    {
        $form_id = absint($form_id);
        return $this->get_column('manysubmits', $form_id);
    }
}
