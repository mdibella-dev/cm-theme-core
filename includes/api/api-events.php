<?php
/**
 * CM core functions.
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\api;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Determines the currently active events.
 *
 * @since  1.0.0
 * @return array
 */

function get_active_events() {
    $events = [];
    $terms  = get_terms( [
        'taxonomy'   => 'event',
        'hide_empty' => 'false',
        'meta_key'   => 'event-status',
        'meta_value' => '1',
    ] );

    if( false === $terms ) :
        return null;
    endif;

    foreach( $terms as $term ) :
        $events[] = $term->term_taxonomy_id;
    endforeach;

    return $events;
}


/**
 * Determines the speakers from all sessions from one or more events.
 *
 * @since 1.0.0
 *
 * @param string $event_list_string A comma-separated list of events (IDs)
 *
 * @return array
 */

function get_speaker_datasets( $event_list_string = '' ) {

    // Construction and implementation of the data query.
    // If no events have been specified (i.e. $event_list_string is empty), the active events will be used as a basis.
    $query = [
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'post_type'      => 'session',
        'meta_query'     => [[
            'key'     => 'programmpunkt-referenten',
            'compare' => 'EXISTS',
        ]],
        'tax_query' => [
            'relation' => 'OR'
        ]
    ];

    if( ! empty( $event_list_string ) ) :
        $event_list = explode( ',', str_replace( " ", "", $event_list_string ) );
    else :
        $event_list = get_active_events();
    endif;

    foreach( $event_list as $event ) :
        $query['tax_query'][] = [
            'taxonomy' => 'event',
            'field'    => 'term_id',
            'terms'    => $event,
        ];
    endforeach;

    $sessions = get_posts( $query );


    // Identification of the affected speakers.
    if( $sessions ) :
        $finds_list   = [];
        $speaker_list = [];

        foreach( $sessions as $session ) :
            $speakers = get_field( 'programmpunkt-referenten', $session->ID );

            if( null != $speakers ) :
                foreach( $speakers as $speaker ) :
                    // Do not add if already in the list.
                    if( false == in_array( $speaker, $finds_list ) ) :
                        $finds_list[]   = $speaker;
                        $speaker_list[] = get_speaker_dataset( $speaker );
                    endif;
                endforeach;
            endif;
        endforeach;


        // Sorting the found speakers by first and last name.
        return sort_speaker_datasets( $speaker_list );
    endif;

    return null;
}


/**
 * Determines the name of an event.
 *
 * @since 1.0.0
 *
 * @param int $event
 *
 * @return string
 */

function get_event( $event ) {
    
    if( ! empty( $event ) ) :
        $term = get_term_by( 'term_taxonomy_id', $event, 'event' );

        if( false != $term ) :
            return $term->name;
        endif;
    endif;

    return null;
}
