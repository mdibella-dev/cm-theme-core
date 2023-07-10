<?php
/**
 * Custom taxonomy: partnership ('Kooperationsformen').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Specifies the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function cm_set_partnership_columns( $default )
{
    $columns = array(
        'cb'            => $default['cb'],
        'id'            => 'ID',
        'name'          => $default['name'],
        'description'   => $default['description'],
        'slug'          => $default['slug'],
        'count'         => __( 'Anzahl', 'cm' ),
    );
    return $columns;
}
add_filter( 'manage_edit-partnership_columns', 'cm_set_partnership_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function cm_manage_partnership_custom_column( $content, $column_name, $term_id )
{
    switch ($column_name) {
        case 'id':
            $content = $term_id;
        break;

        case 'count':
            $posts = get_posts( array(
                'post_type'   => 'partner',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => array( array(
                    'taxonomy' => 'partnership',
                    'terms'    => $term_id,
                ) ),
            ) );
            $term    = get_term( $term_id, 'partnership' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?partnership=%2$s&post_type=partner" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'Alle in dieser Form kooperienden Partner anzeigen', 'cm' )
            );
        break;

        default:
        break;
    }
    return $content;
}
add_filter( 'manage_partnership_custom_column', 'cm_manage_partnership_custom_column', 10, 3 );
