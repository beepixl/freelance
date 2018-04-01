<?php

// Theme Stylesheets using Enqueue
function babysitter_styles()
{
	$responsive = of_get_option('responsive_design', 'yes');

	// Normalize default styles
	wp_register_style('normalize', get_template_directory_uri() . '/css/normalize.css', array(), '2.0.1', 'all');
	wp_enqueue_style('normalize');

	// Skeleton grid system
	wp_register_style('skeleton', get_template_directory_uri() . '/css/skeleton.css', array(), '1.2', 'all');
	wp_enqueue_style('skeleton');

	if($responsive != 'no') {
		// Skeleton Responsive Part
		wp_register_style('skeleton_media', get_template_directory_uri() . '/css/skeleton_media.css', array(), '1.2', 'all');
		wp_enqueue_style('skeleton_media');
	}

	// FontAwesome (icon fonts)
	wp_register_style('fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '3.2.1', 'all');
	wp_enqueue_style('fontawesome');

	//Base Template Styles
	wp_register_style('base', get_template_directory_uri() . '/css/base.css', array(), '1.0', 'all');
	wp_enqueue_style('base');

	// Template Styles
	wp_register_style('babysitter_style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all');
	wp_enqueue_style('babysitter_style');

	// Superfish Menu
	wp_register_style('superfish', get_template_directory_uri() . '/css/superfish.css', array(), '1.0', 'all');
	wp_enqueue_style('superfish');

	// Flexslider
	wp_register_style('flexslider', get_template_directory_uri() . '/css/flexslider.css', array(), '2.1', 'all');
	wp_enqueue_style('flexslider');

	if($responsive != 'no') {
		// Responsive Layout and Media Queries 
		wp_register_style('layout', get_template_directory_uri() . '/css/responsive.css', array(), '1.0', 'all');
		wp_enqueue_style('layout');
	}
	
}
// Loading Admin Styles
function babysitter_admin_styles() {
	wp_register_style('custom-admin-styles', get_template_directory_uri() . '/css/custom-admin-styles.css', array(), '1.0', 'all');
	wp_enqueue_style('custom-admin-styles');
   
}
add_action('admin_enqueue_scripts','babysitter_admin_styles',10,1);
?>