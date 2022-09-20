<?php

namespace App\Classes;

use Config\Services;

class Route {

    private static $instance = null;

    public static function config()
    {
        if (!self::$instance) {
            self::$instance = Services::routes();
        }
        return self::$instance;
    }
}