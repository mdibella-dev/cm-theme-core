<?php
namespace Congressomat\Core\Post_Types\Exhibition_Space;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'                  => __( 'Booths', 'congressomat' ),
        'singular_name'         => __( 'Booth', 'congressomat' ),
        'menu_name'             => __( 'Booths', 'congressomat' ),
        'add_new'               => __( 'Add New Booth', 'congressomat' ),
        'add_new_item'          => __( 'Add New Booth', 'congressomat' ),
        'edit_item'             => __( 'Edit Booth', 'congressomat' ),
        'new_item'              => __( 'New Booth', 'congressomat' ),
        'view_item'             => __( 'View Booth', 'congressomat' ),
        'view_items'            => __( 'View Booth', 'congressomat' ),
        'search_items'          => __( 'Search Booth', 'congressomat' ),
        'not_found'             => __( 'No booth found', 'congressomat' ),
        'not_found_in_trash'    => __( 'No deleted booth found', 'congressomat' ),
        'all_items'             => __( 'Booths', 'congressomat' ),
        'name_admin_bar'        => __( 'Booth', 'congressomat' ),
    ];

    $menu = 'edit.php?post_type=session';

    $args = [
        'label'                 => __( 'Booths', 'congressomat' ),
        'labels'                => $labels,
        'description'           => '',
        'public'                => true,
        'publicly_queryable'    => false,
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
        'exclude_from_search'   => true,
        'capability_type'       => 'page',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => true,
        'rewrite'               => [
            'slug'       => 'exhibition_space',
            'with_front' => true
        ],
        'query_var'             => true,
        'supports'              => [
            'title'
        ],
        'taxonomies'            => [
            'location',
            'exhibition_package'
        ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'exhibition_space', $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
