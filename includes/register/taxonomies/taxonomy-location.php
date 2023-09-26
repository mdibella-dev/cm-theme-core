<?php
/**
 * Custom taxonomy: location ('Örtlichkeiten').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\taxonomies\location;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the location taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Locations', 'cm-theme-core' ),
        'singular_name' => __( 'Location', 'cm-theme-core' ),
        'menu_name'     => __( 'Locations', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Locations', 'cm-theme-core' ),
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
        'show_tagcloud'         => true,
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
