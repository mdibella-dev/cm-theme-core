<?php
/**
 * CM core functions.
 *
 * @author  Marco Di Bella
 * @package cm
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Returns an array with sessions.
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return array
 */

function cm_get_sessions( $args )
{
    // Determination of the passed parameters
    $default_args = array(
        'event'          => '',
        'event_filter'   => 'ACTIVE',
        'speaker'        => '',
        'posts_per_page' => -1,
        'date'           => '',
    );
    extract( wp_parse_args( $args, $default_args ) );


    // Data query construction
    $query = array(
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'post_type'      => 'session',
    );


    // Handling event/event_filter
    // Adds either the search for the sessions of a specific event (variant 1)
    // or filtering by active or inactive sessions (variant 2).
    if( null !== cm_get_event( $event ) ) :

        $query[ 'tax_query' ] = array( array(
            'taxonomy' => 'event',
            'field'    => 'term_id',
            'terms'    => $event,
        ) );
    else :
        $event_list   = cm_get_active_events();
        $event_filter = strtoupper( trim( $event_filter ) );

        if( 'INACTIVE' === $event_filter ) :

            $query['tax_query'] = array( array(
                'taxonomy' => 'event',
                'field'    => 'term_id',
                'terms'    => $event_list,
                'operator' => 'NOT IN',
            ) );
        elseif( 'ACTIVE' === $event_filter ) :

            $query['tax_query'] = array( array(
                'taxonomy' => 'event',
                'field'    => 'term_id',
                'terms'    => $event_list,
                'operator' => 'IN',
            ) );
        endif;

    endif;


    // Handling of speaker/date
    // Adds the search for the sessions of a specific speaker and/or the search for the session taking place on a specific date.
    if( ! empty( $speaker ) or ! empty( $date ) ) :
        $query['meta_query'] = array();

        if( ! empty( $speaker ) and is_numeric( $speaker ) ) :

            $query['meta_query'][] = array(
                'key'     => 'programmpunkt-referenten',
                'value'   => $speaker,
                'compare' => 'LIKE',
            );

        endif;

        if( ! empty( $date ) ) :

            // @see: https://www.php.net/manual/de/function.strtotime.php#122937/
            $date = str_replace( '.', '-', $date );

            if( false !== ( $timestamp = strtotime( $date) ) ) :
                $query['meta_query'][] = array(
                    'key'   => 'programmpunkt-datum',
                    'value' => date( 'Ymd', $timestamp ),
                );
            endif;

        endif;

    endif;


    // Execution of the data query and return of the sorted result
    $sessions = get_posts( $query );
    return cm_sort_sessions_by_timestamp( $sessions );
}



/**
 * Returns the sessions belonging to a specific event.
 *
 * @since 1.0.0
 *
 * @param int $event
 *
 * @return array
 */

function cm_get_sessions_by_event( $event, $date = '' )
{
    return cm_get_sessions( array(
        'event' => $event,
        'date'  => $date,
    ) );
}



/**
 * Delivers the sessions belonging to a specific speaker.
 * It can be filtered by active, inactive or all sessions.
 *
 * @since 1.0.0
 *
 * @param int    $speaker
 * @param string $event_filter
 *
 * @return array
 */

function cm_get_sessions_by_speaker( $speaker, $event_filter = 'ACTIVE' )
{
    return cm_get_sessions( array(
        'speaker'      => $speaker,
        'event_filter' => $event_filter,
    ) );
}



/**
 * Sorts an array of sessions in ascending order by timestamp.
 *
 * @since 1.0.0
 *
 * @param  array $sessions
 *
 * @return array
 */

function cm_sort_sessions_by_timestamp( $sessions )
{
    if( true == is_array( $sessions ) ) :
        $unable_to_sort = false;
        $sort           = array();

        // Creation of a sortable array
        foreach( $sessions as $session ) :

            // Generation of the necessary time stamps ('from', to')
            $timestamp_from = strtotime(
                get_field( 'programmpunkt-datum', $session->ID )
                . ' ' .
                get_field( 'programmpunkt-von', $session->ID )
            );

            $timestamp_to = strtotime(
                get_field( 'programmpunkt-datum', $session->ID )
                . ' ' .
                get_field( 'programmpunkt-bis', $session->ID )
            );


            // Add the session to the sort array if 'from' timestamps (1st priority) or 'to' timestamps (2nd priority) are present.
            // Otherwise abort, because sorting is not possible.
            if( false !== $timestamp_from ) :
                $sort[ $timestamp_from ] = $session;
            elseif ( false !== $timestamp_to ) :
                $sort[ $timestamp_to ] = $session;
            else :
                $unable_to_sort = true;
                break;
            endif;

        endforeach;


        // Implementation of the sorting (if possible)
        if( false === $unable_to_sort ) :
            ksort( $sort );
            $sessions = array_values( $sort );
        endif;

    endif;

    return $sessions;
}
