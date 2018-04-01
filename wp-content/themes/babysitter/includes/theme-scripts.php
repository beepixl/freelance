<?php

// Load Custom Theme Scripts using Enqueue
function babysitter_scripts()
{
    if (!is_admin()) {

        $responsive = of_get_option('responsive_design', 'yes');

        // Modernizr with version Number at the end
        wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.custom.14583.js', array('jquery'), '2.6.2', true);
        wp_enqueue_script('modernizr');

         // Easing Plugin
        wp_register_script('easing', get_template_directory_uri() . '/js/jquery.easing.min.js', array('jquery'), '1.3', true);
        wp_enqueue_script('easing');

        if($responsive != 'no') {
            // Mobile Select Menu
            wp_register_script('mobilemenu', get_template_directory_uri() . '/js/jquery.mobilemenu.js', array('jquery'), '1.0', true);
            wp_enqueue_script('mobilemenu');
        }
        
        // Superfish Menu
        wp_register_script('superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array('jquery'), '1.7.2', true);
        wp_enqueue_script('superfish');

        // Flexslider
        wp_register_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'), '2.1', true);
        wp_enqueue_script('flexslider');

        // Flickr Feed
        wp_register_script('flickr', get_template_directory_uri() . '/js/jflickrfeed.js', array('jquery'), '1.0', true);
        wp_enqueue_script('flickr', true);

        // Elastislide
        wp_register_script('caroufredsel', get_template_directory_uri() . '/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'), '6.2.1', true);
        wp_enqueue_script('caroufredsel');

        // Fitvids
        wp_register_script('fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), '1.1', true);
        wp_enqueue_script('fitvids');

        // Script for inits
        wp_register_script('babysitterscripts', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);
        wp_enqueue_script('babysitterscripts'); 
    }
}

// Loading Conditional Scripts
function conditional_scripts() {

    // Conditional for Contact page
    if (is_page_template('template-contacts.php')) {
        // Google Map
        wp_register_script('gmap_api', '//maps.google.com/maps/api/js?sensor=true', array('jquery'), '1.0');
        wp_enqueue_script('gmap_api');
        wp_register_script('gmap', get_template_directory_uri() . '/js/jquery.ui.map.js', array('jquery'), '3.0');
        wp_enqueue_script('gmap');
    }

}

// Loading Admin Scripts
function babysitter_admin_js($hook) {
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_register_script('babysitter-admin', get_template_directory_uri() . '/js/jquery.custom.admin.js', 'jquery', '1.0');
        wp_enqueue_script('babysitter-admin');
    }
}
add_action('admin_enqueue_scripts','babysitter_admin_js',10,1);
?>