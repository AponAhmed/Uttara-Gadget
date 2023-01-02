<?php

/**
 * Plugin Name: UTTG
 * Plugin URI: https://siatexltd.com/
 * Description: -about-us
 * Author: SiATEX
 * Author URI: https://www.siatex.com
 * Version: 1.0
 * Text Domain: uttg;
 */

namespace Aponahmed\Uttaragedget;

use Aponahmed\Uttaragedget\src\AdminController;
use Aponahmed\Uttaragedget\src\Frontend;
use Aponahmed\Uttaragedget\src\Init;
use Aponahmed\Uttaragedget\src\Contact;

define('__UTTG_DIR', dirname(__FILE__));
define('__UTTG_ASSETS', plugin_dir_url(__FILE__) . "assets/");
//Autoloader 
require 'vendor/autoload.php';

/**
 * Plugin Root Class
 *
 * @author Apon
 */
class Uttg
{
    //put your code here
    private object $AdminController;
    private object $FrontEnd;
    public $contactSystem;

    public function __construct()
    {
        register_activation_hook(__FILE__, [Init::class, 'active_plugin']);
        //Global Ajax Hook
        $this->ajaxHookInit();
        $this->contactSystem = new Contact();
        //Init Admin And Frontend Controller
        if (is_admin()) {
            $this->AdminController = new AdminController();
        } else {
            $this->AdminController = new Frontend();
        }
    }

    public function ajaxHookInit()
    {
    }

    /**
     * Initialization Of Plugin
     * @return \Aponahmed\Uttaragedget\Uttg
     */
    public static function init()
    {
        return new Uttg();
    }
}

$UTTG = Uttg::init();
//var_dump($UTTG);
