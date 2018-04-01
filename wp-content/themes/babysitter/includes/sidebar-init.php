<?php
// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{   
    // Define Sidebar
    register_sidebar(array(
        'name'          => __('Sidebar', 'babysitter'),
        'id'            => 'sidebar',
        'description'   => __( 'Sidebar located at the right side of blog page by default.', 'babysitter'),
        'before_widget' => '<div id="%1$s" class="widget widget__sidebar %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    // Define Footer Widget 1
    register_sidebar(array(
        'name' => __('Footer Widget 1', 'babysitter'),
        'id' => 'footer1',
        'description'   => __( 'First Footer Widget Area', 'babysitter'),
        'before_widget' => '<div id="%1$s" class="widget widget__footer %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    // Define Footer Widget 2
    register_sidebar(array(
        'name' => __('Footer Widget 2', 'babysitter'),
        'id' => 'footer2',
        'description'   => __( 'Second Footer Widget Area', 'babysitter'),
        'before_widget' => '<div id="%1$s" class="widget widget__footer %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    // Define Footer Widget 3
    register_sidebar(array(
        'name' => __('Footer Widget 3', 'babysitter'),
        'id' => 'footer3',
        'description'   => __( 'Third Footer Widget Area', 'babysitter'),
        'before_widget' => '<div id="%1$s" class="widget widget__footer %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    // Define Footer Widget 4
    register_sidebar(array(
        'name' => __('Footer Widget 4', 'babysitter'),
        'id' => 'footer4',
        'description'   => __( 'Fourth Footer Widget Area', 'babysitter'),
        'before_widget' => '<div id="%1$s" class="widget widget__footer %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

}
?>