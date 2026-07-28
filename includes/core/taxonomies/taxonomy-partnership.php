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
        'name'          => __( 'Partnerships', 'congressomat' ),
        'singular_name' => __( 'Partnership', 'congressomat' ),
        'menu_name'     => __( 'Partnerships', 'congressomat' ),
        'search_items'  => __( 'Search Partnerships', 'congressomat' ),
        'all_items'     => __( 'All Partnerships', 'congressomat' ),
        'edit_item'     => __( 'Edit Partnership', 'congressomat' ),
        'view_item'     => __( 'View Partnership', 'congressomat' ),
        'update_item'   => __( 'Update Partnership', 'congressomat' ),
        'add_new_item'  => __( 'Add New Partnership', 'congressomat' ),
        'new_item_name' => __( 'New Partnership Title', 'congressomat' ),
        'not_found'     => __( 'No Partnership found', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Partnerships', 'congressomat' ),
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
        'show_tagcloud'         => true,
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
