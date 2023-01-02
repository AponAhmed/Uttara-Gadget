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
    /**
     * @var DataList
     */
    private $dataList;

    /**
     * InvoiceController constructor.
     *
     * @param DataList $dataList
     */
    public function __construct(DataList $dataList)
    {
    }

    public static function exchangeList()
    {
?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Exchange List</h1>
            <hr>
            <?php
            $dataList = new DataList('exchanges');
            $dataList->setColumn([
                'id' => "ID",
                'invoice_number' => 'Invoice #',
                'Mobile' => 'Customer Name'
            ]);
            echo $dataList->get();
            ?>
        </div>
<?php
    }
}
