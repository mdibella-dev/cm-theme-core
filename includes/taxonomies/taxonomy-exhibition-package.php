<?php
/**
 * Custom taxonomy: exhibition_package ('Ausstellungspaket').
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

function cm_set_exhibition_package_columns( $default )
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
add_filter( 'manage_edit-exhibition_package_columns', 'cm_set_exhibition_package_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 2.5.0
 */

function cm_manage_exhibition_package_custom_column( $content, $column_name, $term_id )
{
    switch ($column_name) {
        case 'id':
            $content = $term_id;
        break;

        case 'count':
            $posts = get_posts( array(
                'post_type'   => 'exhibition_space',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => array( array(
                    'taxonomy' => 'exhibition_package',
                    'terms'    => $term_id,
                ) ),
            ) );
            $term    = get_term( $term_id, 'exhibition_package' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?exhibition_package=%2$s&post_type=exhibition_space" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'Alle Ausstellungsflächen mit diesem Austellungspaket anzeigen', 'cm' )
            );
        break;

        default:
        break;
    }
    return $content;
}
add_filter( 'manage_exhibition_package_custom_column', 'cm_manage_exhibition_package_custom_column', 10, 3 );
