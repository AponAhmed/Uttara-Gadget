<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of CustomerController
 *
 * @author Mahabub
 */
class CustomerController
{
    private static $table = 'customers';

    public function __construct()
    {
        add_action('wp_ajax_new-customer', [$this, 'create_new_customer']);
        add_action('wp_ajax_new-customer_save', [$this, 'create_new_customer_store']);
    }

    public static function count()
    {
        global $wpdb;
        $rowcount = $wpdb->get_var("SELECT count(*) FROM $wpdb->prefix" . self::$table . " WHERE 1 order by id desc");
        return $rowcount;
    }

    public static function allCustomers()
    {
        global $wpdb;
        $sql = "SELECT * FROM $wpdb->prefix" . self::$table . " WHERE 1 order by id desc";
        $customers = $wpdb->get_results($sql);
        return $customers;
    }

    public static function getCustomer($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM $wpdb->prefix" . self::$table . " WHERE id=$id";
        $customers = $wpdb->get_results($sql);
        if ($customers) {
            return $customers[0];
        }
        return false;
    }

    function create_new_customer_store()
    {
        global $wpdb;
        $formData = [];
        parse_str($_POST['data'], $formData);
        $data = $formData['data'];
        if (isset($formData['id']) && !empty($formData['id'])) {
            $id = $formData['id'];
            $resp = $wpdb->update($wpdb->prefix . self::$table, $data, ['id' => $id]);
            if ($resp) {
                echo json_encode(['error' => false, 'message' => 'Customer Updated successfully']);
            } else {
                $info = ['error' => true, 'message' => 'Customer could not be Updated,' . $wpdb->last_error];
                if (empty($wpdb->last_error)) {
                    $info['message'] = 'Nothing have been Changed';
                }
                echo json_encode($info);
            }
        } else {
            $resp = $wpdb->insert($wpdb->prefix . self::$table, $data);

            if ($resp) {
                echo json_encode(['error' => false, 'message' => 'Customer Inserted successfully']);
            } else {
                echo json_encode(['error' => true, 'message' => 'Customer could not be inserted successfully, ' . $wpdb->last_error]);
            }
        }
        wp_die();
    }

    function create_new_customer()
    {
        $id = false;
        if (isset($_POST['id'])) {
            $id = intval($_POST['id']);
        }
        $data = [];
        if ($id) {
            $data = $this->getData($id);
        }
        $this->form($data);
        wp_die();
    }

    function form($data = [])
    {
?>
        <br>
        <h2 class="pop-title"><?php echo isset($data['id']) ?  __('Update Customer') :  __('Add New Customer'); ?></h2>
        <form method="post" class="ajx">
            <?php
            if (isset($data['id']) && !empty($data['id'])) {
                echo '<input type="hidden" name="id" value="' . $data['id'] . '" />';
            }
            ?>
            <div class="uttg-form-group">
                <label><?php echo __('Name'); ?></label>
                <input type="text" placeholder="Customer Name" class="uttg-form-control" name="data[name]" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="uttg-form-group">
                        <label><?php echo __('Email'); ?></label>
                        <input type="text" placeholder="Email Address" class="uttg-form-control" name="data[email]" value="<?php echo isset($data['email']) ? $data['email'] : ""; ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="uttg-form-group">
                        <label><?php echo __('Mobile'); ?></label>
                        <input type="text" placeholder="Mobile" class="uttg-form-control" name="data[mobile]" value="<?php echo isset($data['mobile']) ? $data['mobile'] : ""; ?>" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="uttg-form-group">
                        <label><?php echo __('Address'); ?></label>
                        <textarea name="data[address]" class="uttg-form-control"><?php echo isset($data['address']) ? $data['address'] : ""; ?></textarea>
                    </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="button button-default">Save</button>
        </form>
    <?php
    }

    /**
     * Get Invoice primary Data
     * @param $id invoice id
     */
    function getData($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}" . self::$table . " where id =$id";
        $invoices = $wpdb->get_results($sql, ARRAY_A);
        if ($invoices && isset($invoices[0])) {
            return $invoices[0];
        } else {
            return false;
        }
    }

    //put your code here
    public static function customerList()
    {
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Manage Customer <a data-w="500" href="new-customer" class="button button-primary popup uttg-p-btn">New</a></h1>
            <hr>
            <?php
            $dataList = new DataList(self::$table, 'customer', 15);
            $dataList->setColumn([
                'name' => 'Name',
                'mobile' => 'Mobile',
                'email' => 'Email',
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
