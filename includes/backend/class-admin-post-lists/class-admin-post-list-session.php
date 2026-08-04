<?php
/**
 * Class Admin_Post_List_Session
 *
 * @author  Marco Di Bella
 * @package congressomat
 * @uses    ACF
 */

namespace Congressomat\Backend;

use \Congressomat\Core\API as API;



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
            'session-timeframe' => __( 'Time Frame', 'congressomat' ),
            'session-duration'  => __( 'Duration', 'congressomat' ),
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

                    echo '<div class="congressomat-speaker-grid">';
                    foreach ( $speakers as $speaker ) {
                        $speaker_dataset = API\get_speaker_dataset( $speaker );
                        $speaker_id      = $speaker_dataset['id'];
                        $speaker_name    = trim( implode( ' ', [
                            $speaker_dataset['firstname'],
                            $speaker_dataset['lastname']
                        ] ) );

                        echo '<div>';
                        echo sprintf(
                            '<a class="congressomat-speaker-image" href="%1$s">%2$s</a>',
                            esc_url( sprintf(
                                '%1$spost.php?post=%2$s&action=edit',
                                get_admin_url(),
                                $speaker_id,
                            ) ),
                            get_the_post_thumbnail(
                                $speaker_id,
                                'thumbnail'
                            ),
                        );
                        echo '</div>';
                        echo '<div>';
                        echo sprintf(
                            '<a href="%1$s">%2$s</a>',
                            esc_url( sprintf(
                                '%1$spost.php?post=%2$s&action=edit',
                                get_admin_url(),
                                $speaker_id,
                            ) ),
                            $speaker_name
                        );
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '&mdash;';
                }
                break;

            case 'event-date':
                echo get_field( 'programmpunkt-datum', $post_id );
                break;

            case 'session-timeframe':
                $time_begin = get_field( 'programmpunkt-von', $post_id );
                $time_end   = get_field( 'programmpunkt-bis', $post_id );

                if ( ! empty( $time_begin ) and ! empty( $time_end ) ) {
                    echo sprintf(
                        '<div class="congressomat-session-timeframe"><div>%1$s</div><div>&rarr;</div><div>%2$s</div></div>',
                        $time_begin,
                        $time_end
                    );
                } else {
                    echo __( 'N/A', 'congressomat' );
                }
                break;

            case 'session-duration':
                $time_begin = get_field( 'programmpunkt-von', $post_id );
                $time_end   = get_field( 'programmpunkt-bis', $post_id );

                if ( ! empty( $time_begin ) and ! empty( $time_end ) ) {
                    $origin = new \DateTimeImmutable( $time_begin );
                    $target = new \DateTimeImmutable( $time_end );

                    $duration = $origin->diff( $target );

                    echo $duration->format('%H:%I');
                } else {
                    echo '&mdash;';
                }
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
            case '':
            case 'event-date':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'CONGRESSOMAT_SESSION_DATE_SORTKEY' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }



    /**
     * Filters the list of views.
     *
     * @param string[] $views An array of available list table views.
     */

    public function filter_views( $views ) {
        // Remove unused default filter options
        unset( $views['mine'] );
        unset( $views['publish'] );
        unset( $views['draft'] );

        // Add event filter options
        $events = API\get_active_events();

        foreach( $events as $id ) {
            $term    = get_term( $id, 'event' );
            $current = '';

            if ( ( isset( $_GET['event'] ) ) and ( $term->slug == $_GET['event'] ) ) {
                $current = 'current';
            }

            $views[$term->slug] = sprintf(
                '<a class="%4$s" href="%1$s">%2$s <span class="count">(%3$s)</span></a>',
                esc_url( sprintf(
                    '%1$sedit.php?post_type=session&event=%2$s',
                    get_admin_url(),
                    $term->slug
                ) ),
                $term->name,
                $term->count,
                $current
            );
        }

        return $views;
    }
}


new Admin_Post_List_Session();
