/**
 * Created by shibly on 11/23/13.
 */


jQuery(document).ready(function () {


    jQuery("#wtcpl_tabs a").click(function (event) {
        if (jQuery(window).height() <= 767) {
            jQuery.scrollTo('.product_content', 1000);
        }
        event.preventDefault();
        var my_id = jQuery(this).attr("id");
        jQuery("#wtcpl_tabs a").removeClass("active");
        jQuery(this).addClass("active");


        jQuery("#wtcpl_tabs_container .each_cat").fadeOut(0);
        jQuery("#wtcpl_tabs_container .each_cat").removeClass("active");

        jQuery("#product-" + my_id).fadeIn();
        jQuery("#product-" + my_id).addClass("active");

    });
});
