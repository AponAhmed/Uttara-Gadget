<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\DataList;
use Aponahmed\Uttaragedget\src\CustomerController;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Aponahmed\Uttaragedget\src\NumberFormater;
use Aponahmed\Uttaragedget\src\AdminController;

/**
 * Description of InvoiceController
 *
 * @author Mahabub
 */
class InvoiceController
{
    public static $table = 'sales';
    public function __construct()
    {
        add_action('wp_ajax_new-invoice', [$this, 'create_new_invoice']);
        add_action('wp_ajax_product_search', [$this, 'product_search']);
        add_action('wp_ajax_new-invoice_save', [$this, 'new_invoice_save']);
        if (isset($_GET['download']) && $_GET['download'] == 'invoice' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->download_invoice($id);
        }
    }

    function invoiceNumber($id)
    {
        $options = AdminController::getOption();

        return $options['prefix'] . $id;
    }

    public static function count()
    {
        global $wpdb;
        $rowcount = $wpdb->get_var("SELECT count(*) FROM $wpdb->prefix" . self::$table . " WHERE 1 order by id desc");
        return $rowcount;
    }

    function download_invoice($id)
    {
        $options = AdminController::getOption();
        $data = $this->getInvoiceData($id);
        $customer = CustomerController::getCustomer($data['customer_id']);
        $itemsData = $this->getInvoiceItems($id);

        $tableS = "<table border='0' style='width: 100%;border-collapse: collapse;'>";
        $trS = "<tr>";
        $tdS = "<td>"; //
        $tdE = "</td>";
        $trE = "</tr>";
        $tableE = "</table>";

        $headerImg = __UTTG_ASSETS . "invoice-logo.png";
        $paidImage = __UTTG_ASSETS . "paid-logo.png";
        $address = nl2br($options['address']); //

        $paid = "";
        $due = ($data['sales_value'] - $data['sales_value_discount'] - $data['sales_value_receive']); //sales_value_receive
        if ($due <= 0) {
            $paid = "  <div><img style='margin-left:10px;height:100px;position: absolute;top:250px;left:300px;z-index:999' src='$paidImage'/></div>";
        }

        $html = "<style>
        .tbl_itm{color:#333333;border-collapse: collapse; border-spacing: 0; margin-bottom:20px;border-color:#333333;}
        .tbl_itm tr th{font-size:12px;font-weight:300;padding:5px;background:#EBEBEB;}
        .tbl_itm tr td, .tbl_itm tr th {
            border: .5px solid #333;
            font-size:12px;
            padding-top:8px;
            padding-bottom:8px;
            }
        .tbl_itm  tr td.bnT{border-top:0px solid #fff;}
        .tbl_itm  tr td.bnL{border-left:0px solid #fff;}
        .tbl_itm  tr td.bnB{border-bottom:0px solid #fff;}
        .tbl_itm  tr td.bnR{border-right:0px solid #fff;}
        .tbl_itm  tr th {font-size:14px;}
        .tbl_itm  tr td{
    padding:5px 3px
        }
        .head{
            font-size:12px;
            color:#333;
            width:80px;
            padding:2px 0px;
        }
        </style>
        <page backtop=\"80mm\" backbottom=\"0mm\" backleft=\"0mm\" backright=\"0mm\">
		<page_header>
                <table style='width: 100%;border-collapse: collapse;'><tr>
                    <td width='350' align='left'>
                        <img style='margin-left:10px;height:50px'  src='$headerImg'>
                    </td>
                    <td width='350' align='left'>
                        <div style='font-size:13px;text-align:right;line-height:1.2'>
                            <strong style='margin:0;line-height:15px;font-weight:bold;font-size:18px'>$options[name]</strong>
                            <p style='line-height:18px;'>$address</p>
                        </div>
                    </td>
                </tr>
                <tr><td colspan='2'><div style='text-align:center;border-bottom:1px solid #eee;font-size:20px;color:#21356e;font-weight:bold;padding:10px'>Invoice</div></td></tr>
                <tr>
                    <td colspan='2'>
                        $tableS
                        $trS
                        <td width='580' align='left'>
                            <div style='padding:20px 0'>
                                <strong>Invoice To</strong><br><br>
                                <table style='border-collapse: collapse;'>
                                    <tr>
                                        <td class='head'>Name </td>
                                        <td>: $customer->name </td>
                                    </tr>
                                    <tr>
                                        <td class='head'>Mobile </td>
                                        <td>: $customer->mobile  </td>
                                    </tr>
                                    <tr>
                                        <td class='head'>Email </td>
                                        <td>: $customer->email  </td>
                                    </tr>
                                    <tr>
                                        <td class='head'>Address </td>
                                        <td>: $customer->address </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width='120' align='left'>
                            <div style='line-height:18px;padding:5px 10px;background:#eee;border:1px solid #ddd;text-align:center;border-radius:5px'>
                                invoice # " . $this->invoiceNumber($data['id']) . "
                            </div>
                        </td>
                        $trE
                        $tableE
                    </td>
                </tr>
                </table>
                $paid
		</page_header>";
        //Footer
        $siteUrl = site_url();
        $html .= "<page_footer>"
            . "<div style='text-align:center;border-top:1px solid #999;padding-top:5px;font-size:10px'>"
            . "<table><tr><td width='350' align='left'>Page - [[page_cu]] of [[page_nb]]</td><td width='350' align='right'>$siteUrl</td></tr></table>"
            . "</div>"
            . "</page_footer>";

        $html .= "<table class='tbl_itm' style='width:715px'>";
        $html .= "<tr>
                    <th style='width:64%;text-align:left'>Item</th>
                    <th style='width:12%;text-align:right'>Unit Price</th>
                    <th style='width:10%;text-align:center'>Qty</th>
                    <th style='width:14%;text-align:right'>Total</th>
                </tr>";

        $total = 0;
        $q = 0;
        foreach ($itemsData as $item) {
            $title = get_the_title($item['product_id']);
            $subTotal = $item['unit_price'] * $item['qualtity'];
            $total += $subTotal;
            $q += $item['qualtity'];
            $html .= "<tr>
                    <td style='text-align:left'>$title</td>
                    <td style='text-align:right'>" . number_format($item['unit_price'], 2) . "</td>
                    <td style='text-align:center'>$item[qualtity]</td>
                    <td style='text-align:right'>" . number_format($subTotal, 2) . "</td>
                </tr>";
        }
        $html .= "<tr>
                    <td class='bnL bnB' style='text-align:right' colspan='2'><strong>Total : </strong></td>
                    <td  style='text-align:center'><strong>$q</strong></td>
                    <td style='text-align:right'><strong>" . number_format($total, 2) . "</strong></td>
                </tr>";
        if ($data['sales_value_discount'] != 0) {
            $html .= "<tr>
                    <td class='bnL bnB bnR' style='text-align:right' colspan='3'>Discount ($options[currency]) : </td>
                    <td class='bnL bnR' style='text-align:right'>" . number_format($data['sales_value_discount'], 2) . "</td>
                </tr>";
            $html .= "<tr>
                    <td class='bnL bnB bnR' style='text-align:right' colspan='3'><strong>Total ($options[currency]) : </strong></td>
                    <td class='bnR' style='text-align:right'><strong>" . number_format($total - $data['sales_value_discount'], 2) . "</strong></td>
                </tr>";
        }

        $html .= "</table>";

        $nf = new NumberFormater();
        $inWord = $nf->convertNumber(number_format($total - $data['sales_value_discount'], 2));
        $html .= "<div style='text-align:center;font-size:12px;background:#eee;border:1px solid #ddd;padding:5px'>IN WORD : " . ucwords($inWord) . " $options[currency] Only.</div>";
        $html .= "<br><p style='font-size:10px;line-height:12px;color:#444;font-weighr:normal'>" . nl2br($options['invoice_notes']) . "</p>";
        $html .= "</page>"; //Page Close

        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 10));
            // $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($html);
            //$html2pdf->createIndex('Sommaire', 30, 12, false, true, 2, null, '10mm');
            $html2pdf->output("invoice-$data[id]" . '.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit;
    }

    function new_invoice_save()
    {
        global $wpdb;
        $formData = [];
        parse_str($_POST['data'], $formData);

        $data = $formData['data'];
        $info = ['error' => false, 'message' => ''];
        //if Not Select Customer or Customer Information Missing then Error

        //
        if ($data['sales_value'] == 0) {
            $info['error'] = true;
            $info['message'] = 'No sales information available, Select products first';
            echo json_encode($info);
            wp_die();
        }
        //if (!isset($data['customer_id']) || empty($data['customer_id'])) {
        if (
            isset($formData['customer']['name']) &&
            isset($formData['customer']['mobile']) &&
            !empty($formData['customer']['name']) &&
            !empty($formData['customer']['mobile'])
        ) {
            //insert new Customer
            // echo "---Customer New Information";
            if ($wpdb->insert($wpdb->prefix . 'customers', $formData['customer'])) {
                $data['customer_id'] = $wpdb->insert_id; //new Customer Id
            } else {
                //- update existing
                $info['error'] = true;
                $info['message'] = 'New Customer Insert Error, ' . $wpdb->last_error;
            }
        } else {
            if (!isset($data['customer_id']) || empty($data['customer_id'])) {
                $info['error'] = true;
                $info['message'] = 'Customer Information Missing';
            }
        }
        //}


        if ($info['error']) {
            echo json_encode($info);
            wp_die();
        }

        //Insert Invoice Data  or Update 
        if (isset($formData['id']) && !empty($formData['id'])) {
            //Update Invoice Data or Update
            if ($wpdb->update($wpdb->prefix . self::$table, $data, ['id' => $formData['id']])) {
                //Items updated
                if (isset($formData['items']) && count($formData['items']) > 0) {
                    $this->invoiceItems($formData['items'], $formData['id']);
                }
                $info['error'] = false;
                $info['message'] = 'Invoice Updated Successfully';
            } else {
                $info['error'] = true;
                $info['message'] = 'Invoice Updated failed, ' . $wpdb->last_error;
                if (empty($wpdb->last_error)) {
                    $info['message'] = 'Nothing have been Changed';
                }
            }
        } else {
            //Insert new Invoice
            if ($wpdb->insert($wpdb->prefix . self::$table, $data)) {
                $info['error'] = false;
                $info['message'] = 'New Invoice Created successfully';
                //Items updated
                if (isset($formData['items']) && count($formData['items']) > 0) {
                    $this->invoiceItems($formData['items'], $wpdb->insert_id);
                }
            } else {
                $info['error'] = true;
                $info['message'] = 'Invoice creation failed, ' . $wpdb->last_error;
            }
        }
        //Insert Invoice Items data 
        echo json_encode($info);
        wp_die();
    }

    function invoiceItems($items, $id = false)
    {
        global $wpdb;
        //Delete Existing Items data
        $wpdb->delete($wpdb->prefix . 'sales_items', ['sales_id' => $id]);
        //Insert New Items data
        foreach ($items as $item) {
            $item['sales_id'] = $id;
            $wpdb->insert($wpdb->prefix . 'sales_items', $item);
        }
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
        $sql = "SELECT * FROM {$wpdb->prefix}sales where id=$id";
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

    function printVal($data, $key, $default)
    {
        return isset($data[$key]) && !empty($data[$key]) ? $data[$key] : $default;
    }

    function invoiceView($invoice_id)
    {
        $data = $this->getInvoiceData($invoice_id);
        $itemsData = $this->getInvoiceItems($invoice_id);
?>
        <h2 class="pop-title"><?php echo isset($data['id']) ?  __('Update Invoice') :  __('Add New Invoice'); ?></h2>
        <form class="ajx">
            <?php
            if (isset($data['id']) && !empty($data['id'])) {
                echo '<input type="hidden" name="id" value="' . $data['id'] . '" />';
            }
            ?>
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
                                                $sel = isset($data['customer_id']) && $data['customer_id'] == $customer->id ? 'selected' : '';
                                                echo '<option ' . $sel . ' value="' . $customer->id . '">' . $customer->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button type="button" class="button button-default newCustomer-btn" onclick="jQuery('#newCustomer').slideToggle('fast')"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                                <title>Add</title>
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112" />
                                            </svg></button>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <strong class="invoiceNumber">Invoice # <spam>12548</spam></strong>
                                </div> -->
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
                                        <th>Item</th>
                                        <th style="width: 100px">Price</th>
                                        <th style="width: 80px">Quantity</th>
                                        <th style="width: 100px">Total</th>
                                        <th style="width: 15px"></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if (is_array($itemsData) && count($itemsData) > 0) {

                                        $q = 0;
                                        foreach ($itemsData as $item) {
                                            $n = $item['id'];
                                            $q += $item['qualtity'];
                                            $post_titler = get_the_title($item['product_id']);
                                    ?>
                                            <tr class='invoiceItem'>
                                                <td>
                                                    <strong><?php echo $post_titler ?></strong>
                                                    <input type="hidden" name="items[<?php echo $n ?>][product_id]" value="<?php echo $item['product_id'] ?>">
                                                    <input type="hidden" class='unit_price' name="items[<?php echo $n ?>][unit_price]" value="<?php echo $item['unit_price'] ?>">
                                                </td>
                                                <td><?php echo number_format($item['unit_price'], 2) ?></td>
                                                <td><input type='number' onchange="calcQuantity(this)" style="max-width:100px;" class='noborder quantityIn' name='items[<?php echo $n ?>][qualtity]' value='<?php echo $item['qualtity'] ?>'></td>
                                                <td><input type='text' value='<?php echo number_format($item['qualtity'] * $item['unit_price'], 2) ?>' class='subTotal noborder' readonly></td>
                                                <td><span class="removeinvItem" onclick="removeItem(this)">&times;</span></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">Total</td>
                                        <td><strong class="totalQty"><?php echo $q  ?></strong></td>
                                        <td>
                                            <strong class="totalvalue"><?php echo $this->printVal($data, 'sales_value', '0') ?></strong>
                                            <input type="hidden" name="data[sales_value]" class="totalvalueIn" readonly value="<?php echo $this->printVal($data, 'sales_value', '0') ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right;">Discount</td>
                                        <td>
                                            <input type="text" onkeyup="updatedCalc()" id="discount" name="data[sales_value_discount]" class="discount_amount noborder" value="<?php echo $this->printVal($data, 'sales_value_discount', '0') ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right;">Received Amount</td>
                                        <td>
                                            <input type="text" name="data[sales_value_receive]" class="received_amount noborder" value="<?php echo $this->printVal($data, 'sales_value_receive', '0') ?>">
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
                                        <strong>${data.name}</strong> 
                                        <input type="hidden" name="items[${rID}][product_id]" value="${data.id}">
                                        <input type="hidden" class='unit_price' name="items[${rID}][unit_price]" value="${data.price}">
                                    </td>
                                    <td>${Number(data.price).toFixed(2)}</td>
                                    <td><input type='number' onchange="calcQuantity(this)" style="max-width:100px;" class='noborder quantityIn' name='items[${rID}][qualtity]' value='1'></td>
                                    <td><input type='text' value='${Number(data.price * 1 ).toFixed(2)}' class='subTotal noborder' readonly></td>
                                    <td><span class="removeinvItem" onclick="removeItem(this)">&times;</span></td>
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

                let discountVal = Number(jQuery('#discount').val());
                jQuery(".received_amount").val((totalVal - discountVal).toFixed(2));

            }

            function removeItem(_this) {
                jQuery(_this).closest('.invoiceItem').remove();
                updatedCalc();
            }
        </script>
    <?php
    }


    public static function invoiceList()
    {
        global $wpdb;
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Invoice List <a data-w="900" href="new-invoice" class="button button-primary popup uttg-p-btn">New</a></h1>
            <hr>
            <?php
            $dataList = new DataList('sales', 'invoice', 15);
            $dataList->setColumn([
                'id' => "Invoice#",
                'name' => 'Customer',
                'mobile' => 'Mobile',
                'sales_value_receive' => 'Due(TK)',
                'sales_value' => 'Invoice Value(TK)'
            ]);
            $dataList->leftJoin = [
                ['customers', 'id', 'customer_id']
            ];
            $pfx = $wpdb->prefix;
            $dataList->selectedFields = "{$pfx}sales.*,{$pfx}customers.name,mobile";

            $dataList->filters = [
                'sales_value_receive' => function ($colVal, $row) {
                    $sValue = $row->sales_value - $row->sales_value_discount; //sales_value_receive
                    return number_format($sValue - $colVal, 2);
                },
                'sales_value' => function ($colVal, $row) {
                    $colVal = $colVal - $row->sales_value_discount;
                    return number_format($colVal, 2);
                }
            ];
            $dataList->actions['edit']['atts']['data-w'] = 900;
            $dataList->addAction('downloaded', [
                'label' => 'Downloaded',
                'atts' => [
                    'target' => '_blank',
                    'href' => "admin.php?page=invoice&download=invoice&id=%id",
                ]
            ]);
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
