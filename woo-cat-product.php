<?php

/*
Plugin Name: WooCommerce Category Product
Plugin URI:
Description: A WooCommerce Plugin for dynamically load product under a selected category.
Author: QuantumCloud
Version: 1.0
Author URI:
*/


/**
 * Check first if WooCommerce is activated or not
 */

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Put your plugin code here


    /**
     * Loading the plugin specific javascript files.
     */

    add_action('init', 'woo_plugin_scripts');
    add_action('init', 'woo_scroll_to_scripts');
    function woo_plugin_scripts()
    {
        wp_enqueue_script('woo-product-cat-js', plugins_url('/js/woo-scripts.js', __FILE__), array('jquery'));

    }

    function woo_scroll_to_scripts()
    {
        wp_enqueue_script('woo-scroll-to-js', plugins_url('/js/jquery.scrollTo-1.4.3.1-min.js', __FILE__), array('jquery'));
    }


    /**
     * Loading the plugin specific stylesheet files.
     */

    function woo_plugin_styles()
    {
        wp_register_style('woo_plugin_style', plugin_dir_url(__FILE__) . 'css/woo-styles.css');
        wp_enqueue_style('woo_plugin_style');
    }


    function woo_admin_actions()
    {
        add_options_page("Help", "WooCommerce Category Product", 1, "Help", "woo_help");
    }


    function woo_help()
    {
        ?>
        <div class='wrap'>
            <?php get_screen_icon(); ?>
            <h3>Use the shortcode [product-cat] inside any WordPress post or page to view category wise WooCommerce
                product listing. </h3>
            <h4>WooCommerce must be installed to use this plugin.</h4>
        </div>


    <?
    }


    /**
     * The load_cat_product() body
     */

    function load_cat_product()
    {
        ?>

        <div class="product_container">


            <div id="nav-holder">
                <div class="woo_category_nav" id="tabs">
                    <?php
                    // echo do_shortcode('[product_categories]');
                    $args = array(
                        'number' => $number,
                        'orderby' => $orderby,
                        'order' => $order,
                        'hide_empty' => $hide_empty,
                        'include' => $ids
                    );

                    $product_categories = get_terms('product_cat', $args);
                    // echo "<pre>";
                    //  var_dump($product_categories);
                    // echo "<pre>";
                    //  die();
                    ?>
                    <ul>
                        <?php
                        $i = 0;
                        foreach ($product_categories as $cat) {
                            ?>
                            <li>


                                <a id="<?php echo $cat->slug; ?>"
                                   class="product-<?php echo $cat->slug; ?><?php if ($i == 0) {
                                       echo " active";
                                   } ?>"
                                   data-name="<?php echo $cat->name; ?>"
                                   href="#"><?php echo $cat->name; ?></a>
                            </li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ul>
                    <!--   <div class="clear"></div>-->
                </div>
            </div>
            <div class="product_content" id="tabs_container">
                <?php
                $i = 0;
                foreach ($product_categories as $cat) {
                    ?>
                    <div class="each_cat<?php if ($i == 0) {
                        echo " active";
                    } ?>" id="product-<?php echo $cat->slug; ?>">
                        <?php
                        echo do_shortcode('[product_category category="' . $cat->name . '" per_page="12" columns="4" orderby="date" order="desc"]');
                        ?></div>
                    <?php $i++;
                } ?>
            </div>
        </div>
    <?php
    }

    /**
     * Hooking to WordPress when it initialize
     */

    add_action('admin_menu', 'woo_admin_actions');
    add_action('wp_enqueue_scripts', 'woo_plugin_styles');

    /**
     * Register the shortcode
     */


    add_shortcode('product-cat', 'load_cat_product');
} else {
    echo "<h1 style='color:red;'>WooCommerce must be activated before activate this plugin. Otherwise this plugin will not work.</h1>";
}