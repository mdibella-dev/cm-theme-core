<?php
/**
 * Functions to setup the congressomat menu
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Backend;

use \Congressomat\Core\API as API;



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

    if ( in_array( $post->post_type, API\get_post_types() ) ) {
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
    remove_meta_box( 'slugdiv', API\get_post_types(), 'normal' );
    remove_meta_box( 'tagsdiv-event', API\get_post_types(), 'normal' );
    remove_meta_box( 'tagsdiv-location', API\get_post_types(), 'normal' );
    remove_meta_box( 'tagsdiv-exhibition_package', API\get_post_types(), 'normal' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\remove_unused_meta_boxes' );



/**
 * Creates a sortable value for the session post type
 *
 * @since 3.0.0
 */

function make_it_sortable( $post_id, $post, $update ) {
    $date  = get_field( 'programmpunkt-datum', $post_id );
    $begin = get_field( 'programmpunkt-von', $post_id );
    $time  = '';

    if ( !empty( $date ) and !empty( $begin ) ) {
        $time = strtotime( $date . ' ' . $begin );
    }

    add_post_meta( $post_id, 'CONGRESSOMAT_SESSION_DATE_SORTKEY', $time );
}

add_action( 'save_post_session', __NAMESPACE__ . '\make_it_sortable', 10, 3 );
