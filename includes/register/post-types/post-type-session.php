<?php
/**
 * Custom post type: session ('Programmpunkte').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\post_types\session;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'           => __( 'Sessions', 'cm-theme-core' ),
        'singular_name'  => __( 'Session', 'cm-theme-core' ),
        'menu_name'      => __( 'Sessions', 'cm-theme-core' ),
        'all_items'      => __( 'Sessions', 'cm-theme-core' ),
        'add_new'        => __( 'Add new', 'cm-theme-core' ),
        'add_new_item'   => __( 'New session', 'cm-theme-core' ),
        'name_admin_bar' => __( 'Session', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Sessions', 'cm-theme-core' ),
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
            'thumbnail'
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
