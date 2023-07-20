<?php
/**
 * Custom post type: speaker ('referent').
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

function post_type_speaker__manage_posts_columns( $default )
{
    $columns['cb']               = $default['cb'];
    $columns['image']            = __( 'Image', 'cm-theme-core' );
    $columns['title']            = __( 'Speaker', 'cm-theme-core' );
    $columns['shortdescription'] = __( 'Short description', 'cm-theme-core' );
    $columns['update']           = __( 'Last updated', 'cm-theme-core' );

    return $columns;
}

add_filter( 'manage_speaker_posts_columns', __NAMESPACE__ . '\post_type_speaker__manage_posts_columns', 10 );



/**
 * Generates the column output.
 *
 * @since 2.5.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the post (aka record) to be output.
 */

function post_type_speaker__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'image':
            if( true === has_post_thumbnail( $post_id ) ) :
                // alternativ: admin_url?
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Edit', 'cm-theme-core' )
                );
            else :
                echo '&mdash;';
            endif;
        break;

        case 'shortdescription':
            echo trim( implode( ' ', array(
                get_field( 'referent-titel', $post_id ),
                get_field( 'referent-vorname', $post_id ),
                get_field( 'referent-nachname', $post_id ),
            ) ) );

            $position = get_field( 'referent-position', $post_id );

            if( ! empty( $position ) ) :
                echo '<br>' . $position;
            endif;
        break;

        case 'update':
            echo sprintf(
                __( '%1$s at %2$s','cm-theme-core' ),
                get_the_modified_date( 'd.m.Y', $post_id ),
                get_the_modified_date( 'H:i', $post_id ),
            );
        break;

    endswitch;
}

add_action( 'manage_speaker_posts_custom_column', __NAMESPACE__ . '\post_type_speaker__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 2.5.0
 *
 * @param array $columns The columns.
 *
 * @return $array An associative array.
 */

function post_type_speaker__manage_sortable_columns( $columns )
{
    $columns['title']  = 'title';
    $columns['update'] = 'update';
    return $columns;
}

add_filter( 'manage_edit-speaker_sortable_columns', __NAMESPACE__ . '\post_type_speaker__manage_sortable_columns' );



/**
 * Produces sorted output.
 *
 * @since 2.5.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function post_type_speaker__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'update':
                $query->set( 'orderby', 'modified' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\post_type_speaker__pre_get_posts', 1 );



function cptui_register_my_cpts() {


    /**
     * Post Type: Referenten.
     */

    $labels = [
        "name" => esc_html__( "Referenten", 'cm-theme-core' ),
        "singular_name" => esc_html__( "Referent", 'cm-theme-core' ),
        "menu_name" => esc_html__( "Referenten", 'cm-theme-core' ),
        "all_items" => esc_html__( "Referenten", 'cm-theme-core' ),
        "add_new" => esc_html__( "Erstellen", 'cm-theme-core' ),
        "search_items" => esc_html__( "Referent suchen", 'cm-theme-core' ),
        "not_found" => esc_html__( "Keine Referenten gefunden", 'cm-theme-core' ),
        "not_found_in_trash" => esc_html__( "Keine Referenten im Papierkorb", 'cm-theme-core' ),
        "featured_image" => esc_html__( "Referentenbild", 'cm-theme-core' ),
        "set_featured_image" => esc_html__( "Referentenbild festlegen", 'cm-theme-core' ),
        "remove_featured_image" => esc_html__( "Referentenbild entfernen", 'cm-theme-core' ),
        "use_featured_image" => esc_html__( "Als Referentenbild verwenden", 'cm-theme-core' ),
        "archives" => esc_html__( "Übersicht aller Referenten", 'cm-theme-core' ),
        "name_admin_bar" => esc_html__( "Referent", 'cm-theme-core' ),
    ];

    $args = [
        "label" => esc_html__( "Referenten", 'cm-theme-core' ),
        "labels" => $labels,
        "description" => "",
        "public" => false,
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
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => true,
        "rewrite" => [ "slug" => "speaker", "with_front" => true ],
        "query_var" => true,
        "menu_position" => 20,
        "supports" => [ "title", "thumbnail", "custom-fields" ],
        "show_in_graphql" => false,
    ];

    register_post_type( "speaker", $args );

}

add_action( 'init', __NAMESPACE__ . '\cptui_register_my_cpts' );
