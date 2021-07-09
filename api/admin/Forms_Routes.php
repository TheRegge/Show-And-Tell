<?php
namespace showAndTell\api\admin;

use showAndTell\includes\db\Forms;
use showAndTell\includes\db\Fields;
use showAndTell\includes\Utils;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

class Forms_Routes extends \WP_REST_Controller
{
    protected $namespace; // the rest api namespace, not the php namespace
    // protected $route;
    // protected $methods;
    // protected $callback;
    protected $dbforms;
    protected $dbfields;
    public $routes;

    public function __construct()
    {
        $this->namespace = 'show-and-tell/v1';
        $this->dbforms = new Forms();
        $this->dbfields = new Fields();
    }

    public function register_routes()
    {
        /**
         * Route: admin/forms (READ)
         *
         * GET ALL FORMS
         *
         * args:
         * number int   default 999
         * count  bool  default 0  (int evalutated as bool)
         */
        register_rest_route(
            $this->namespace,
            'admin/forms',
            array(
                'methods'  => \WP_REST_Server::READABLE,
                'callback' => array($this, 'get_forms'),
                'args'     => array(
                    'number' => array(
                        'default'           => 999,
                        'sanitize_callback' => 'absint',
                    ),
                    'count' => array(
                        'default'           => 0,
                        'sanitize_callback' => 'absint',
                    )
                ),
                'permission_callback' => array( $this, 'get_forms_permissions_check' ),
            )
        );

        /**
         * Route: admin/form (create)
         *
         * CREATE NEW FORM
         */
        register_rest_route(
            $this->namespace,
            'admin/form',
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'create_form'),
                'permission_callback' => array( $this, 'create_form_permissions_check' ),
            )
        );

        /**
         * Route: admin/form/form_id (READ)
         *
         * GET ONE FORM
         *
         * @param int form_id   The id of the form to get
         */
        register_rest_route(
            $this->namespace,
            'admin/form/(?P<form_id>\d+)',
            array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => array($this, 'get_form'),
                'sanitize_callback'   => 'absint',
                'permission_callback' => array( $this, 'get_form_permissions_check' ),
            )
        );

        /**
         * Route: admin/form/form_id (update)
         *
         * UPDATE ONE FORM
         */
        register_rest_route(
            $this->namespace,
            'admin/form/(?P<form_id>\d+)',
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'update_form'),
                'sanitize_callback'   => 'absint',
                'permission_callback' => array($this, 'update_form_permissions_check'),
            )
        );

        /**
         * Route: admin/form/form_id (DELETE)
         *
         * DELETE ONE FORM
         *
         * @param int form_id   The id of the form to delete
         */
        register_rest_route(
            $this->namespace,
            'admin/form/(?P<form_id>\d+)',
            array(
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => array($this, 'delete_form'),
                'sanitize_callback'   => 'absint',
                'permission_callback' => array( $this, 'delete_form_permissions_check' ),
            )
        );
    }

    public function get_forms_permissions_check($request)
    {
        return current_user_can('manage_options');
    }

    /**
     * Get all forms for the current user
     *
     * Note: dbforms->get_forms uses
     * `get_current_user_id()` to find the
     * current user id.
     *
     * @param httpRequest $request
     * @return WP_REST_Response
     */
    public function get_forms($request)
    {
        $params = $request->get_params();
        $count = (bool)$params['count'];

        $args = array(
            'number'  => intval($params['number']),
            'form_id' => intval($request['form_id']),
        );
        $forms = $this->dbforms->get_forms($args, $count);

        // Get form fields
        // foreach ($forms as $form) {
        //     $fields = $this->dbfields
        // }
        return new \WP_REST_Response($forms, 200);
    }

    public function get_form_permissions_check($request)
    {
        return current_user_can('manage_options');
    }

    public function get_form($request)
    {
        if (!isset($request['form_id'])) {
            return new \WP_Error('get_error', __('Error getting form', 'show-and-tell'));
        }

        $form = $this->dbforms->get_by('form_id', $request['form_id']);
        return new \WP_REST_Response($form, 200);
    }

    public function create_form_permissions_check($request)
    {
        return current_user_can('manage_options');
    }

    public function create_form($request)
    {
        $user_id = get_current_user_id();
        // Save the form
        $requestParams              = array('title', 'status', 'multiplesubmits', 'disabledate');
        $formSettings               = Utils::get_array_from_request($requestParams, $request, 'POST');
        $formSettings['user_id']    = $user_id;
        $insertedFormId = $this->dbforms->insert($formSettings);

        // Form fields
        $fields = Utils::get_array_from_request(['items'], $request, 'POST')['items'];
        $savedFieldIds = $this->_save_form_fields($fields, $insertedFormId);

        if ($insertedFormId && count($savedFieldIds) === count($request['items'])) {
            return new \WP_REST_Response(array(
                'status' => 'success',
                'form_id' => $insertedFormId
            ), 200);
        } else {
            // TODO: Test error cases

            // Cleanup
            // Delete form if any
            if ($insertedFormId) {
                $this->_delete_form($insertedFormId);
            }
            // Delete fields if any
            foreach ($savedFieldIds as $field_id) {
                $this->_delete_form_field($field_id);
            }

            $message = 'The form was not saved.';

            return new \WP_REST_Response(array(
                'status' => 'error',
                'message' => $message
            ), 500);
        }
    }

    public function update_form_permissions_check($request)
    {
        return current_user_can('manage_options');
    }

    public function update_form($request)
    {
        $requestParams = array('form_id', 'title', 'status', 'manysubmits', 'disabledate');
        $data          = Utils::get_array_from_request($requestParams, $request, 'POST');
        $result        = $this->dbforms->update($data['form_id'], $data);

        // Form fields
        $fields = Utils::get_array_from_request(['items'], $request, 'POST');
        $savedFieldIds = $this->_save_form_fields($fields, $data['form_id']);

        if ($result) {
            return new \WP_REST_Response(array(
                'status' => 'success',
            ), 200);
        }
    }

    public function delete_form_permissions_check($request)
    {
        return current_user_can('manage_options');
    }

    public function delete_form($request)
    {
        $form_id = $request['form_id'];
        return $this->_delete_form($form_id);
    }

    private function _delete_form($form_id)
    {
        $deleted = $this->dbforms->delete($form_id);
        if ($deleted) {
            return new \WP_REST_Response($deleted, 200);
        } else {
            return new \WP_Error('delete_error', __('Error Deleting Form ' . $form_id, 'show-and-tell'));
        }
    }

    private function _delete_form_field($field_id)
    {
        $this->dbfields->delete($field_id);
    }

    private function _save_form_fields($fields, $form_id)
    {
        $saved_field_ids = [];
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $field['form_id'] = $form_id;
                $fieldId = $this->dbfields->insert($field);
                if (!$fieldId) {
                    break;
                }
                $saved_field_ids[] = $fieldId;
            }
        }

        return $saved_field_ids;
    }
}
