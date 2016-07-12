<?php

/*
Plugin Name: Woo Tabbed Category Product Listing
Plugin URI: http://www.quantumcloud.com/blog/woocommerce-tabbed-category-wise-product-listing/
Description: A WooCommerce Plugin to dynamically load products from selected category in tabs.
Author: http://www.QuantumCloud.com/
Version: 0.9.1
Author URI:
*/


/**
 * Check first if WooCommerce is activated or not
 */

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Plugin Code Below


    /**
     * Loading the plugin specific javascript files.
     */

    add_action('init', 'woo_plugin_scripts');
    add_action('init', 'woo_scroll_to_scripts');

    wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');

    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    function woo_plugin_scripts()
    {
        wp_enqueue_script('woo-product-cat-js', plugins_url('/js/woo-scripts.js', __FILE__), array('jquery'));

    }

    function woo_scroll_to_scripts()
    {

        // wp_enqueue_script('woo-scroll-to-js', plugins_url('/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('woo-scroll-to-js', plugins_url('/js/jquery.scrollTo-1.4.3.1-min.js', __FILE__), array('jquery'));
    }


    /**
     * Loading the plugin specific stylesheet files.
     */

    function woo_plugin_styles()
    {
        wp_register_style('woo_plugin_style', plugin_dir_url(__FILE__) . 'css/woo-styles.css');
        //wp_register_style('woo_plugin_bootstrap', plugin_dir_url(__FILE__) . 'bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('woo_plugin_style');
        //wp_enqueue_style('woo_plugin_bootstrap');
    }


    function woo_admin_actions()
    {
        add_options_page("Help", "Woo Tabbed Category Product Listing", 1, "Help", "woo_help");
    }


    function woo_help()
    {
        ?>
        <div class='wrap'>
            <?php get_screen_icon(); ?>
            <h3>Use the shortcode [product-cat] inside any WordPress post or page to show category wise WooCommerce
                product listing in tabbed format. </h3>
            <h4>WooCommerce must be installed 1st to use this plugin.</h4>
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
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $i = 0;
                        foreach ($product_categories as $cat) {
                            ?>
                            <div class="each_cat<?php if ($i == 0) {
                                echo " active";
                            } ?>" id="product-<?php echo $cat->slug; ?>">
                                <?php
                                echo do_shortcode('[product_category category="' . $cat->name . '" per_page="12" columns="3" orderby="date" order="desc"]');
                                ?></div>
                            <?php $i++;
                        } ?>
                    </div>
                </div>
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
    add_action('admin_notices', 'woo_notice');

}


function woo_notice()
{
    ?>

    <div class="error notice">
        <p>
            <strong><?php _e('Please install WooCommerce first as it is required for Woo Tabbed Category Product Listing plugin to work properly.', 'my_plugin_textdomain'); ?></strong>
        </p>
    </div>

<?php
}


