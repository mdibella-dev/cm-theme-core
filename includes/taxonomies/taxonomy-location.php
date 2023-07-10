<?php
/**
 * Custom taxonomy: location ('Örtlichkeiten').
 *
 * @author  Marco Di Bella
 * @package cm
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Specifies the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function cm_set_location_columns( $default )
{
    $columns = array(
        'cb'            => $default['cb'],
        'id'            => 'ID',
        'image'         => __( 'Bild', 'cm' ),
        'name'          => $default['name'],
        'description'   => $default['description'],
        'slug'          => $default['slug'],
        'count-session' => __( 'Programmpunkte', 'cm' ),
        'count-space'   => __( 'Ausstellungsflächen', 'cm' ),
    );
    return $columns;
}
add_filter( 'manage_edit-location_columns', 'cm_set_location_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function cm_manage_location_custom_column( $content, $column_name, $term_id )
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
                __( 'Alle Programmpunkte an diesem Ort anzeigen', 'cm' )
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
                __( 'Alle Ausstellungsflächen an diesem Ort anzeigen', 'cm' )
            );
        break;

        default:
        break;
    }
    return $content;
}
add_filter( 'manage_location_custom_column', 'cm_manage_location_custom_column', 10, 3 );
