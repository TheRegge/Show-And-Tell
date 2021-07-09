<?php
namespace showAndTell\api\admin;

use showAndTell\api\admin\Forms_Routes;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

class Routes
{
    public $Forms_Routes;

    public function __construct()
    {
        $this->Forms_Routes = new Forms_Routes();
    }

    public function register_routes()
    {
        $this->Forms_Routes->register_routes();
    }
}
