<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\DataList;

/**
 * Description of InvoiceController
 *
 * @author Mahabub
 */
class ExchangeController
{

    private static $table = 'exchange';

    public function __construct()
    {
        add_shortcode('exchanger', [Views::class, 'exchangeWindow']);
        add_action('wp_ajax_exchange_request', [$this, 'exchange_request']);
        add_action('wp_ajax_nopriv_exchange_request',  [$this, 'exchange_request']);
    }


    function exchange_request()
    {
        var_dump($_POST);
        wp_die();
    }

    public static function count()
    {
        global $wpdb;
        $rowcount = $wpdb->get_var("SELECT count(*) FROM $wpdb->prefix" . self::$table . " WHERE 1 order by id desc");
        return $rowcount;
    }

    public static function all()
    {
        global $wpdb;
        $sql = "SELECT * FROM $wpdb->prefix" . self::$table . " WHERE 1 order by id desc";
        $customers = $wpdb->get_results($sql);
        return $customers;
    }

    public static function get($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM $wpdb->prefix" . self::$table . " WHERE id=$id";
        $customers = $wpdb->get_results($sql);
        if ($customers) {
            return $customers[0];
        }
        return false;
    }



    public static function exchangeList()
    {
?>
        <div class="wrap mt0">
            <h1 class="wp-heading-inline bt1">Exchange</h1>
            <hr>
            <?php
            $dataList = new DataList(self::$table, 'exchange', 15);
            $dataList->setColumn([
                'name' => 'Name',
                'mobile_number' => 'Mobile Number',
                'type' => 'Type',
                'status' => 'Status',
                'email_address' => 'Email',
            ]);
            $dataList->actions['edit']['atts']['data-w'] = 500;
            // $dataList->conditions = [
            //     ['id', ">", 0]
            // ];
            $dataList->getData();
            echo $dataList->get();
            echo $dataList->paginate();
            ?>
        </div>
        <script>
            new Popup(jQuery);
        </script>
<?php
    }
}
