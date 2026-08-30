<?php
namespace Congressomat\Core\Post_Types\Speaker;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'                  => __( 'Speakers', 'congressomat' ),
        'singular_name'         => __( 'Speaker', 'congressomat' ),
        'menu_name'             => __( 'Speakers', 'congressomat' ),
        'add_new'               => __( 'Add New Speaker', 'congressomat' ),
        'add_new_item'          => __( 'Add New Speaker', 'congressomat' ),
        'edit_item'             => __( 'Edit Speaker', 'congressomat' ),
        'new_item'              => __( 'New Speaker', 'congressomat' ),
        'view_item'             => __( 'View Speaker', 'congressomat' ),
        'view_items'            => __( 'View Speaker', 'congressomat' ),
        'search_items'          => __( 'Search Speaker', 'congressomat' ),
        'not_found'             => __( 'No Speaker Found', 'congressomat' ),
        'not_found_in_trash'    => __( 'No deleted speaker found', 'congressomat' ),
        'all_items'             => __( 'Speakers', 'congressomat' ),
        'name_admin_bar'        => __( 'Speaker', 'congressomat' ),
        'featured_image'        => __( 'Speaker Image', 'congressomat' ),
        'set_featured_image'    => __( 'Set Speaker Image', 'congressomat' ),
        'remove_featured_image' => __( 'Remove Speaker Image', 'congressomat' ),
        'use_featured_image'    => __( 'Use As Speaker Image', 'congressomat' ),
    ];

    $menu = 'edit.php?post_type=session';

    $args = [
        'label'                 => __( 'Speakers', 'congressomat' ),
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
        'show_in_menu'          => $menu,
        'show_in_nav_menus'     => false,
        'show_in_admin_bar'     => false,
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
