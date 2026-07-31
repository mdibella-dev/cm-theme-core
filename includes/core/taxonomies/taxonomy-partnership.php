<?php
/**
 * Custom taxonomy: partnership
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Core\Taxonomies\Partnership;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the partnership taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Exhibitor Roles', 'congressomat' ),
        'singular_name' => __( 'Exhibitor Role', 'congressomat' ),
        'menu_name'     => __( 'Exhibitor Roles', 'congressomat' ),
        'search_items'  => __( 'Search Exhibitor Roles', 'congressomat' ),
        'all_items'     => __( 'All Exhibitor Roles', 'congressomat' ),
        'edit_item'     => __( 'Edit Exhibitor Role', 'congressomat' ),
        'view_item'     => __( 'View Exhibitor Role', 'congressomat' ),
        'update_item'   => __( 'Update Exhibitor Role', 'congressomat' ),
        'add_new_item'  => __( 'Add New Exhibitor Role', 'congressomat' ),
        'new_item_name' => __( 'New Title', 'congressomat' ),
        'not_found'     => __( 'No exhibitor role found', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Exhibitor Roles', 'congressomat' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'partnership',
            'with_front' => true,
        ],
        'show_admin_column'     => false,
        'show_in_rest'          => true,
        'show_tagcloud'         => false,
        'rest_base'             => 'partnership',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'partnership', ['partner'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
