<?php
/**
 * Custom post type: partner
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Core\Post_Types\Partner;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'                  => __( 'Exhibitors', 'congressomat' ),
        'singular_name'         => __( 'Exhibitor', 'congressomat' ),
        'menu_name'             => __( 'Exhibitors', 'congressomat' ),
        'add_new'               => __( 'Add New Exhibitor', 'congressomat' ),
        'add_new_item'          => __( 'Add New Exhibitor', 'congressomat' ),
        'edit_item'             => __( 'Edit Exhibitor', 'congressomat' ),
        'new_item'              => __( 'New Exhibitor', 'congressomat' ),
        'view_item'             => __( 'View Exhibitor', 'congressomat' ),
        'view_items'            => __( 'View Exhibitor', 'congressomat' ),
        'search_items'          => __( 'Search Exhibitor', 'congressomat' ),
        'not_found'             => __( 'No exhibitor found', 'congressomat' ),
        'not_found_in_trash'    => __( 'No deleted exhibitor found', 'congressomat' ),
        'all_items'             => __( 'Exhibitors', 'congressomat' ),
        'name_admin_bar'        => __( 'Exhibitor', 'congressomat' ),
        'featured_image'        => __( 'Exhibitor Logo', 'congressomat' ),
        'set_featured_image'    => __( 'Set Exhibitor Logo', 'congressomat' ),
        'remove_featured_image' => __( 'Remove Exhibitor Logo', 'congressomat' ),
        'use_featured_image'    => __( 'Use As Exhibitor Logo', 'congressomat' ),
    ];

    $menu = 'edit.php?post_type=session';

    $args = [
        'label'                 => __( 'Exhibitors', 'congressomat' ),
        'labels'                => $labels,
        'description'           => '',
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_rest'          => true,
        'rest_base'             => '',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_namespace'        => 'wp/v2',
        'has_archive'           => false,
        'show_in_menu'          => $menu,
        'show_in_nav_menus'     => true,
        'delete_with_user'      => false,
        'exclude_from_search'   => false,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => false,
        'rewrite'               => [
            'slug'       => 'partner',
            'with_front' => true
        ],
        'query_var'             => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'supports'              => [
            'title',
            'thumbnail'
        ],
        'taxonomies'            => [
            'partnership'
        ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'partner', $args );

}

add_action( 'init', __NAMESPACE__ . '\register' );
