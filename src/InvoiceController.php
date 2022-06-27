<?php

namespace Aponahmed\Uttaragedget\src;

use Aponahmed\Uttaragedget\src\DataList;

/**
 * Description of InvoiceController
 *
 * @author Mahabub
 */
class InvoiceController {

    //put your code here

    public static function invoiceList() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Invoice List</h1><hr>
            <?php
            $dataList = new DataList('invoice');
            $dataList->setColumn([
                'id' => "ID",
                'invoice_number' => 'Invoice #',
                'customer' => 'Customer Name'
            ]);
            echo $dataList->get();
            ?> 
        </div>
        <?php
    }

}
