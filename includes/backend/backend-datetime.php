<?php
/**
 * Functions to handle date and time
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;




/**
  * Echo the modified date time of a post object in a more human form
  *
  * @since 3.0.0
  */

function show_modified_date( $post_id ) {

    $origin = new \DateTimeImmutable( get_the_modified_date( 'd.m.Y H:i', $post_id ), wp_timezone() );
    $target = new \DateTimeImmutable( 'now', wp_timezone() );

    $interval = $origin->diff( $target );

    if ( $interval->y >= 1 ) {
        echo sprintf( _n( '%s year ago', '%s years ago', $interval->y, 'congressomat' ), $interval->y );
    } elseif ( $interval->m >= 1 ) {
        echo sprintf( _n( '%s month ago', '%s months ago', $interval->m, 'congressomat' ), $interval->m );
    } elseif ( $interval->d >= 1 ) {
        echo sprintf( _n( '%s day ago', '%s days ago', $interval->d, 'congressomat' ), $interval->d );
    } elseif ( $interval->h >= 1 ) {
        echo sprintf( _n( '%s hour ago', '%s hours ago', $interval->h, 'congressomat' ), $interval->h );
    } elseif ( $interval->i >= 1 ) {
        echo sprintf( _n( '%s minute ago', '%s minutes ago', $interval->i, 'congressomat' ), $interval->i );
    } else {
        echo __( 'just now', 'congressomat' );
    }

}
