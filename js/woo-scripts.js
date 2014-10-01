/**
 * Created by shibly on 11/23/13.
 */


jQuery(document).ready(function () {

    // alert(jQuery(window).height());

    // alert("loading script from the plugin page");
    jQuery(".woocommerce-breadcrumb").text(" ");
    jQuery("#tabs a").click(function (event) {
        if (jQuery(window).height() <= 767) {
            jQuery.scrollTo('.product_content', 1000);
        }
        event.preventDefault();
        var my_id = jQuery(this).attr("id");
        jQuery("#tabs a").removeClass("active");
        jQuery(this).addClass("active");


        jQuery("#tabs_container .each_cat").fadeOut(0);
        jQuery("#tabs_container .each_cat").removeClass("active");

        jQuery("#product-" + my_id).fadeIn();
        jQuery("#product-" + my_id).addClass("active");

    });
});
