<?php
/**
 * Custom post type: exhibition_space
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace CM_Theme\Core\Post_Types\Exhibition_Space;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Exhibition Spaces', 'congressomat' ),
        'singular_name' => __( 'Exhibition Space', 'congressomat' ),
        'menu_name'     => __( 'Exhibition Spaces', 'congressomat' ),
        'all_items'     => __( 'Exhibition Spaces', 'congressomat' ),
    ];

    $menu = 'edit.php?post_type=session';

    $args = [
        'label'                 => __( 'Exhibition Spaces', 'congressomat' ),
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
