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

    public $contactSys;
    //put your code here
    public function __construct($contactSys)
    {
        $this->contactSys = $contactSys;
        add_action("admin_menu", [$this, "AdminMenu"]);
        add_action('admin_enqueue_scripts', [$this, 'adminScript']);
        add_action('wp_ajax_deleteData', [$this, 'deleteData']);
        add_action('wp_ajax_option_save', [$this, 'option_save']);
    }


    function deleteData()
    {
        global $wpdb;
        if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['tablename']) && !empty($_POST['tablename'])) {
            $id = $_POST['id'];
            $tablename = $_POST['tablename'];
            $resp = $wpdb->delete($wpdb->prefix . $tablename, ['id' => $id]);
            if ($resp) {
                echo json_encode(['error' => false, 'message' => 'Data Deleted successfully']);
            } else {
                echo json_encode(['error' => true, 'message' => 'Data could not be Deleted,' . $wpdb->last_error]);
            }
        }
        wp_die();
    }

    /**
     * Admin Script Init
     */
    function adminScript($hook)
    {
        //if (strpos($hook, 'uttara') !== false) {
        wp_enqueue_style('uttg-admin-style', __UTTG_ASSETS . 'admin-style.css');
        wp_enqueue_style('uttg-elements-style', __UTTG_ASSETS . 'elements.css');
        wp_enqueue_style('uttg-admin-column', __UTTG_ASSETS . 'column.css');
        wp_enqueue_script('uttg-admin-script', __UTTG_ASSETS . 'admin-script.js', array('jquery'), '1.0');
        wp_localize_script('uttg-admin-script', 'uttg', array('ajax_url' => admin_url('admin-ajax.php')));
        //}
    }

    static function getOption()
    {
        $options = json_decode(get_option('uttg-options'), true);
        $defaults = [
            'name' => '',
            'address' => '',
            'invoice_notes' => '',
            'currency' => 'TK',
            'prefix' => 'UT1054-'
        ];
        $options = array_merge($defaults, $options);
        return $options;
    }

    function option_save()
    {
        $data = array();
        parse_str($_POST['fData'], $data);
        if (isset($data['uttg-options'])) {
            update_option('uttg-options', json_encode($data['uttg-options']));
        }
        echo 1;
        wp_die();
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

        // add_submenu_page(
        //     "uttara-gadget", //$parent_slug
        //     "Exchange", //$page_title
        //     "Exchange", //$menu_title
        //     "manage_options", //$capability
        //     "exchange", //$menu_slug
        //     [ExchangeController::class, 'exchangeList'] //Calback
        // );


        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Customer", //$page_title
            "Customer", //$menu_title
            "manage_options", //$capability
            "customer", //$menu_slug
            [CustomerController::class, 'customerList'] //Calback
        );
        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Settings", //$page_title
            "Settings", //$menu_title
            "manage_options", //$capability
            "uttg-settings", //$menu_slug
            [Views::class, 'settingsView'] //Calback
        );

        add_submenu_page(
            "uttara-gadget", //$parent_slug
            "Mail Config", //$page_title
            "Mail Config", //$menu_title
            "manage_options", //$capability
            "mail-admin", //$menu_slug
            [$this->contactSys->admin, 'optionPage'] //Calback
        );
    }
}
