<?php
/**
 * Custom taxonomy: event ('veranstaltung')
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Specifies the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function taxonomy_event__manage_edit_columns( $default )
{
    $columns = [
        'cb'          => $default['cb'],
        'id'          => 'ID',
        'name'        => $default['name'],
        'description' => $default['description'],
        'slug'        => $default['slug'],
        'status'      => __( 'Status', 'cm-theme-core' ),
        'posts'       => __( 'Events', 'cm-theme-core' ),
    ];
    return $columns;
}
add_filter( 'manage_edit-event_columns', __NAMESPACE__ . '\taxonomy_event__manage_edit_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function taxonomy_event__manage_custom_column( $content, $column_name, $term_id )
{
    switch( $column_name ) :
        case 'id':
            $content = $term_id;
        break;

        case 'status':
            $status = get_field( 'event-status', 'term_' . $term_id );

            $content = sprintf(
                '<span class="status-icon %1$s" title="%2$s"></span>',
                (1 == $status)? 'status-icon-active' : 'status-icon-inactive',
                (1 == $status)? __( 'active', 'cm-theme-core' ) : __( 'inactive', 'cm-theme-core' ),
            );
        break;

        default:
        break;
    endswitch;

    return $content;
}
add_filter( 'manage_event_custom_column', __NAMESPACE__ . '\taxonomy_event__manage_custom_column', 10, 3 );



/**
 * Registers the event taxonomy.
 *
 * @since 1.0.0
 */

function taxonomy_event__register()
{
    $labels = [
        'name'          => esc_html__( 'Events', 'cm-theme-core' ),
        'singular_name' => esc_html__( 'Event', 'cm-theme-core' ),
        'menu_name'     => esc_html__( 'Events', 'cm-theme-core' ),
        'all_items'     => esc_html__( 'All events', 'cm-theme-core' ),
    ];


    $args = [
        'label'                 => esc_html__( 'Events', 'cm-theme-core' ),
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

add_action( 'init', __NAMESPACE__ . '\taxonomy_event__register' );
