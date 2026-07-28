<?php
/**
 * Functions to setup the congressomat menu
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Creates the CM menu.
 * Note: Menu items for posttypes are created when they are registered.
 *
 * @since 3.0.0
 */

function hide_publishing_actions() {
    global $post;

    $post_types = [
         'speaker',
         'partner',
         'session',
         'exhibition-space'
    ];

    if ( in_array( $post->post_type, $post_types ) ) {
        echo '
            <style type="text/css">
            #edit-slug-box, #minor-publishing-actions { display:none; }
            </style>
        ';
    }
}

add_action( 'admin_head-post.php', __NAMESPACE__ . '\hide_publishing_actions' );
add_action( 'admin_head-post-new.php', __NAMESPACE__ . '\hide_publishing_actions' );
