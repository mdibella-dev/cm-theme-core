<?php
namespace Congressomat\Backend;

use \Congressomat as Core;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Prepares the admin pages
 *
 * @since 3.1.0
 */

function current_screen( $screen ) {

    $post_types = [
         'speaker',
         'partner',
         'session',
         'exhibition-space'
    ];

    if ( isset( $screen->post_type ) and in_array( $screen->post_type, $post_types ) ) {
        //add_action( 'in_admin_header', __NAMESPACE__ . '\in_admin_header' );
        add_filter( 'admin_footer_text', __NAMESPACE__ . '\admin_footer_text', 99, 0 );
    }
}

add_action( 'current_screen', __NAMESPACE__ . '\current_screen' );



/**
 * Shows plugin name, version and credits in the footer
 *
 * @since 3.1.0
 */

function admin_footer_text() {
    return sprintf(
        __( '<strong>Congressomat</strong> %1$s | Made by %2$s', 'congressomat' ),
        Core\PLUGIN_VERSION,
        '<a href="https://www.marcodibella.de" target="_blank">Marco Di Bella</a>'
    );
}
