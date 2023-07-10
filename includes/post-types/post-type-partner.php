<?php
/**
 * Custom post type: partner ('Kongresspartner').
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

function post_type_partner__manage_posts_columns( $default )
{
    $columns['cb']                   = $default['cb'];
    $columns['image']                = __( 'Bild', 'mdb' );
    $columns['title']                = __( 'Kooperationspartner', 'cm' );
    $columns['taxonomy-partnership'] = __( 'Kooperationsformen', 'cm' );
    $columns['exhibition']           = __( 'Ausstellungsflächen', 'cm' );
    $columns['update']               = __( 'Zuletzt aktualisiert', 'cm' );

    return $columns;
}

add_filter( 'manage_partner_posts_columns', __NAMESPACE__ . '\post_type_partner__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function post_type_partner__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'image':
            if( true === has_post_thumbnail( $post_id ) ) :
                // alternatively: admin_url?
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Bearbeiten', 'cm' )
                );
            else :
                echo '&mdash;';
            endif;
        break;

        case 'exhibition':
            $data = cm_get_partner_dataset( $post_id );

            if( ! empty( $data['exhibition-spaces'] ) ) :
                $spaces = array();

                foreach( $data[ 'exhibition-spaces' ] as $space ) :
                    if( ! empty( $space[ 'location' ] ) and ! empty( $space[ 'signature' ] ) ) :
                        $spaces[] = sprintf(
                            '<a href="post.php?post=%1$s&action=edit">%2$s</a>%3$s',
                            $space['id'],
                            $space['signature'],
                            ( ! empty( $space['package'] ) )? ' (' . $space['package'] . ')' : '',
                        );
                    endif;
                endforeach;

                if( ! empty( $spaces ) ) :
                    echo implode( ', ', $spaces );
                else :
                    echo '&mdash;';
                endif;
            else :
                echo '&mdash;';
            endif;
        break;

        case 'update':
            echo sprintf(
                '%1$s um %2$s Uhr',
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_partner_posts_custom_column', __NAMESPACE__ . '\post_type_partner__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array  $columns The columns.
 *
 * @return $array An associative array.
 */

function post_type_partner__manage_sortable_columns( $columns )
{
    $columns['title']  = 'title';
    $columns['update'] = 'update';
    return $columns;
}

add_filter( 'manage_edit-partner_sortable_columns', __NAMESPACE__ . '\post_type_partner__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function post_type_partner__pre_get_posts( $query )
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

add_action( 'pre_get_posts', __NAMESPACE__ . '\post_type_partner__pre_get_posts', 1 );
