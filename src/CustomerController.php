<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of CustomerController
 *
 * @author Mahabub
 */
class CustomerController {

    //put your code here
    public static function customerList() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Manage Customer</h1><hr>
            <?php
            $dataList = new DataList('customer');
            $dataList->setColumn([
                'id' => "ID",
                'name' => 'Name',
                'mobile' => 'Mobile Number'
            ]);
            echo $dataList->get();
            ?> 
        </div>
        <?php
    }

}
