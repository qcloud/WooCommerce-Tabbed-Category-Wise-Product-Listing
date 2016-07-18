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

    add_action('init', 'wtcpl_plugin_scripts');
    add_action('init', 'wtcpl_scroll_to_scripts');


    function wtcpl_plugin_scripts()
    {
        wp_enqueue_script('wtcpl-product-cat-js', plugins_url('/js/wtcpl-scripts.js', __FILE__), array('jquery'));

    }

    function wtcpl_scroll_to_scripts()
    {

        wp_enqueue_script('wtcpl-scroll-to-js', plugins_url('/js/jquery.scrollTo-1.4.3.1-min.js', __FILE__), array('jquery'));
    }


    /**
     * Loading the plugin specific stylesheet files.
     */

    function wtcpl_plugin_styles()
    {
        wp_register_style('wtcpl_plugin_style', plugin_dir_url(__FILE__) . 'css/wtcpl-styles.css');
        wp_enqueue_style('wtcpl_plugin_style');

    }


    function wtcpl_admin_actions()
    {
        add_options_page("Help", "Tabbed Category", 1, "Help", "wtcpl_help");
    }


    function wtcpl_help()
    {
        ?>
        <div class='wrap'>

            <div class="updated notice">
                <p>Use the shortcode [wtcpl-product-cat] inside any WordPress post or page to show category wise
                    WooCommerce
                    product listing in tabbed format.</p>
            </div>
            <div class="notice error">
                <p>WooCommerce must be installed to use this plugin.</p>
            </div>
        </div>


        <?
    }


    /**
     * The wtcpl_load_products() body
     */

    function wtcpl_load_products()
    {
        ?>

        <div class="wtcpl_container">


            <div id="nav-holder">
                <div class="wtcpl_category_nav" id="wtcpl_tabs">
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
            <div class="product_content" id="wtcpl_tabs_container">


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
        <?php
    }

    /**
     * Hooking to WordPress when it initialize
     */

    add_action('admin_menu', 'wtcpl_admin_actions');
    add_action('wp_enqueue_scripts', 'wtcpl_plugin_styles');

    /**
     * Register the shortcode
     */


    add_shortcode('wtcpl-product-cat', 'wtcpl_load_products');
} else {
    add_action('admin_notices', 'wtcpl_notice');

}


function wtcpl_notice()
{
    ?>

    <div class="error notice">
        <p>
            <strong><?php _e('Please install WooCommerce first, it is required for this plugin to work properly.', 'wtcpl_textdomain'); ?></strong>
        </p>
    </div>

    <?php
}


