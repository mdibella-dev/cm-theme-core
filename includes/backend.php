<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package ph_PLUGIN-PACKAGE
 */

namespace ph_PLUGIN_NAMESPACE;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since 1.0.0
 */

function admin_enqueue_scripts()
{
    // Do something!
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );
