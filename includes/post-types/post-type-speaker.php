<?php
/**
 * Custom post type: speaker ('referent').
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

function post_type_speaker__manage_posts_columns( $default )
{
    $columns['cb']               = $default['cb'];
    $columns['image']            = __( 'Image', 'cm-theme-core' );
    $columns['title']            = __( 'Speaker', 'cm-theme-core' );
    $columns['shortdescription'] = __( 'Short description', 'cm-theme-core' );
    $columns['update']           = __( 'Last updated', 'cm-theme-core' );

    return $columns;
}

add_filter( 'manage_speaker_posts_columns', __NAMESPACE__ . '\post_type_speaker__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function post_type_speaker__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'image':
            if( true === has_post_thumbnail( $post_id ) ) :
                // alternativ: admin_url?
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Edit', 'cm-theme-core' )
                );
            else :
                echo '&mdash;';
            endif;
        break;

        case 'shortdescription':
            echo trim( implode( ' ', array(
                get_field( 'referent-titel', $post_id ),
                get_field( 'referent-vorname', $post_id ),
                get_field( 'referent-nachname', $post_id ),
            ) ) );

            $position = get_field( 'referent-position', $post_id );

            if( ! empty( $position ) ) :
                echo '<br>' . $position;
            endif;
        break;

        case 'update':
            echo sprintf(
                __( '%1$s at %2$s','cm-theme-core' ),
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_speaker_posts_custom_column', __NAMESPACE__ . '\post_type_speaker__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array $columns The columns.
 *
 * @return $array An associative array.
 */

function post_type_speaker__manage_sortable_columns( $columns )
{
    $columns['title']  = 'title';
    $columns['update'] = 'update';
    return $columns;
}

add_filter( 'manage_edit-speaker_sortable_columns', __NAMESPACE__ . '\post_type_speaker__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function post_type_speaker__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'update':
                $query->set( 'orderby', 'modified' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\post_type_speaker__pre_get_posts', 1 );
