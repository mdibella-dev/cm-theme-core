<?php
/**
 * Functions to modify taxonomy and post type overview pages
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Hides various columns in the admin overview by default.
 *
 * @since 1.0.0
 */

function default_hidden_columns( $hidden, $screen ) {

    if ( isset( $screen->id ) ) {
        switch ( $screen->id ) {

            case 'edit-event':
                $hidden[] = 'slug' ;
                break;

            case 'edit-location':
            case 'edit-partnership':
            case 'edit-exhibition_package':
                $hidden[] = 'description';
                $hidden[] = 'slug';
                break;
        }
    }

    return $hidden;
}

add_filter( 'default_hidden_columns', __NAMESPACE__ . '\default_hidden_columns', 10, 2 );



/**
 * Generates customized page titles in the admin overview.
 *
 * @since 1.0.0
 *
 * @see https://stackoverflow.com/questions/22261284/add-button-link-immediately-after-title-to-custom-post-type-edit-screen
 */

function rewrite_header() {

    $screen    = get_current_screen();
    $do_modify = false;
    $term      = false;

    if ( isset( $_GET['post_type'] ) and isset( $screen->id ) ) {

        switch( $screen->id ) {

            case 'edit-session':  // event // location
                if ( isset( $_GET['location'] ) ) {
                    $term = get_term_by( 'slug', $_GET['location'], 'location' );
                } elseif( isset( $_GET['event'] ) ) {
                    $term = get_term_by( 'slug', $_GET['event'], 'event' );
                }

                if ( false !== $term ) {
                    $do_modify = true;
                    $title     = __( 'Sessions', 'congressomat' );
                    $subtitle  = $term->name;
                    $add_new   = __( 'Add New Session', 'congressomat' );
                }
                break;

            case 'edit-partner':
                if ( isset( $_GET['partnership'] ) ) {
                    $term = get_term_by( 'slug', $_GET['partnership'], 'partnership' );
                }

                if ( false !== $term ) {
                    $do_modify = true;
                    $title     = __( 'Partners', 'congressomat' );
                    $subtitle  = $term->name;
                    $add_new   = __( 'Add New Exhibitor', 'congressomat' );
                }
                break;

            case 'edit-exhibition_space':
                if ( isset( $_GET['location'] ) ) {
                    $term = get_term_by( 'slug', $_GET['location'], 'location' );
                } elseif ( isset( $_GET['exhibition_package'] ) ) {
                    $term = get_term_by( 'slug', $_GET['exhibition_package'], 'exhibition_package' );
                }

                if ( false !== $term ) {
                    $do_modify = true;
                    $title     = __( 'Booth', 'congressomat' );
                    $subtitle  = $term->name;
                    $add_new   = __( 'Add New Booth', 'congressomat' );
                }
                break;
        }
    }

    if ( $do_modify ) {
     ?>
<div class="wrap">
    <h1 class="wp-heading-inline show" style="display:inline-block;"><?php echo $title . ' (' . $subtitle . ')';?></h1>
     <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . $_GET['post_type'] ) ); ?>" class="page-title-action show"><?php echo $add_new;?></a>
</div>
<style id="modify">
    .wp-heading-inline:not(.show),.page-title-action:not(.show){display:none!important;}
</style>
<?php
    }
 }

 add_action( 'admin_notices', __NAMESPACE__ . '\rewrite_header' );



 /**
  * Remove months dropdown
  *
  * @since 3.0.0
  *
  * @see https://developer.wordpress.org/reference/hooks/disable_months_dropdown/
  */

 function disable_months_dropdown( $disable, $type ) {
    $post_types = [
          'speaker',
          'partner',
          'session',
          'exhibition_space'
    ];

    if ( in_array( $type, $post_types ) ) {
        $disable = true;
    }

     return $disable;
}

add_filter( 'disable_months_dropdown', __NAMESPACE__ . '\disable_months_dropdown', 10, 2 );



/**
 * Remove view link in row actions
 *
 * @since 3.1.0
 *
 * @see https://developer.wordpress.org/reference/hooks/post_row_actions/
 */

function modify_list_row_actions( $actions, $post ) {
    $post_types = [
        'speaker',
        'partner',
        'session',
        'exhibition_space'
    ];

    if ( in_array( $post->post_type, $post_types ) ) {
        unset( $actions['view'] );
    }
    return $actions;
}

add_filter( 'post_row_actions', __NAMESPACE__ . '\modify_list_row_actions', 10, 2 );
