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
 * @since 2.5.0
 */

function taxonomy_event__manage_edit_columns( $default )
{
    $columns = array(
        'cb'            => $default['cb'],
        'id'            => 'ID',
        'name'          => $default['name'],
        'description'   => $default['description'],
        'slug'          => $default['slug'],
        'status'        => __( 'Status', 'cm-theme-core' ),
        'posts'         => __( 'Events', 'cm-theme-core' ),
    );
    return $columns;
}
add_filter( 'manage_edit-event_columns', __NAMESPACE__ . '\taxonomy_event__manage_edit_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function taxonomy_event__manage_custom_column( $content, $column_name, $term_id )
{
    switch ($column_name) {
        case 'id':
            $content = $term_id;
        break;

        case 'status':
            $status = get_field( 'event-status', 'term_' . $term_id );

            $content  = sprintf(
                '<span class="status-icon %1$s" title="%2$s"></span>',
                (1 == $status)? 'status-icon-active' : 'status-icon-inactive',
                (1 == $status)? __( 'active', 'cm-theme-core' ) : __( 'inactive', 'cm-theme-core' ),
            );
        break;

        default:
        break;
    }
    return $content;
}
add_filter( 'manage_event_custom_column', __NAMESPACE__ . '\taxonomy_event__manage_custom_column', 10, 3 );
