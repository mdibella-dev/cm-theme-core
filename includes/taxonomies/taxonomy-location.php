<?php
/**
 * Custom taxonomy: location ('Örtlichkeiten').
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

function taxonomy_location__manage_edit_columns( $default )
{
    $columns = array(
        'cb'            => $default['cb'],
        'id'            => 'ID',
        'image'         => __( 'Image', 'cm-theme-core' ),
        'name'          => $default['name'],
        'description'   => $default['description'],
        'slug'          => $default['slug'],
        'count-session' => __( 'Sessions', 'cm-theme-core' ),
        'count-space'   => __( 'Exhibition spaces', 'cm-theme-core' ),
    );
    return $columns;
}
add_filter( 'manage_edit-location_columns', __NAMESPACE__ . '\taxonomy_location__manage_edit_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function taxonomy_location__manage_custom_column( $content, $column_name, $term_id )
{
    switch ($column_name) {
        case 'id':
            $content = $term_id;
        break;

        case 'image':
            $image_id = get_field( 'location-image', 'location_' . $term_id );
            $image    = wp_get_attachment_image( $image_id, array( '150', '9999' ) );

            if( ! empty( $image ) ) :
                echo $image;
            else :
                echo '&mdash;';
            endif;
        break;

        case 'count-session':
            $posts = get_posts( array(
                'post_type'   => 'session',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => array( array(
                    'taxonomy' => 'location',
                    'terms'    => $term_id,
                ) ),
            ) );
            $term    = get_term( $term_id, 'location' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?location=%2$s&post_type=session" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'View all sessions at this location', 'cm-theme-core' )
            );
        break;

        case 'count-space':
            $posts = get_posts( array(
                'post_type'   => 'exhibition_space',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => array( array(
                    'taxonomy' => 'location',
                    'terms'    => $term_id,
                ) ),
            ) );
            $term    = get_term( $term_id, 'location' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?location=%2$s&post_type=exhibition_space" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'View all exhibition spaces in this location', 'cm-theme-core' )
            );
        break;

        default:
        break;
    }
    return $content;
}
add_filter( 'manage_location_custom_column', __NAMESPACE__ . '\taxonomy_location__manage_custom_column', 10, 3 );
