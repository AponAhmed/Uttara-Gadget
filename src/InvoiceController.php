<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\DataList;

/**
 * Description of InvoiceController
 *
 * @author Mahabub
 */
class InvoiceController
{
    public function __construct()
    {
        add_action('wp_ajax_new-invoice', [$this, 'create_new_invoice']);
    }


    function create_new_invoice()
    {
        echo "sadfsdf";
        wp_die();
    }

    public static function invoiceList()
    {
?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Invoice List <a data-w="900" href="new-invoice" class="button button-primary popup">New</a></h1>
            <hr>
            <?php
            $dataList = new DataList('sales');
            $dataList->setColumn([
                'id' => "Invoice#",
                'customer_id' => 'Customer',
                'sales_value' => 'Value'
            ]);
            $dataList->getData();
            echo $dataList->get();
            ?>
        </div>
        <script>
            new Popup(jQuery);
        </script>

<?php
    }
}
