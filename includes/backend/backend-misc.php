<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Backend;

use \Congressomat as Core;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since 1.0.0
 *
 * @param string $hook The current page in the backend.
 */

function admin_enqueue_scripts( $hook ) {
    wp_enqueue_style(
        'cm-theme-core-backend-style',
        esc_url( plugins_url( 'congressomat/assets/build/css/backend.min.css' ) ),
        [],
        Core\PLUGIN_VERSION
    );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );




/**
 * Adds a JS script to:
 * - move various standard WordPress input fields to a new mask (created with ACF),
 *
 * @since 2.0.0
 *
 * @see http://www.advancedcustomfields.com/resources/moving-wp-elements-content-editor-within-acf-fields/
 */

function adjust_acf_dialog() {
?>
<script type="text/javascript">
    (function($) {
        $(document).ready(function(){
<?php /* -- CPT Session -- */ ?>
            $( '.acf-field-5d81eec13261d .acf-input' ).append( $( '#title' ) );
            $( '#title-prompt-text' ).remove();
        });
    })(jQuery);
</script>
<?php
}

add_action( 'acf/input/admin_head', __NAMESPACE__ . '\adjust_acf_dialog' );




/**
 * Adds a CSS class to certain admin pages to show the presence of this plugin.
 *
 * @since 3.0.0
 */

function modify_admin_body_classes( $classes ) {

    if ( is_admin() ) {
        $current_screen = get_current_screen();
        $screens = [
            'session',
            'speaker',
            'partner',
            'exhibition_space',
            'edit-session',
            'edit-speaker',
            'edit-partner',
            'edit-exhibition_space',
            'edit-event',
            'edit-location',
            'edit-exhibition_package',
            'edit-partnership'
        ];

        if ( in_array( $current_screen->id, $screens ) ) {
            $classes .= ' congressomat';
        }
    }

    return $classes;
}

add_filter( 'admin_body_class', __NAMESPACE__ . '\modify_admin_body_classes' );
