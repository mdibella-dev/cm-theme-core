<?php
namespace Congressomat\Core\Taxonomies\Location;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the location taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Locations', 'congressomat' ),
        'singular_name' => __( 'Location', 'congressomat' ),
        'menu_name'     => __( 'Locations', 'congressomat' ),
        'search_items'  => __( 'Search Locations', 'congressomat' ),
        'all_items'     => __( 'All Locations', 'congressomat' ),
        'edit_item'     => __( 'Edit Location', 'congressomat' ),
        'view_item'     => __( 'View Location', 'congressomat' ),
        'update_item'   => __( 'Update Location', 'congressomat' ),
        'add_new_item'  => __( 'Add New Location', 'congressomat' ),
        'new_item_name' => __( 'New Title', 'congressomat' ),
        'not_found'     => __( 'No location found', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Locations', 'congressomat' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'location',
            'with_front' => true,
        ],
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'show_tagcloud'         => false,
        'rest_base'             => 'location',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'location', ['session'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
