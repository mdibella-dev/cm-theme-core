<?php
/**
 * Custom post type: session ('Programmpunkte').
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

function post_type_session__manage_posts_columns( $default )
{
    $columns['cb']                = $default['cb'];
    $columns['title']             = $default['title'];
    $columns['taxonomy-event']    = __( 'Event', 'cm-theme-core' );
    $columns['taxonomy-location'] = __( 'Location', 'cm-theme-core' );
    $columns['event-date']        = __( 'Date', 'cm-theme-core' );
    $columns['event-time']        = __( 'Time period', 'cm-theme-core' );
    $columns['speaker']           = __( 'Speakers', 'cm-theme-core' );
    $columns['update']            = __( 'Last updated', 'cm-theme-core' );

    return $columns;
}

add_filter( 'manage_session_posts_columns', __NAMESPACE__ . '\post_type_session__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function post_type_session__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'speaker':
            $speakers = get_field( 'programmpunkt-referenten', $post_id );

            if( null != $speakers ) :

                foreach( $speakers as $speaker ) :
                    $speaker_dataset = core__get_speaker_dataset( $speaker );
                    echo sprintf(
                        '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                        $speaker_dataset[ 'id' ],
                        get_the_post_thumbnail( $speaker_dataset[ 'id' ], array( 100, 0 ) ),
                        sprintf(
                            __( 'Edit %1$s', 'cm-theme-core' ),
                            $speaker_dataset[ 'name' ],
                        ),
                    );
                endforeach;
            else :
                echo '-';
            endif;
        break;

        case 'event-date':
            echo get_field( 'programmpunkt-datum', $post_id );
        break;

        case 'event-time':
            $time = get_field( 'programmpunkt-alternative-zeitangabe', $post_id );

            if( empty( $time ) ) :
                $time = sprintf(
                    'from %1$s to %2$s',
                    get_field( 'programmpunkt-von', $post_id ),
                    get_field( 'programmpunkt-bis', $post_id )
                );
            endif;

            echo $time;
        break;

        case 'update':
            echo sprintf(
                __( '%1$s at %2$s', 'cm-theme-core' ),
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_session_posts_custom_column', __NAMESPACE__ . '\post_type_session__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array $columns The columns.
 *
 * @return $array An associative array.
 */

function post_type_session__manage_sortable_columns( $columns )
{
    $columns['title']             = 'title';
    $columns['taxonomy-event']    = 'taxonomy-event';
    $columns['taxonomy-location'] = 'taxonomy-location';
    $columns['event-date']        = 'event-date';
    $columns['update']            = 'update';
    return $columns;
}

add_filter( 'manage_edit-session_sortable_columns', __NAMESPACE__ . '\post_type_session__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function post_type_session__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'event-date':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'programmpunkt-datum' );
            break;

            case 'update':
                $query->set( 'orderby', 'modified' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\post_type_session__pre_get_posts', 1 );



function cptui_register_my_cpts() {

    /**
     * Post Type: Programmpunkte.
     */

    $labels = [
        "name" => esc_html__( "Programmpunkte", 'cm-theme-core' ),
        "singular_name" => esc_html__( "Programmpunkt", 'cm-theme-core' ),
        "menu_name" => esc_html__( "Programm", 'cm-theme-core' ),
        "all_items" => esc_html__( "Programmpunkte", 'cm-theme-core' ),
        "add_new" => esc_html__( "Erstellen", 'cm-theme-core' ),
        "add_new_item" => esc_html__( "Neuen Programmpunkt erstellen", 'cm-theme-core' ),
        "name_admin_bar" => esc_html__( "Programmpunkt", 'cm-theme-core' ),
    ];

    $args = [
        "label" => esc_html__( "Programmpunkte", 'cm-theme-core' ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace" => "wp/v2",
        "has_archive" => false,
        "show_in_menu" => "edit.php?post_type=session",
        "show_in_nav_menus" => false,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "page",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => true,
        "rewrite" => [ "slug" => "session", "with_front" => true ],
        "query_var" => true,
        "menu_position" => 20,
        "supports" => [ "title", "thumbnail" ],
        "taxonomies" => [ "location", "event" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "session", $args );
}

add_action( 'init', __NAMESPACE__ . '\cptui_register_my_cpts' );
