<?php
/**
 * Custom taxonomy: event ('veranstaltung')
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\taxonomies\event;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the event taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Events', 'cm-theme-core' ),
        'singular_name' => __( 'Event', 'cm-theme-core' ),
        'menu_name'     => __( 'Events', 'cm-theme-core' ),
        'all_items'     => __( 'All events', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Events', 'cm-theme-core' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'event',
            'with_front' => true,
        ],
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'event',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'event', ['session'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
