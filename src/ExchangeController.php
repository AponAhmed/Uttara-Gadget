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
    private $imageDir;
    private $maxSize = 5; //MB For Image Upload
    private $maxDimSize = '1000'; // Pixel Size x or Y for Imnage Upload

    public function __construct()
    {
        $this->imageDir = ABSPATH . '/wp-content/exchange/';
        if (!is_dir($this->imageDir)) {
            mkdir($this->imageDir, 0777, true);
            chmod($this->imageDir, 0777);
        }
        add_shortcode('exchanger', [Views::class, 'exchangeWindow']);
        add_action('wp_ajax_exchange_request', [$this, 'exchange_request']);
        add_action('wp_ajax_nopriv_exchange_request',  [$this, 'exchange_request']);
        add_action('wp_ajax_upload_divices_image',  [$this, 'upload_divices_image']);
        add_action('wp_ajax_nopriv_upload_divices_image',  [$this, 'upload_divices_image']);

        add_action('wp_ajax_view-exchange-proposer',  [Views::class, 'view_exchange_proposer']);
    }


    function upload_divices_image()
    {
        if (isset($_FILES["exchange-image"])) {
            // Get Image Dimension

            $fileinfo = @getimagesize($_FILES["exchange-image"]["tmp_name"]);
            $width = $fileinfo[0];
            $height = $fileinfo[1];

            $allowed_image_extension = array(
                "png",
                "jpg",
                "jpeg"
            );

            $file = $_FILES["exchange-image"];
            // Get image file extension
            $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);

            // Validate file input to check if is not empty
            if (!file_exists($file["tmp_name"])) {
                $response = array(
                    "type" => "error",
                    "message" => "Choose image file to upload."
                );
            }    // Validate file input to check if is with valid extension
            else if (!in_array($file_extension, $allowed_image_extension)) {
                $response = array(
                    "type" => "error",
                    "message" => "Upload valid images. Only PNG and JPEG are allowed."
                );
            }    // Validate image file size
            else if (($file["size"] >  ($this->maxSize * 1000000))) {
                $response = array(
                    "type" => "error",
                    "message" => "Image size exceeds {$this->maxSize}MB"
                );
            } else {
                $pathinfo = pathinfo($file['name']);
                $fileName = $pathinfo['filename'] . "-" . time() . "." . $file_extension;
                $target = $this->imageDir . $fileName;
                if (move_uploaded_file($_FILES["exchange-image"]["tmp_name"], $target)) {
                    $response = array(
                        "type" => "success",
                        "message" => "Image uploaded successfully.",
                        "src" => content_url() . "/exchange/" . $fileName,
                        "name" => $fileName
                    );
                } else {
                    $response = array(
                        "type" => "error",
                        "message" => "Problem in uploading image files."
                    );
                }
            }
        }
        echo json_encode($response);
        wp_die();
    }

    function exchange_request()
    {
        global $wpdb;

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $data = [];
        $info = ['error' => false, 'message' => ''];
        parse_str($_POST['data'], $data);
        $exchData = $data['exch'];
        if (isset($data['exch-images'])) {
            $exchData['midia_referance'] = json_encode($data['exch-images']);
        }

        if ($wpdb->insert($wpdb->prefix . self::$table, $exchData)) {
            $info['error'] = false;
            $info['message'] = 'Thank you so much, Our consern Team will contact you soon';
        } else {
            $info['error'] = false;
            $info['message'] = 'An error ocured during request, Please try again later';
        }
        echo json_encode($info);
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
            <h1 class="wp-heading-inline bt1">Exchange or Sales Request</h1>
            <hr>
            <?php
            $dataList = new DataList(self::$table, 'exchange', 15);
            $dataList->setColumn([
                'name' => 'Name',
                'mobile_number' => 'Mobile Number',
                'type' => 'Type',
                'product_type' => 'Product',
                'model' => 'Model',
                // 'status' => 'Status'
            ]);
            $dataList->actions['edit']['atts']['data-w'] = 500;
            unset($dataList->actions['edit']);
            $dataList->addAction('view', [
                'label' => 'View Details',
                'atts' => [
                    'class' => 'popup',
                    'data-id' => "%id",
                    'data-w' => 500,
                    'href' => "view-exchange-proposer",
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
