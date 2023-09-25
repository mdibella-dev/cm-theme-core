<?php
/**
 * Class Admin_Post_List_Session
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
 * A class for the implementation of the admin post list for post type "session".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Session extends \wordpress_helper\Admin_Post_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $post_type = 'session';



    /**
     * Determines the columns of the admin post list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns['cb']                = $default['cb'];
        $columns['title']             = $default['title'];
        $columns['taxonomy-event']    = __( 'Event', 'cm-theme-core' );
        $columns['taxonomy-location'] = __( 'Location', 'cm-theme-core' );
        $columns['event-date']        = __( 'Date', 'cm-theme-core' );
        $columns['event-time']        = __( 'Time period', 'cm-theme-core' );
        $columns['speaker']           = __( 'Speakers', 'cm-theme-core' );
        $columns['update']            = __( 'Last updated', 'cm-theme-core' );

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
            case 'speaker' :
                $speakers = get_field( 'programmpunkt-referenten', $post_id );

                if ( null != $speakers ) {

                    foreach ( $speakers as $speaker ) {
                        $speaker_dataset = api\get_speaker_dataset( $speaker );
                        echo sprintf(
                            '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                            $speaker_dataset['id'],
                            get_the_post_thumbnail( $speaker_dataset['id'], [ 100, 0 ] ),
                            sprintf(
                                __( 'Edit %1$s', 'cm-theme-core' ),
                                $speaker_dataset['name'],
                            ),
                        );
                    }
                } else {
                    echo '-';
                }
                break;

            case 'event-date' :
                echo get_field( 'programmpunkt-datum', $post_id );
                break;

            case 'event-time' :
                $time = get_field( 'programmpunkt-alternative-zeitangabe', $post_id );

                if ( empty( $time ) ) {
                    $time = sprintf(
                        __( 'from %1$s to %2$s', 'cm-theme-core' ),
                        get_field( 'programmpunkt-von', $post_id ),
                        get_field( 'programmpunkt-bis', $post_id )
                    );
                }

                echo $time;
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
        $columns['title']             = 'title';
        $columns['taxonomy-event']    = 'taxonomy-event';
        $columns['taxonomy-location'] = 'taxonomy-location';
        $columns['event-date']        = 'event-date';
        $columns['update']            = 'update';

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
            case 'event-date' :
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'programmpunkt-datum' );
                break;

            case 'update' :
                $query->set( 'orderby', 'modified' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Session();
