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



/**
 * Removes unused metabox from edit screens.
 *
 * @since 3.0.0
 */

function remove_unused_meta_boxes() {
    $post_types = [
        'exhibition_space',
        'partner',
        'session',
        'speaker'
    ];

    remove_meta_box( 'slugdiv', $post_types, 'normal' );
    remove_meta_box( 'tagsdiv-event', $post_types, 'normal' );
    remove_meta_box( 'tagsdiv-location', $post_types, 'normal' );
    remove_meta_box( 'tagsdiv-exhibition_package', $post_types, 'normal' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\remove_unused_meta_boxes' );
