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
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );



/** Include files */

require_once PLUGIN_DIR . 'vendor/autoload.php';

require_once PLUGIN_DIR . 'includes/core/index.php';
require_once PLUGIN_DIR . 'includes/backend/index.php';
require_once PLUGIN_DIR . 'includes/shortcodes/index.php';
require_once PLUGIN_DIR . 'includes/third-party/index.php';



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init() {
    // Load text domain, use relative path to the plugin's language folder
    load_plugin_textdomain( 'congressomat', false, plugin_basename( PLUGIN_DIR ) . '/languages' );
}

add_action( 'init', __NAMESPACE__ . '\plugin_init' );
