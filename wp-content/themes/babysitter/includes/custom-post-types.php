<?php

/*
 * ========================================================================
 * Custom Post Types
 * ========================================================================
 */

// Slides
function create_post_type_slides() {
    register_post_type( 'slides',
        array( 
        'label' => __('Slider', 'babysitter'), 
        'public' => true, 
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'menu_icon' => 'dashicons-format-image',
        'rewrite' => array(
            'slug' => 'slides',
            'with_front' => FALSE,
        ),
        'supports' => array(
                'title',
                'thumbnail',
                'page-attributes')
            ) 
        );
}


// Our Team
function create_post_type_team() {
    register_post_type( 'team',
        array( 
        'label' => __('Team', 'babysitter'), 
        'singular_label' => __('Person', 'babysitter'),
        '_builtin' => false,
        'exclude_from_search' => true, // Exclude from Search Results
        'capability_type' => 'page',
        'public' => true, 
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-groups',
        'menu_position' => 5,
        'rewrite' => array(
            'slug' => 'team',
            'with_front' => FALSE,
        ),
        'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'page-attributes')
            ) 
        );
    register_taxonomy(
        'team_category', 
        'team', array(
            'hierarchical' => true, 
            'label' => __('Team Categories', 'babysitter'), 
            'singular_name' => __('Category', 'babysitter'), 
            "rewrite" => true,
            "query_var" => true)
        );

}
?>