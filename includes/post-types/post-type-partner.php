<?php
/**
 * Custom post type: partner ('Kongresspartner').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\post_types\partner;

use \cm_theme_core\api as api;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Determines the columns of the post list (backend).
 *
 * @since 1.0.0
 *
 * @param array $default The defaults for columns.
 *
 * @return $array An associative array describing the columns to use.
 */

function manage_posts_columns( $default ) {

    $columns['cb']                   = $default['cb'];
    $columns['image']                = __( 'Image', 'cm-theme-core' );
    $columns['title']                = __( 'Partner', 'cm-theme-core' );
    $columns['taxonomy-partnership'] = __( 'Partnership', 'cm-theme-core' );
    $columns['exhibition']           = __( 'Exhibition space', 'cm-theme-core' );
    $columns['update']               = __( 'Last updated', 'cm-theme-core' );

    return $columns;
}

add_filter( 'manage_partner_posts_columns', __NAMESPACE__ . '\manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 1.0.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function manage_posts_custom_column( $column_name, $post_id ) {

    switch ( $column_name ) {
        case 'image' :
            if ( true === has_post_thumbnail( $post_id ) ) {
                // alternatively: admin_url?
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, [ 100, 0 ] ),
                    __( 'Edit', 'cm-theme-core' )
                );
            } else {
                echo '&mdash;';
            }
            break;

        case 'exhibition' :
            $data = api\get_partner_dataset( $post_id );

            if ( ! empty( $data['exhibition-spaces'] ) ) {
                $spaces = [];

                foreach ( $data['exhibition-spaces'] as $space ) {
                    if ( ! empty( $space['location'] ) and ! empty( $space['signature'] ) ) {
                        $spaces[] = sprintf(
                            '<a href="post.php?post=%1$s&action=edit">%2$s</a>%3$s',
                            $space['id'],
                            $space['signature'],
                            ( ! empty( $space['package'] ) )? ' (' . $space['package'] . ')' : '',
                        );
                    }
                }

                if ( ! empty( $spaces ) ) {
                    echo implode( ', ', $spaces );
                } else {
                    echo '&mdash;';
                }
            } else {
                echo '&mdash;';
            }
            break;

        case 'update' :
            echo sprintf(
                __( '%1$s at %2$s', 'cm-theme-core' ),
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
            break;
    }
}

add_action( 'manage_partner_posts_custom_column', __NAMESPACE__ . '\manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 1.0.0
 *
 * @param array  $columns The columns.
 *
 * @return $array An associative array.
 */

function manage_sortable_columns( $columns ) {

    $columns['title']  = 'title';
    $columns['update'] = 'update';

    return $columns;
}

add_filter( 'manage_edit-partner_sortable_columns', __NAMESPACE__ . '\manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 1.0.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function pre_get_posts( $query ) {

    if ( $query->is_main_query() and is_admin() ) {

        $orderby = $query->get( 'orderby' );

        switch ( $orderby ) {
            case 'update' :
                $query->set( 'orderby', 'modified' );
                break;
        }

    }
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts', 1 );



/**
 * Registers the custom post type.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Partners', 'cm-theme-core' ),
        'singular_name' => __( 'Partner', 'cm-theme-core' ),
        'menu_name'     => __( 'Partnerships', 'cm-theme-core' ),
        'all_items'     => __( 'Partners', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Partner', 'cm-theme-core' ),
        'labels'                => $labels,
        'description'           => '',
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_rest'          => true,
        'rest_base'             => '',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_namespace'        => 'wp/v2',
        'has_archive'           => false,
        'show_in_menu'          => 'edit.php?post_type=session',
        'show_in_nav_menus'     => true,
        'delete_with_user'      => false,
        'exclude_from_search'   => false,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => false,
        'rewrite'               => [
            'slug'       => 'partner',
            'with_front' => true
        ],
        'query_var'             => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'supports'              => [
            'title',
            'thumbnail'
        ],
        'taxonomies'            => [
            'partnership'
        ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'partner', $args );

}

add_action( 'init', __NAMESPACE__ . '\register' );
