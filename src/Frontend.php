<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of Frontend
 *
 * @author Apon
 */
class Frontend
{
    //put your code here

    function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'frontEndScript'));
    }

    function frontEndScript()
    {
        wp_register_style('uttg-css', __UTTG_ASSETS . 'public-style.css', false, '1.0.0');
        wp_enqueue_style('uttg-css');

        wp_enqueue_script('uttg-scripts', __UTTG_ASSETS . 'public-scripts.js', array('jquery'), '1.0');
        wp_localize_script('uttg-scripts', 'uttgajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}
