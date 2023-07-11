<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since 1.0.0
 *
 * @param string $hook The current page in the backend.
 */

function admin_enqueue_scripts( $hook )
{
    wp_enqueue_style(
        'cm-theme-core-backend-style',
        PLUGIN_DIR . '/assets/build/css/backend.min.css',
        array(),
        PLUGIN_VERSION
    );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );



/**
 * Creates the CM menu.
 * Note: Menu items for posttypes are created when they are registered.
 *
 * @since 1.1.0
 */

function admin_menu()
{
    $admin_menu_slug = 'edit.php?post_type=session';

    add_menu_page(
        __( 'Congress-Management', 'cm-theme-core' ),
        __( 'Congress-Management', 'cm-theme-core' ),
        'manage_options',
        $admin_menu_slug,
        '',
        'dashicons-groups',
        20,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Veranstaltungen', 'cm-theme-core' ),
        __( 'Veranstaltungen', 'cm-theme-core' ),
        'manage_options',
        'edit-tags.php?taxonomy=event&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Örtlichkeiten', 'cm-theme-core' ),
        __( 'Örtlichkeiten', 'cm-theme-core' ),
        'manage_options',
        'edit-tags.php?taxonomy=location&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Kooperationsformen', 'cm-theme-core' ),
        __( 'Kooperationsformen', 'cm-theme-core' ),
        'manage_options',
        'edit-tags.php?taxonomy=partnership&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Ausstellungspakete', 'cm-theme-core' ),
        __( 'Ausstellungspakete', 'cm-theme-core' ),
        'manage_options',
        'edit-tags.php?taxonomy=exhibition_package&post_type=session',
        '',
        0,
    );
}

add_action( 'admin_menu', __NAMESPACE__ . '\admin_menu', 999 );



/**
 * Sorts the Congressomat menu.
 *
 * @since 2.3.0
 */

function admin_menu_order( $menu_order )
{
    global $submenu;
           $admin_menu_slug = 'edit.php?post_type=session';
           $sorted          = array();

    $sort_order = array(
        'Veranstaltungen',
        'Örtlichkeiten',
        'Referenten',
        'Programmpunkte',
        'Kooperationspartner',
        'Kooperationsformen',
        'Ausstellungsflächen',
        'Ausstellungspakete',
    );

    for( $i = 0; $i != sizeof( $sort_order ); $i++ ) :
        foreach( $submenu[ $admin_menu_slug ] as $submenu_item ) :
            if( $submenu_item[0] == $sort_order[ $i ]) :
                $sorted[] = $submenu_item;
                break;
            endif;
        endforeach;
    endfor;

    $submenu[ $admin_menu_slug ] = $sorted;

    return $menu_order;
}

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', __NAMESPACE__ . '\admin_menu_order' );



/**
 * Adds a JS script to:
 * - move various standard WordPress input fields to a new mask (created with ACF),
 *
 * @since 2.0.0
 *
 * @see http://www.advancedcustomfields.com/resources/moving-wp-elements-content-editor-within-acf-fields/
 */

function adjust_acf_dialog()
{
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
 * Hides various columns in the admin overview by default.
 *
 * @since 2.5.0
 */

function default_hidden_columns( $hidden, $screen )
{
    if( isset( $screen->id ) ) :
        switch( $screen->id ) :

            case 'edit-event' :
                $hidden[] = 'slug' ;
            break;

            case 'edit-location' :
            case 'edit-partnership' :
            case 'edit-exhibition_package' :
                $hidden[] = 'description';
                $hidden[] = 'slug';
            break;

        endswitch;
    endif;

    return $hidden;
}

add_filter( 'default_hidden_columns', __NAMESPACE__ . '\default_hidden_columns', 10, 2 );



/**
 * Generates customized page titles in the admin overview.
 *
 * @since 2.5.0
 *
 * @see https://stackoverflow.com/questions/22261284/add-button-link-immediately-after-title-to-custom-post-type-edit-screen
 */

function rewrite_header()
{
    $screen    = get_current_screen();
    $do_modify = false;
    $term      = false;

    if( isset( $_GET['post_type'] ) and isset( $screen->id ) ) :
        switch( $screen->id ) :
            case 'edit-session':  // event // location
                if( isset( $_GET['location'] ) ) :
                    $term = get_term_by( 'slug', $_GET['location'], 'location' );
                elseif( isset( $_GET['event'] ) ) :
                    $term = get_term_by( 'slug', $_GET['event'], 'event' );
                endif;

                if( false !== $term ) :
                    $do_modify = true;
                    $title     = __( 'Programmpunkte', 'cm-theme-core' );
                    $subtitle  = $term->name;
                endif;
            break;

            case 'edit-partner':
                if( isset( $_GET['partnership'] ) ) :
                    $term = get_term_by( 'slug', $_GET['partnership'], 'partnership' );
                endif;

                if( false !== $term ) :
                    $do_modify = true;
                    $title     = __( 'Kooperationspartner', 'cm-theme-core' );
                    $subtitle  = $term->name;
                endif;
            break;

            case 'edit-exhibition_space':
                if( isset( $_GET['location'] ) ) :
                    $term = get_term_by( 'slug', $_GET['location'], 'location' );
                elseif( isset( $_GET['exhibition_package'] ) ) :
                    $term = get_term_by( 'slug', $_GET['exhibition_package'], 'exhibition_package' );
                endif;

                if( false !== $term ) :
                    $do_modify = true;
                    $title     = __( 'Ausstellungsflächen', 'cm-theme-core' );
                    $subtitle  = $term->name;
                endif;
            break;

        endswitch;
    endif;

    if( $do_modify ) :
     ?>
<div class="wrap">
    <h1 class="wp-heading-inline show" style="display:inline-block;"><?php echo $title . ' (' . $subtitle . ')';?></h1>
     <a href="<?php echo admin_url( 'post-new.php?post_type=' . $_GET['post_type'] ); ?>" class="page-title-action show"><?php echo __( 'Erstellen', 'cm-theme-core' );?></a>
</div>
<style id="modify">
    .wp-heading-inline:not(.show), .page-title-action:not(.show) { display:none !important;}
</style>
<?php
    endif;
 }

 add_action( 'admin_notices', __NAMESPACE__ . '\rewrite_header' );
