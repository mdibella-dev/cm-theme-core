<?php
/**
 * Custom post type: exhibition_space ('Ausstellungsfläche').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\post_types\exhibition_space;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Exhibition spaces', 'cm-theme-core' ),
        'singular_name' => __( 'Exhibition space', 'cm-theme-core' ),
        'menu_name'     => __( 'Exhibition spaces', 'cm-theme-core' ),
        'all_items'     => __( 'Exhibition spaces', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Exhibition spaces', 'cm-theme-core' ),
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
        'show_in_menu'          => 'edit.php?post_type=session',
        'show_in_nav_menus'     => false,
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
