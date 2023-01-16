<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of Views
 *
 * @author Mahabub
 */
class Views
{

    //put your code here
    public static function adminMainView()
    {
        global $wpdb;

?>
        <div class="utatra-gadget">
            <div class="overview">
                <!-- <div class="box">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Sync</title>
                            <path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44" />
                        </svg>
                    </div>
                    <div class="box-title">
                        <label class="counter">18</label>
                        <label><a href='edit.php?post_type=exchange'>Exchange</a></label>
                    </div>
                </div> -->
                <div class="box" style="background:#ff76b7">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Cube</title>
                            <path d="M448 341.37V170.61A32 32 0 00432.11 143l-152-88.46a47.94 47.94 0 00-48.24 0L79.89 143A32 32 0 0064 170.61v170.76A32 32 0 0079.89 369l152 88.46a48 48 0 0048.24 0l152-88.46A32 32 0 00448 341.37z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M69 153.99l187 110 187-110M256 463.99v-200" />
                        </svg>
                    </div>
                    <div class="box-title">
                        <label class="counter">
                            <?php
                            $rowcount = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_status='publish' and post_type='product'");
                            echo $rowcount;
                            ?>
                        </label>
                        <label><a href='edit.php?post_type=product'>Products</a></label>
                    </div>
                    <a href="post-new.php?post_type=product" class="box-new-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Add New Product</title>
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path>
                        </svg>
                    </a>
                </div>

                <div class="box" style="background:#7698ff">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>People</title>
                            <path d="M402 168c-2.93 40.67-33.1 72-66 72s-63.12-31.32-66-72c-3-42.31 26.37-72 66-72s69 30.46 66 72z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                            <path d="M336 304c-65.17 0-127.84 32.37-143.54 95.41-2.08 8.34 3.15 16.59 11.72 16.59h263.65c8.57 0 13.77-8.25 11.72-16.59C463.85 335.36 401.18 304 336 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                            <path d="M200 185.94c-2.34 32.48-26.72 58.06-53 58.06s-50.7-25.57-53-58.06C91.61 152.15 115.34 128 147 128s55.39 24.77 53 57.94z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                            <path d="M206 306c-18.05-8.27-37.93-11.45-59-11.45-52 0-102.1 25.85-114.65 76.2-1.65 6.66 2.53 13.25 9.37 13.25H154" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" />
                        </svg>
                    </div>
                    <div class="box-title">
                        <label class="counter"><?php echo CustomerController::count() ?></label>
                        <label><a href='admin.php?page=customer'>Customers</a></label>
                    </div>
                    <a data-w="500" href="new-customer" class="popup box-new-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Add new Customer</title>
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path>
                        </svg>
                    </a>
                </div>

                <div class="box" style="background:#0bb756">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Receipt</title>
                            <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" d="M160 336V48l32 16 32-16 31.94 16 32.37-16L320 64l31.79-16 31.93 16L416 48l32.01 16L480 48v224" />
                            <path d="M480 272v112a80 80 0 01-80 80h0a80 80 0 01-80-80v-48H48a15.86 15.86 0 00-16 16c0 64 6.74 112 80 112h288" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M224 144h192M288 224h128" />
                        </svg>
                    </div>
                    <div class="box-title">
                        <label class="counter"><?php echo InvoiceController::count() ?></label>
                        <label><a href='admin.php?page=invoice'>Sales Invoice</a></label>
                    </div>
                    <a data-w="900" href="new-invoice" class="popup box-new-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>Add new Invoice</title>
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path>
                        </svg>
                    </a>

                </div>
            </div>
            <div class="uttg-exchange-zone">
                <?php ExchangeController::exchangeList() ?>
            </div>
        </div>
        <script>
            new Popup(jQuery);
        </script>
    <?php
    }


