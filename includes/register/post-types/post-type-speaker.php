<?php
/**
 * Custom post type: speaker ('referent').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace CM_Theme\Core\Post_Types\Speaker;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'                  => __( 'Speakers', 'cm-theme-core' ),
        'singular_name'         => __( 'Speaker', 'cm-theme-core' ),
        'menu_name'             => __( 'Speakers', 'cm-theme-core' ),
        'add_new'               => __( 'Add New Speaker', 'cm-theme-core' ),
        'add_new_item'          => __( 'Add New Speaker', 'cm-theme-core' ),
        'edit_item'             => __( 'Edit Speaker', 'cm-theme-core' ),
        'new_item'              => __( 'New Speaker', 'cm-theme-core' ),
        'view_item'             => __( 'View Speaker', 'cm-theme-core' ),
        'view_items'            => __( 'View Speaker', 'cm-theme-core' ),
        'search_items'          => __( 'Search Speaker', 'cm-theme-core' ),
        'not_found'             => __( 'No Speaker Found', 'cm-theme-core' ),
        'not_found_in_trash'    => __( 'No deleted speaker found', 'cm-theme-core' ),
        'all_items'             => __( 'Speakers', 'cm-theme-core' ),
        'name_admin_bar'        => __( 'Speaker', 'cm-theme-core' ),
        'featured_image'        => __( 'Speaker Image', 'cm-theme-core' ),
        'set_featured_image'    => __( 'Set Speaker Image', 'cm-theme-core' ),
        'remove_featured_image' => __( 'Remove Speaker Image', 'cm-theme-core' ),
        'use_featured_image'    => __( 'Use As Speaker Image', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Speakers', 'cm-theme-core' ),
        'labels'                => $labels,
        'description'           => '',
        'public'                => false,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_rest'          => true,
        'rest_base'             => '',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_namespace'        => 'wp/v2',
        'has_archive'           => false,
        'show_in_menu'          => 'edit.php?post_type=session',
        'show_in_nav_menus'     => false,
        'delete_with_user'      => false,
        'exclude_from_search'   => false,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => true,
        'rewrite'               => [
            'slug'       => 'speaker',
            'with_front' => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'supports'              => [
            'title',
            'thumbnail',
            'custom-fields'
        ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'speaker', $args );

}

add_action( 'init', __NAMESPACE__ . '\register' );
