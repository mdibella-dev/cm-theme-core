<?php
/**
 * Plugin Name:         Congressomat
 * Plugin URI:          https://github.com/mdibella-dev/congressomat
 * Description:         A simple event managing tool.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   6
 * Tested up to:        7.0
 * Requires PHP:        7
 * Version:             3.1.0-develop
 * Text Domain:         congressomat
 * Domain Path:         /languages
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions */

define( __NAMESPACE__ . '\PLUGIN_VERSION', '3.1.0-develop' );



/** Include files */

require_once 'vendor/autoload.php';

require_once 'includes/core/index.php';
require_once 'includes/backend/index.php';
require_once 'includes/shortcodes/index.php';
require_once 'includes/third-party/index.php';



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init() {
    // Load text domain, use relative path to the plugin's language folder
    load_plugin_textdomain( 'congressomat', false, plugin_basename( __FILE__ ) . '/languages' );
}

add_action( 'init', __NAMESPACE__ . '\plugin_init' );
