<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\Views;
use Aponahmed\Uttaragedget\src\InvoiceController;
use Aponahmed\Uttaragedget\src\CustomerController;
use Aponahmed\Uttaragedget\src\ExchangeController;

/**
 * Description of AdminController
 *
 * @author Apon
 */
class AdminController
{

    public $contactAdmin;
    //put your code here
    public function __construct()
    {
        add_action("admin_menu", [$this, "AdminMenu"]);
        add_action('admin_enqueue_scripts', [$this, 'adminScript']);
    }

    /**
     * Admin Script Init
     */
    function adminScript($hook)
    {
        //if (strpos($hook, 'uttara') !== false) {
        wp_enqueue_style('uttg-admin-style', __UTTG_ASSETS . 'admin-style.css');
        wp_enqueue_style('uttg-admin-column', __UTTG_ASSETS . 'column.css');
        wp_enqueue_script('uttg-admin-script', __UTTG_ASSETS . 'admin-script.js', array('jquery'), '1.0');
        wp_localize_script('uttg-admin-script', 'uttg', array('ajax_url' => admin_url('admin-ajax.php')));
        //}
    }

    /**
     * Menu Register for Admin Page
     */
    function AdminMenu()
    {
        add_menu_page("Urrara Gadget", "Uttara Gadget", "manage_options", "uttara-gadget", [Views::class, 'adminMainView'], __UTTG_ASSETS . "logo-color.png", 10);
        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Invoice", //$page_title
            "Invoice", //$menu_title
            "manage_options", //$capability
            "invoice", //$menu_slug
            [InvoiceController::class, 'invoiceList'] //Calback
        );

        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Exchange", //$page_title
            "Exchange", //$menu_title
            "manage_options", //$capability
            "exchange", //$menu_slug
            [ExchangeController::class, 'exchangeList'] //Calback
        );

        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Customer", //$page_title
            "Customer", //$menu_title
            "manage_options", //$capability
            "customer", //$menu_slug
            [CustomerController::class, 'customerList'] //Calback
        );
    }
}
