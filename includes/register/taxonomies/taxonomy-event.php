<?php
/**
 * Custom taxonomy: event
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace CM_Theme\Core\Taxonomies\Event;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the event taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Events', 'congressomat' ),
        'singular_name' => __( 'Event', 'congressomat' ),
        'menu_name'     => __( 'Events', 'congressomat' ),
        'search_items'  => __( 'Search Events', 'congressomat' ),
        'all_items'     => __( 'All Events', 'congressomat' ),
        'edit_item'     => __( 'Edit Event', 'congressomat' ),
        'view_item'     => __( 'View Event', 'congressomat' ),
        'update_item'   => __( 'Update Event', 'congressomat' ),
        'add_new_item'  => __( 'Add New Event', 'congressomat' ),
        'new_item_name' => __( 'New Event Title', 'congressomat' ),
        'not_found'     => __( 'No event found', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Events', 'congressomat' ),
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
        'show_tagcloud'         => false,
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
