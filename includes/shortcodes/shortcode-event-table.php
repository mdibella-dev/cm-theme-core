<?php
/**
 * Shortcode [event-table].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a table with the schedule of a specific event.
 *
 * @since 2.1.0
 *
 * @param array $atts The attributes (parameters) of the shorcode.
 *                     - set
 *                       The selected setlist.
 *                     - event
 *                       The identification number of the event.
 *                     - speaker
 *                       The identification number of a speaker; is used to filter the contributions of this speaker.
 *                     - show_details
 *                       Allow details to be displayed (TRUE, FALSE).
 *
 * @return string The output produced by the shortcode.
 */

function cm_shortcode_event_table( $atts, $content = null )
{
    /** Determine passed parameters. */

    $default_atts = array(
        'set'          => '1',
        'speaker'      => '',
        'event'        => '',
        'date'         => '',
        'show_details' => 'false',
    );
    extract( shortcode_atts( $default_atts, $atts ) );


    /** Continue if setlist is valid */

    if( ( 1 <= $set ) and ( $set <= sizeof( EVENT_TABLE_SETLIST ) ) ) :

        /** Retrieve and prepare data. */

        // Either search for (active) sessions of the specified speaker (variant 1)
        // or search for the sessions of the specified event (variant 2)
        if( !empty( $speaker) ) :
            $sessions = cm_get_sessions_by_speaker( $speaker );
        elseif( !empty( $event ) ) :
            $sessions = cm_get_sessions_by_event( $event, $date );
        else :
            $sessions = null;
        endif;


        // Loop through each session along the setlist
        if( $sessions ) :
            $a_set  = explode( ',', EVENT_TABLE_SETLIST[ $set ]['a'] );
            $b_set  = explode( ',', EVENT_TABLE_SETLIST[ $set ]['b'] );

            $output = sprintf( '<div class="event-table has-set-%1$s">', $set );

            foreach( $sessions as $session ) :
                $output .= '<div class="event-table__session">';


                /** Process the elements configured by a_set. */

                $output .= '<div class="event-table__session-schedule">';

                foreach( $a_set as $data_key ) :

                    switch( $data_key ) :

                        case 'session-date' :
                            $output .= sprintf(
                                '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                get_field( 'programmpunkt-datum', $session->ID )
                            );
                        break;

                        case 'session-time-begin' :
                            $output .= sprintf(
                                '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                get_field( 'programmpunkt-von', $session->ID )
                            );
                        break;

                        case 'session-time-range' :
                            $data = get_field( 'programmpunkt-alternative-zeitangabe', $session->ID );

                            if( empty( $data ) ) :
                                $data = sprintf(
                                    '%1$s bis %2$s',
                                    get_field( 'programmpunkt-von', $session->ID ),
                                    get_field( 'programmpunkt-bis', $session->ID ) );
                            endif;

                            $output .= sprintf( '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                $data
                            );
                        break;

                        case 'session-location' :
                            $output .= sprintf(
                                '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                cm_get_location( get_field( 'programmpunkt-location', $session->ID ) )
                            );
                        break;

                    endswitch;

                endforeach;

                $output .= '</div>';


                /** Process the elements configured by b_set. */

                $output .= '<div class="event-table__session-overview">';

                foreach( $b_set as $data_key ) :

                    switch( $data_key ) :

                        case 'session-title' :
                            $output .= sprintf(
                                '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                $session->post_title
                            );
                        break;

                        case 'session-subtitle' :
                            $output .= sprintf(
                                '<div data-type="%1$s">%2$s</div>',
                                $data_key,
                                get_field( 'programmpunkt-untertitel', $session->ID )
                            );
                        break;

                        case 'session-speaker' :
                            $speakers = get_field( 'programmpunkt-referenten', $session->ID );

                            if( null != $speakers ) :
                                unset( $speakers_list );

                                foreach( $speakers as $speaker ) :
                                    $speaker_dataset = cm_get_speaker_dataset( $speaker );
                                    $speakers_list[] = sprintf(
                                        '<a href="%1$s" title="%2$s">%3$s</a>',
                                        $speaker_dataset[ 'permalink' ],
                                        sprintf(
                                            __( 'Mehr über %1$s erfahren', 'cm' ),
                                            $speaker_dataset[ 'title_name' ]
                                        ),
                                        get_the_post_thumbnail( $speaker_dataset[ 'id' ], 'full' ) );
                                endforeach;

                                $output .= sprintf(
                                    '<div data-type="%1$s">%2$s</div>',
                                    $data_key,
                                    implode( ' ', $speakers_list )
                                );
                            endif;

                        break;

                    endswitch;

                endforeach;

                $output .= '</div>';


                /** Enable display of detailed information (if available). */

                $details = apply_filters( 'the_content', get_field( 'programmpunkt-beschreibung', $session->ID ) );

                if( ( $show_details == true ) and !empty( $details ) ):
                    $output .= '<div class="event-table__session-toggle"><span><i class="far fa-angle-down"></i></span></div>';
                    $output .= sprintf ('<div class="event-table__session-details">%1$s</div>', $details );
                endif;

                $output .= '</div>';

            endforeach;

            $output .= '</div>';
        endif;
    endif;

    return $output;
}

add_shortcode( 'event-table', 'cm_shortcode_event_table' );
