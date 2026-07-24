<?php
/**
 * Custom post type: session ('Programmpunkte').
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace CM_Theme\Core\Post_Types\Session;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'           => __( 'Sessions', 'congressomat' ),
        'singular_name'  => __( 'Session', 'congressomat' ),
        'menu_name'      => __( 'Sessions', 'congressomat' ),
        'add_new'        => __( 'Add New Session', 'congressomat' ),
        'add_new_item'   => __( 'Add New Session', 'congressomat' ),
        'edit_item'      => __( 'Edit Session', 'congressomat' ),
        'new_item'       => __( 'New Session', 'congressomat' ),
        'view_item'      => __( 'View Session', 'congressomat' ),
        'view_items'     => __( 'View Sessions', 'congressomat' ),
        'search_items'   => __( 'Search Sessions', 'congressomat' ),
        'not_found'      => __( 'No Session Found', 'congressomat' ),
        'all_items'      => __( 'Sessions', 'congressomat' ),
        'name_admin_bar' => __( 'Session', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Sessions', 'congressomat' ),
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
        'show_in_menu'          => 'edit.php?post_type=session',
        'show_in_nav_menus'     => false,
        'delete_with_user'      => false,
        'exclude_from_search'   => false,
        'capability_type'       => 'page',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => true,
        'rewrite'               => [
            'slug'       => 'session',
            'with_front' => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'supports'              => [
            'title',
        ],
        'taxonomies'            => [
            'location',
            'event'
        ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'session', $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
