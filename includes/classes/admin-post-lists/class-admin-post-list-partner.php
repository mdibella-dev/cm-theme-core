<?php
/**
 * Class Admin_Post_List_Partner
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 * @uses    ACF
 */

namespace cm_theme_core;

use \cm_theme_core\api as api;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin post list for post type "partner".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Partner extends \wordpress_helper\Admin_Post_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $post_type = 'partner';



    /**
     * Determines the columns of the admin post list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns['cb']                   = $default['cb'];
        $columns['image']                = __( 'Image', 'cm-theme-core' );
        $columns['title']                = __( 'Partner', 'cm-theme-core' );
        $columns['taxonomy-partnership'] = __( 'Partnership', 'cm-theme-core' );
        $columns['exhibition']           = __( 'Exhibition space', 'cm-theme-core' );
        $columns['update']               = __( 'Last updated', 'cm-theme-core' );

        return $columns;
    }



    /**
     * Generates the column output.
     *
     * @param string $column_name Designation of the column to be output
     * @param int    $post_id     ID of the post (aka record) to be output
     */

    public function manage_custom_column( $column_name, $post_id ) {

        switch ( $column_name ) {
            case 'image' :
                if ( true === has_post_thumbnail( $post_id ) ) {
                    // alternatively: admin_url?
                    echo sprintf(
                        '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                        $post_id,
                        get_the_post_thumbnail( $post_id, [ 100, 0 ] ),
                        __( 'Edit', 'cm-theme-core' )
                    );
                } else {
                    echo '&mdash;';
                }
                break;

            case 'exhibition' :
                $data = api\get_partner_dataset( $post_id );

                if ( ! empty( $data['exhibition-spaces'] ) ) {
                    $spaces = [];

                    foreach ( $data['exhibition-spaces'] as $space ) {
                        if ( ! empty( $space['location'] ) and ! empty( $space['signature'] ) ) {
                            $spaces[] = sprintf(
                                '<a href="post.php?post=%1$s&action=edit">%2$s</a>%3$s',
                                $space['id'],
                                $space['signature'],
                                ( ! empty( $space['package'] ) )? ' (' . $space['package'] . ')' : '',
                            );
                        }
                    }

                    if ( ! empty( $spaces ) ) {
                        echo implode( ', ', $spaces );
                    } else {
                        echo '&mdash;';
                    }
                } else {
                    echo '&mdash;';
                }
                break;

            case 'update' :
                echo sprintf(
                    __( '%1$s at %2$s', 'cm-theme-core' ),
                    get_the_modified_date( 'd.m.Y', $post_id ),
                    get_the_modified_date( 'H:i', $post_id ),
                );
                break;
        }
    }



    /**
     * Registers sortable columns (by assigning appropriate orderby parameters).
     *
     * @param array columns The columns
     *
     * @return array An associative array
     */

    public function manage_sortable_columns( $columns ) {
        $columns['title']  = 'title';
        $columns['update'] = 'update';

        return $columns;
    }



    /**
     * Modifys the query string (by assigning appropriate parameters).
     *
     * @param WP_Query $query   A data object of the last query made
     */

    public function manage_sorting( &$query ) {
        $orderby = $query->get( 'orderby' );
        $order   = $query->get( 'order' );

        switch ( $orderby ) {
            case 'update' :
                $query->set( 'orderby', 'modified' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Partner();