    public static function settingsView()
    {
        $options = AdminController::getOption();
    ?>
        <div class="wrap">
            <h2>Settings</h2>
            <hr>
            <form id="settingsFormUttg">

                <div class="tab-wrap">
                    <nav class="nav-tab-wrapper">
                        <a href="#basicOpt" class="nav-tab nav-tab-active">Basic</a>
                        <a href="#invoiceOpt" class="nav-tab">Invoice</a>
                        <a href="#exchangeOpt" class="nav-tab">Exchange</a>
                    </nav>
                    <div class="tab-content">
                        <div class="tab-pane" id="basicOpt">
                            <div class="uttg-form-group">
                                <label>Store Name</label>
                                <input type="text" style="max-width: 300px;" class="uttg-form-control" value="<?php echo $options['name'] ?>" name="uttg-options[name]">
                            </div>
                            <div class="uttg-form-group">
                                <label>Store Address</label>
                                <textarea id="storeAddress" style="max-width: 300px;" class="uttg-form-control" name="uttg-options[address]"><?php echo $options['address'] ?></textarea>
                            </div>
                        </div><!-- Tab End of Basic -->
                        <div class="tab-pane" id="invoiceOpt">
                            <div class="uttg-form-group">
                                <label>Currency</label>
                                <input type="text" style="max-width: 100px;" class="uttg-form-control" value="<?php echo $options['currency'] ?>" name="uttg-options[currency]">
                            </div>
                            <div class="uttg-form-group">
                                <label>Invoice Number Prefix</label>
                                <input type="text" style="max-width: 200px;" class="uttg-form-control" value="<?php echo $options['prefix'] ?>" name="uttg-options[prefix]">
                            </div>
                            <div class="uttg-form-group">
                                <label>Invoice Bottom Notes</label>
                                <textarea id="invoiceBottomNotes" rows="6" style="max-width: 700px;" class="uttg-form-control" name="uttg-options[invoice_notes]"><?php echo $options['invoice_notes'] ?></textarea>
                            </div>
                        </div><!-- Tab End of Invoice -->
                        <div class="tab-pane" id="exchangeOpt">

                        </div><!-- Tab End of Exchage -->
                    </div>
                </div>
                <hr>
                <button type="submit" class="button action updataBtn">Update</button>
            </form>
        </div>
        <script>
            jQuery("#settingsFormUttg").on("submit", function(e) {
                jQuery(".updataBtn").html('Updating...');
                e.preventDefault();
                var data = {
                    action: 'option_save',
                    fData: jQuery("#settingsFormUttg").serialize()
                };
                jQuery.post(ajaxurl, data, function(response) {
                    if (response == 1) {
                        ntf('Settings Updated successfully.');
                        jQuery(".updataBtn").html('Updated');
                    }
                });
                //jQuery("#secMailControl")
            });
        </script>
    <?php

    }


    static function  exchangeWindow()
    {
        ob_start();
    ?>
        <form id="exchangeForm">
            <div class="exchange-wrapper">
                <div class="exchange-title">Exchange or Sale</div>
                <div class="exchange-input-area">
                    <div class="flex-row">
                        <div class="exc-input">
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="Your Name" name="exch[name]" id="name" value="" required="">
                                <label for="name" class="form__label">Your Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex-row">
                        <div class="exc-input">
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="Your Name" name="exch[mobile_number]" id="excMobile" required="">
                                <label for="excMobile" class="form__label">Mobile Number</label>
                            </div>
                        </div>
                        <div class="exc-input">
                            <div class="form__group field">
                                <input type="email" class="form__field" placeholder="Your Name" name="exch[email_address]" id="email_address">
                                <label for="email_address" class="form__label">Email Address (Optional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex-row mt-10 mb-10">
                        <div class="exc-input">
                            <label><input name="exch[type]" value="Exchange" type='radio'>&nbsp;Exchange</label>&nbsp;&nbsp;&nbsp;
                            <label><input name="exch[type]" value="Sale" type='radio'>&nbsp;Sale</label>
                            <select class="type-of-select" name="exch[product_type]">
                                <option>Smart-Phone</option>
                                <option>Tab</option>
                                <option>Laptop</option>
                                <option>Accessories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-row mt-10 mb-10">
                        <div class="exc-input">
                            <textarea class="exch-details" name="exch['details_referance]" placeholder="If you want to share something (optional)"></textarea>
                        </div>
                    </div>
                    <div class="flex-row mt-10 mb-10">
                        <div class="exc-input">
                            <div class="exc-media-reference">
                                <input accept="image/png, image/jpeg, image/jpg, image/gif" type="file" style="display: none;" id="uploadMedia">
                                <div class="uploadedMedia">
                                    <!-- Uploaded Image will append here -->
                                    <label id="uploadNew" for="uploadMedia" class="mediaUploadPlaceholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                            <title>Upload Image of Your Divice</title>
                                            <path d="M432 112V96a48.14 48.14 0 00-48-48H64a48.14 48.14 0 00-48 48v256a48.14 48.14 0 0048 48h16" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                            <rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                            <ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                            <path d="M342.15 372.17L255 285.78a30.93 30.93 0 00-42.18-1.21L96 387.64M265.23 464l118.59-117.73a31 31 0 0141.46-1.87L496 402.91" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary" id="exchangeSubmit">Submit</button>
                </div>
            </div>
        </form>
<?php
        return ob_get_clean();
    }
}
