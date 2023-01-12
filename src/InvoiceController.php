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
        $invoice_id = false;
        if (isset($_POST['id'])) {
            $invoice_id = intval($_POST['id']);
        }
        $this->invoiceView($invoice_id);
        wp_die();
    }

    /**
     * Get Invoice primary Data
     * @param $id invoice id
     */
    function getInvoiceData($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}sales where id =$id";
        $invoices = $wpdb->get_results($sql, ARRAY_A);
        if ($invoices && isset($invoices[0])) {
            return $invoices[0];
        } else {
            return false;
        }
    }

    /**
     * Get Invoice Items
     */
    function getInvoiceItems($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}sales_items where sales_id =$id";
        $items = $wpdb->get_results($sql, ARRAY_A);
        return $items;
    }

    function invoiceView($invoice_id)
    {
        $invoiceData = $this->getInvoiceData($invoice_id);
        $itemsData = $this->getInvoiceItems($invoice_id);
?>
        <div class="invoice-wrapper">
            <div class="row">
                <div class="col-md-6">
                    <div class="invoice-logo">Logo</div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-address">Address</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="invoice-title">Invoice</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customer-wrapper">
                        <strong>Invoice to</strong>
                        New Customer or Existing Customer
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="items-wrapper">Items Select </div>
                </div>
            </div>
        </div>
    <?php
    }


    public static function invoiceList()
    {
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Invoice List <a data-w="900" href="new-invoice" class="button button-primary popup uttg-p-btn">New</a></h1>
            <hr>
            <?php
            $dataList = new DataList('sales', 'invoice', 1);
            $dataList->setColumn([
                'id' => "Invoice#",
                'customer_id' => 'Customer',
                'sales_value' => 'Value'
            ]);
            $dataList->actions['edit']['atts']['data-w'] = 900;
            $dataList->addAction('downloaded', [
                'label' => 'Downloaded',
                'href' => "admin.php?page=invoice&download=invoice&id=%id",
            ]);
            $dataList->conditions = [
                ['id', ">", 0]
            ];
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
