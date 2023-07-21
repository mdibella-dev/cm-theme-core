<?php
/**
 * CM core functions.
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Returns the dataset of a specific speaker.
 *
 * @since 1.0.0
 *
 * @param int $speaker
 *
 * @return array
 */

function get_speaker_dataset( $speaker )
{
    $speaker_post = get_post( $speaker );

    $data['id']          = $speaker;
    $data['firstname']   = get_field( 'referent-vorname', $speaker_post );
    $data['lastname']    = get_field( 'referent-nachname', $speaker_post );
    $data['name']        = trim( sprintf( '%1$s %2$s', $data['firstname'], $data['lastname'] ) );
    $data['title_name']  = trim( sprintf( '%1$s %2$s', get_field( 'referent-titel', $speaker_post ), $data['name'] ) );
    $data['position']    = get_field( 'referent-position', $speaker_post );
    $data['description'] = get_field( 'referent-beschreibung', $speaker_post );
    $data['permalink']   = get_post_permalink( $speaker_post );

    return $data;
}



/**
 * Sorts a list of speaker datasets by first and last name.
 *
 * @since 1.0.0
 *
 * @param array $speaker_list The unsorted list.
 *
 * @return array The sorted list.
 */

function sort_speaker_datasets( $speaker_list )
{
    foreach( $speaker_list as $key => $row ) :
        $forename[$key] = $row['firstname'];
        $lastname[$key] = $row['lastname'];
    endforeach;

    array_multisort( $lastname, SORT_ASC, SORT_STRING, $forename, SORT_ASC, SORT_STRING, $speaker_list );

    return $speaker_list;
}
