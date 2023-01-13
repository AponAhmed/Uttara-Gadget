<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\DataList;
use Aponahmed\Uttaragedget\src\CustomerController;

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
        add_action('wp_ajax_product_search', [$this, 'product_search']);
        add_action('wp_ajax_new-invoice_save', [$this, 'new_invoice_save']);
    }

    function new_invoice_save()
    {
        global $wpdb;
        $formData = [];
        parse_str($_POST['data'], $formData);
        echo "<pre>";
        var_dump($formData);

        $data = $formData['data'];

        wp_die();
    }

    function product_search()
    {
        global $wpdb;
        $wh = "and post_type='product' ";
        if (isset($_POST['qs']) && !empty(['qs'])) {
            $qs = trim($_POST['qs']);
            $wh .= "and (ID like '%$qs%' or post_title like '%$qs%')";
        }
        //$wh="";
        $sql = "SELECT ID,post_title FROM $wpdb->posts  WHERE post_status='publish' $wh order by post_title asc limit 10";
        $results = $wpdb->get_results($sql);

        $data = [];
        foreach ($results as $result) {
            $product = \wc_get_product($result->ID);
            $images = wp_get_attachment_image_src(get_post_thumbnail_id($result->ID), 'thumbnail');
            //$product->get_regular_price();
            //$product->get_sale_price();
            $price = $product->get_price();

            $data[] = [
                'ID' => $result->ID,
                'name' => $result->post_title,
                'image' => count($images) > 0 ? $images[0] : false,
                'price' => $price
            ];
        }
        echo json_encode($data);
        wp_die();
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
        $data = $this->getInvoiceData($invoice_id);
        $itemsData = $this->getInvoiceItems($invoice_id);
?>
        <h2 class="pop-title"><?php echo isset($data['id']) ?  __('Update Invoice') :  __('Add New Invoice'); ?></h2>
        <form class="ajx">
            <div class="invoice-wrapper">
                <!-- <div class="row">
                <div class="col-md-6">
                    <div class="invoice-logo">Logo</div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-address">Address</div>
                </div>
            </div> -->
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="customer-wrapper">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="customer-select-container">
                                        <strong>Invoice to</strong>
                                        <select name="data[customer_id]" class="uttg-custom-select custom-select">
                                            <option value="">Select Curotmer</option>
                                            <?php
                                            foreach (CustomerController::allCustomers() as $customer) {
                                                echo '<option value="' . $customer->id . '">' . $customer->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button type="button" class="button button-default newCustomer-btn" onclick="jQuery('#newCustomer').slideToggle('fast')"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                                <title>Add</title>
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112" />
                                            </svg></button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <strong class="invoiceNumber">Invoice # <spam>12548</spam></strong>
                                </div>
                            </div>


                            <div class="row newCustomerArea">
                                <div class="col w7">
                                    <div class="newCustomer dnone" id="newCustomer">
                                        <div class='row'>
                                            <div class="col w6">
                                                <div class="uttg-form-group">
                                                    <input type="text" name="customer[name]" placeholder="Customer Name" class="uttg-form-control">
                                                </div>
                                                <div class="uttg-form-group">
                                                    <input type="text" name="customer[mobile]" placeholder="Mobile Number" class="uttg-form-control">
                                                </div>
                                                <div class="uttg-form-group" style="margin:0;">
                                                    <input type="email" name="customer[email]" placeholder="Email Address" class="uttg-form-control">
                                                </div>
                                            </div>
                                            <div class="col w6">
                                                <div class="uttg-form-group" style="height: 100%;">
                                                    <textarea style="height: 100%;" name="customer[address]" placeholder="Customer Address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w5">
                                    <div class="productSearch">
                                        <div class="searchInnerWrap">
                                            <input type="text" id="ProductSearch" placeholder="Search Here" class="uttg-form-controll">
                                            <div class="ItemsResult">
                                                <ul id="ResultItems"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="items-wrapper">
                            <table class="table table-invoice-item">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr>
                                        <td colspan="2" style="text-align: right;">Total</td>
                                        <td><strong class="totalQty">0</strong></td>
                                        <td>
                                            <strong class="totalvalue">0</strong>
                                            <input type="hidden" name="data[total_value]" class="totalvalueIn" readonly value="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="button button-primary">Save</button>
            </div>
        </form>

        <script>
            //ProductSearch            
            jQuery("#ProductSearch").on('keyup', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    jQuery("#ResultItems").html("Searching...");
                    jQuery(".ItemsResult").show();

                    let qs = jQuery("#ProductSearch").val();
                    jQuery.post(ajax_object.ajax_url, {
                        action: 'product_search',
                        qs: qs
                    }, (response) => {
                        jQuery("#ResultItems").html("");
                        response = JSON.parse(response);
                        response.forEach(item => {
                            let htm = `<li onclick="addToList(this)" class='rs-item' data-id="${item.ID}" data-name="${item.name}" data-price="${item.price}">
                                            <div class="rs-item-image"><img alt="${item.name}" src="${item.image}"></div>
                                            <div class="rs-item-info">
                                                <div class='title-area'><strong class="rs-title">${item.name}</strong></div>
                                                <strong class="rs-price">Price: ${item.price}</strong>
                                            </div>
                                        </li>`;
                            jQuery("#ResultItems").append(htm);
                            //console.log(item);
                        });
                    });
                }, 500);
            });

            function addToList(_this) {
                jQuery(".ItemsResult").hide();
                jQuery("#ProductSearch").val("");
                let item = jQuery(_this);
                let data = _this.dataset;
                let rID = Date.now();
                let htm = `<tr class='invoiceItem'>
                                    <td>
                                        ${data.name} 
                                        <input type="hidden" name="item[${rID}][product_id]" value="${data.ID}">
                                        <input type="hidden" class='unit_price' name="item[${rID}][unit_price]" value="${data.price}">
                                    </td>
                                    <td>${Number(data.price).toFixed(2)}</td>
                                    <td><input type='number' onchange="calcQuantity(this)" style="max-width:100px;" class='quantityIn' name='item[${rID}][qualtity]' value='1'></td>
                                    <td><input type='text' value='${Number(data.price * 1 ).toFixed(2)}' class='subTotal' readonly></td>
                                </tr>`;
                jQuery("#tableBody").prepend(htm);
                updatedCalc();
            }

            function calcQuantity(_this) {
                let qin = jQuery(_this);
                let rw = qin.closest('.invoiceItem');
                rw.find('.subTotal').val(Number(rw.find('.unit_price').val()) * Number(rw.find('.quantityIn').val()))
                updatedCalc();
            }

            function updatedCalc() {
                let totalQty = 0;
                let totalVal = 0;
                jQuery(".invoiceItem").each(function() {
                    let rw = jQuery(this);
                    let subTotal = Number(rw.find('.subTotal').val());
                    let qty = Number(rw.find('.quantityIn').val());
                    totalVal = subTotal + totalVal;
                    totalQty = totalQty + qty;
                });
                jQuery('.totalQty').html(totalQty);
                jQuery('.totalvalue').html(totalVal.toFixed(2));
                jQuery('.totalvalueIn').val(totalVal.toFixed(2));


            }
        </script>
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
