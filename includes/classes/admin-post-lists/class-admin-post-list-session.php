<?php
/**
 * Class Admin_Post_List_Session
 *
 * @author  Marco Di Bella
 * @package congressomat
 * @uses    ACF
 */

namespace CM_Theme\Core;

use \CM_Theme\Core\API as API;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin post list for post type "session".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Session extends \WordPress_Helper\Admin_Post_List {

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
        $columns = [
            'cb'                => $default['cb'],
            'title'             => $default['title'],
            'taxonomy-event'    => __( 'Event', 'congressomat' ),
            'taxonomy-location' => __( 'Event Location', 'congressomat' ),
            'event-date'        => __( 'Event Date', 'congressomat' ),
            'event-time'        => __( 'Time Slot', 'congressomat' ),
            'speaker'           => __( 'Speakers', 'congressomat' ),
        ];

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
            case 'speaker':
                $speakers = get_field( 'programmpunkt-referenten', $post_id );

                if ( null != $speakers ) {

                    foreach ( $speakers as $speaker ) {
                        $speaker_dataset = API\get_speaker_dataset( $speaker );
                        $speaker_id      = $speaker_dataset['id'];

                        echo sprintf(
                            '<a href="%1$s" title="%2$s">%3$s</a>',
                            esc_url( sprintf(
                                '%1$spost.php?post=%2$s&action=edit',
                                get_admin_url(),
                                $speaker_id,
                            ) ),
                            __( 'Edit Speaker', 'congressomat' ),
                            get_the_post_thumbnail(
                                $speaker_id,
                                'thumbnail',
                                [
                                    'class' => 'speaker-icon'
                                ]
                            ),
                        );
                    }
                } else {
                    echo '&mdash;';
                }
                break;

            case 'event-date':
                echo get_field( 'programmpunkt-datum', $post_id );
                break;

            case 'event-time':
                $time = get_field( 'programmpunkt-alternative-zeitangabe', $post_id );

                if ( empty( $time ) ) {
                    $time = sprintf(
                        __( '%1$s to %2$s', 'congressomat' ),
                        get_field( 'programmpunkt-von', $post_id ),
                        get_field( 'programmpunkt-bis', $post_id )
                    );
                }

                echo $time;
                break;

            case 'update':
                echo sprintf(
                    __( '%1$s at %2$s', 'congressomat' ),
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
            case 'event-date':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'programmpunkt-datum' );
                break;

            case 'update':
                $query->set( 'orderby', 'modified' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Session();
