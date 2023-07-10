<?php
/**
 * Custom post type: exhibition_space ('Ausstellungsfläche').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Determines the columns of the post list (backend).
 *
 * @since 2.5.0
 *
 * @param array $default The defaults for columns.
 *
 * @return $array An associative array describing the columns to use.
 */

function cm_post_type_exhibition_space__manage_posts_columns( $default )
{
    $columns['cb']                          = $default['cb'];
    $columns['title']                       = __( 'Ausstellungsfläche', 'cm' );
    $columns['taxonomy-location']           = __( 'Standort', 'cm' );
    $columns['taxonomy-exhibition_package'] = __( 'Ausstellungspaket', 'cm' );
    $columns['update']                      = __( 'Zuletzt aktualisiert', 'cm' );

    return $columns;
}

add_filter( 'manage_exhibition_space_posts_columns', 'cm_post_type_exhibition_space__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function cm_post_type_exhibition_space__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'update':
            echo sprintf(
                '%1$s um %2$s Uhr',
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_exhibition_space_posts_custom_column', 'cm_post_type_exhibition_space__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array columns The columns.
 *
 * @return $array An associative array.
 */

function cm_post_type_exhibition_space__manage_sortable_columns( $columns )
{
    $columns['title']                       = 'title';
    //$columns['taxonomy-exhibition_package'] = 'taxonomy-exhibition_package';
    $columns['taxonomy-location']           = 'taxonomy-location';
    $columns['update']                      = 'update';
    return $columns;
}

add_filter( 'manage_edit-exhibition_space_sortable_columns', 'cm_post_type_exhibition_space__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function cm_post_type_exhibition_space__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'update':
                $query->set( 'orderby', 'modified' );
            break;

            default:
            case '':
                $query->set( 'orderby', 'title' );
                $query->set( 'order', 'asc' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', 'cm_post_type_exhibition_space__pre_get_posts', 1 );
