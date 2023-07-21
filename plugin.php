<?php
/**
 * Plugin Name:         CM Theme &mdash; Core
 * Plugin URI:          https://github.com/mdibella-dev/cm-theme-core
 * Description:         Core functions of CM Theme. Originally an integral part of the theme, now outsourced in a plugin.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   6.2
 * Tested up to:        6.2
 * Requires PHP:        7
 * Version:             1.0.0
 * Text Domain:         cm-theme-core
 * Domain Path:         /languages
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions */

define( __NAMESPACE__ . '\PLUGIN_VERSION', '1.0.0' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );


/** Definitions (global scope) */

// Preconfigured sets for event tables;
// a = left column with place and time information; b = middle column with title etc.

define( 'EVENT_TABLE_SETLIST', [
    '1' => [
        'a' => 'session-date,session-time-range,session-location',
        'b' => 'session-title,session-subtitle',
    ],
    '2' => [
        'a' => 'session-time-begin,session-location',
        'b' => 'session-title,session-subtitle,session-speaker',
    ],
    '3' => [
        'a' => 'session-time-range',
        'b' => 'session-title,session-subtitle,session-speaker',
    ],
    '4' => [
        'a' => 'session-date,session-time-range',
        'b' => 'session-title,session-subtitle',
    ],
] );


/** Include files */

require_once( PLUGIN_DIR . 'includes/setup.php' );
require_once( PLUGIN_DIR . 'includes/backend.php' );
require_once( PLUGIN_DIR . 'includes/block-editor.php' );

require_once( PLUGIN_DIR . 'includes/core/index.php' );
require_once( PLUGIN_DIR . 'includes/post-types/index.php' );
require_once( PLUGIN_DIR . 'includes/taxonomies/index.php' );
require_once( PLUGIN_DIR . 'includes/shortcodes/index.php' );
