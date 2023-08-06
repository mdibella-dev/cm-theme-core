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
 * Determines the name of a location.
 *
 * @since 1.0.0
 *
 * @param int $location
 *
 * @return string
 */

function get_location( $location ) {
    
    if( ! empty( $location ) ) :
        $term = get_term_by( 'term_taxonomy_id', $location, 'location' );

        if( false != $term ) :
            return $term->name;
        endif;

    endif;

    return null;
}
