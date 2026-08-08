<?php
/**
 * CM API functions.
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Core\API;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Returns an array with the names of all public used custom post types.
 *
 * @since 3.1.0
 *
 * @return string[]
 */

function get_post_types() {
    return [
        'speaker',
        'partner',
        'session',
        'exhibition_space'
    ];
}
