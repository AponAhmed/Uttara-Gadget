<?php

/**
 * Plugin Name: UTTG
 * Plugin URI: https://siatexltd.com/
 * Description: asd
 * Author: SiATEX
 * Author URI: https://www.siatex.com
 * Version: 1.0
 * Text Domain: uttg;
 */

namespace Aponahmed\Uttaragedget;

use Aponahmed\Uttaragedget\src\AdminController;
use Aponahmed\Uttaragedget\src\Frontend;

//Autoloader 
require 'vendor/autoload.php';

/**
 * Plugin Root Class
 *
 * @author Apon
 */
class Uttg {

    //put your code here
    private object $AdminController;
    private object $FrontEnd;

    public function __construct() {
        //Global Ajax Hook
        $this->ajaxHookInit();
        //Init Admin And Frontend Controller
        if (is_admin()) {
            $this->AdminController = new AdminController();
        } else {
            $this->AdminController = new Frontend();
        }
    }

    public function ajaxHookInit() {
        
    }

    /**
     * Initialization Of Plugin
     * @return \Aponahmed\Uttaragedget\Uttg
     */
    public static function init() {
        return new Uttg();
    }

}

Uttg::init();
