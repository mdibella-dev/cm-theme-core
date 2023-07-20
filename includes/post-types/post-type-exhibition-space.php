<?php
/**
 * Custom post type: exhibition_space ('Ausstellungsfläche').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Determines the columns of the post list (backend).
 *
 * @since 2.5.0
 *
 * @param array $default The defaults for columns.
 *
 * @return $array An associative array describing the columns to use.
 */

function post_type_exhibition_space__manage_posts_columns( $default )
{
    $columns['cb']                          = $default['cb'];
    $columns['title']                       = __( 'Exhibition space', 'cm-theme-core' );
    $columns['taxonomy-location']           = __( 'Location', 'cm-theme-core' );
    $columns['taxonomy-exhibition_package'] = __( 'Exhibition package', 'cm-theme-core' );
    $columns['update']                      = __( 'Last updated', 'cm-theme-core' );

    return $columns;
}

add_filter( 'manage_exhibition_space_posts_columns', __NAMESPACE__ . '\post_type_exhibition_space__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function post_type_exhibition_space__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'update':
            echo sprintf(
                '%1$s at %2$s Uhr',
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_exhibition_space_posts_custom_column', __NAMESPACE__ . '\post_type_exhibition_space__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array columns The columns.
 *
 * @return $array An associative array.
 */

function post_type_exhibition_space__manage_sortable_columns( $columns )
{
    $columns['title']                       = 'title';
    //$columns['taxonomy-exhibition_package'] = 'taxonomy-exhibition_package';
    $columns['taxonomy-location']           = 'taxonomy-location';
    $columns['update']                      = 'update';
    return $columns;
}

add_filter( 'manage_edit-exhibition_space_sortable_columns', __NAMESPACE__ . '\post_type_exhibition_space__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function post_type_exhibition_space__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'update':
                $query->set( 'orderby', 'modified' );
            break;

            default:
            case '':
                $query->set( 'orderby', 'title' );
                $query->set( 'order', 'asc' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\post_type_exhibition_space__pre_get_posts', 1 );



function cptui_register_my_cpts() {

    /**
     * Post Type: Ausstellungsflächen.
     */

    $labels = [
        "name" => esc_html__( "Ausstellungsflächen", 'cm-theme-core' ),
        "singular_name" => esc_html__( "Ausstellungsfläche", 'cm-theme-core' ),
        "menu_name" => esc_html__( "Ausstellungsflächen", 'cm-theme-core' ),
        "all_items" => esc_html__( "Ausstellungsflächen", 'cm-theme-core' ),
    ];

    $args = [
        "label" => esc_html__( "Ausstellungsflächen", 'cm-theme-core' ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => false,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace" => "wp/v2",
        "has_archive" => false,
        "show_in_menu" => "edit.php?post_type=session",
        "show_in_nav_menus" => false,
        "delete_with_user" => false,
        "exclude_from_search" => true,
        "capability_type" => "page",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => true,
        "rewrite" => [ "slug" => "exhibition_space", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title" ],
        "taxonomies" => [ "location", "exhibition_package" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "exhibition_space", $args );
}

add_action( 'init', __NAMESPACE__ . '\cptui_register_my_cpts' );
