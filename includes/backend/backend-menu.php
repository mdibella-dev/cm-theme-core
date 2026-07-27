<?php
/**
 * Functions to setup the congressomat menu
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace CM_Theme\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Creates the Congressomat menu
 *
 * Note: Menu items for post types are created during their registration process.
 *
 * @since 1.1.0
 */

function setup_menu() {
    $admin_menu_slug = 'edit.php?post_type=session';

    add_menu_page(
        __( 'Congressomat', 'congressomat' ),
        __( 'Congressomat', 'congressomat' ),
        'manage_options',
        $admin_menu_slug,
        '',
        'dashicons-groups',
        2,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Events', 'congressomat' ),
        __( 'Events', 'congressomat' ),
        'manage_options',
        'edit-tags.php?taxonomy=event&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Locations', 'congressomat' ),
        __( 'Locations', 'congressomat' ),
        'manage_options',
        'edit-tags.php?taxonomy=location&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Partnership', 'congressomat' ),
        __( 'Partnership', 'congressomat' ),
        'manage_options',
        'edit-tags.php?taxonomy=partnership&post_type=session',
        '',
        0,
    );

    add_submenu_page(
        $admin_menu_slug,
        __( 'Exhibition Packages', 'congressomat' ),
        __( 'Exhibition Packages', 'congressomat' ),
        'manage_options',
        'edit-tags.php?taxonomy=exhibition_package&post_type=session',
        '',
        0,
    );
}

add_action( 'admin_menu', __NAMESPACE__ . '\setup_menu', 999 );



/**
 * Arranges the menu items in the correct order
 *
 * @since 1.0.0
 */

function setup_menu_order( $menu_order ) {

    global $submenu;
           $admin_menu_slug = 'edit.php?post_type=session';
           $sorted          = array();

    $sort_order = array(
        __( 'Events', 'congressomat' ),
        __( 'Sessions', 'congressomat' ),
        __( 'Speakers', 'congressomat' ),
        __( 'Locations', 'congressomat' ),
        __( 'Partners', 'congressomat' ),
        __( 'Partnerships', 'congressomat' ),
        __( 'Exhibition Spaces', 'congressomat' ),
        __( 'Exhibition Packages', 'congressomat' ),
    );

    for ( $i = 0; $i != sizeof( $sort_order ); $i++ ) {
        foreach ( $submenu[ $admin_menu_slug ] as $submenu_item ) {
            if ( $submenu_item[0] == $sort_order[ $i ]) {
                $sorted[] = $submenu_item;
                break;
            }
        }
    }

    $submenu[ $admin_menu_slug ] = $sorted;

    return $menu_order;
}

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', __NAMESPACE__ . '\setup_menu_order' );
