<?php
namespace showAndTell\includes;

/**
 * Class SatLoadReactApp.
 */
 class SatLoadReactApp
 {

  /**
   * @var string
   */
     private $selector = '';
     /**
      * @var string
      */
     private $limit_load_hook = '';
     /**
      * @var bool|string
      */
     private $limit_callback = '';

     /**
      * SatLoadReactApp constructor.
      *
      * @param string $enqueue_hook        Hook to enqueue scripts.
      * @param string $limit_load_hook     Limit load to hook in admin load. If front end pass empty string.
      * @param bool|string $limit_callback Limit load by callback result. If back end send false.
      * @param string $css_selector        Css selector to render the application.
      */
     public function __construct($enqueue_hook, $limit_load_hook, $limit_callback = false, $css_selector)
     {
         $this->selector = $css_selector;
         $this->limit_load_hook = $limit_load_hook;
         $this->limit_callback = $limit_callback;

         add_action($enqueue_hook, [$this, 'load_react_app']);
     }

     /**
      * Load react app files in the WordPress admin.
      *
      * @param $hook
      *
      * @return bool|void
      */
     public function load_react_app($hook)
     {
         // Limit app load in admin by admin page hook
         $is_main_dashboard = $hook === $this->limit_load_hook;
         if (! $is_main_dashboard && is_bool($this->limit_callback)) {
             return;
         }

         // Limit app load in front end by callback
         $limit_callback = $this->limit_callback;
         if (is_string($limit_callback) && ! $limit_callback()) {
             return;
         }

         // Get assets links
         $assets_files = $this->get_assets_files();

         $js_files = array_filter($assets_files, function ($file_string) {
             return pathinfo($file_string, PATHINFO_EXTENSION) === 'js';
         });
         $css_files = array_filter($assets_files, function ($file_string) {
             return pathinfo($file_string, PATHINFO_EXTENSION) === 'css';
         });

         // Load css files
         foreach ($css_files as $index => $css_file) {
             wp_enqueue_style('react-plugin-' . $index, SAT_REACT_APP_BUILD . $css_file);
         }

         // Load js files
         foreach ($js_files as $index => $js_file) {
             wp_enqueue_script('react-plugin-' . $index, SAT_REACT_APP_BUILD . $js_file, array(), SHOW_AND_TELL_VERSION, true);
         }

         // Variables for app use = These variables will be available in window.satReactData variable
         wp_localize_script(
             'react-plugin-0',
             'satReactData',
             array( 'appSelector' => $this->selector )
         );
     }

     /**
      * Get app entry points asset files
      *
      * @return mixed
      */
     private function get_assets_files()
     {
         // Request manifest file
         // if the WordPress api was in a remote server
         // we would need to use SAT_MANIFEST_URL.
         $request = file_get_contents(SAT_MANIFEST_PATH);

         // If the remote request fails
         if (! $request) {
             return false;
         }

         // Convert json to php array
         $files_data = json_decode($request);
         if ($files_data === null) {
             return;
         }

         // No entry points found
         if (! property_exists($files_data, 'entrypoints')) {
             return false;
         }

         return $files_data->entrypoints;
     }
 }
