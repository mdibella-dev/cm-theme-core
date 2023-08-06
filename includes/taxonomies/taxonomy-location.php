<?php
/**
 * Custom taxonomy: location ('Örtlichkeiten').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\taxonomies\location;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Specifies the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function manage_edit_columns( $default )
{
    $columns = [
        'cb'            => $default['cb'],
        'id'            => 'ID',
        'image'         => __( 'Image', 'cm-theme-core' ),
        'name'          => $default['name'],
        'description'   => $default['description'],
        'slug'          => $default['slug'],
        'count-session' => __( 'Sessions', 'cm-theme-core' ),
        'count-space'   => __( 'Exhibition spaces', 'cm-theme-core' ),
    ];
    return $columns;
}
add_filter( 'manage_edit-location_columns', __NAMESPACE__ . '\manage_edit_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function manage_custom_column( $content, $column_name, $term_id )
{
    switch( $column_name ) :
        case 'id':
            $content = $term_id;
        break;

        case 'image':
            $image_id = get_field( 'location-image', 'location_' . $term_id );
            $image    = wp_get_attachment_image( $image_id, [ '150', '9999' ] );

            if( ! empty( $image ) ) :
                echo $image;
            else :
                echo '&mdash;';
            endif;
        break;

        case 'count-session':
            $posts = get_posts( [
                'post_type'   => 'session',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'location',
                    'terms'    => $term_id,
                ]],
            ] );
            $term    = get_term( $term_id, 'location' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?location=%2$s&post_type=session" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'View all sessions at this location', 'cm-theme-core' )
            );
        break;

        case 'count-space':
            $posts = get_posts( [
                'post_type'   => 'exhibition_space',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'location',
                    'terms'    => $term_id,
                ]],
            ] );
            $term    = get_term( $term_id, 'location' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?location=%2$s&post_type=exhibition_space" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'View all exhibition spaces in this location', 'cm-theme-core' )
            );
        break;

        default:
        break;
    endswitch;

    return $content;
}
add_filter( 'manage_location_custom_column', __NAMESPACE__ . '\manage_custom_column', 10, 3 );



/**
 * Registers the location taxonomy.
 *
 * @since 1.0.0
 */

function register()
{

    $labels = [
        'name'          => __( 'Locations', 'cm-theme-core' ),
        'singular_name' => __( 'Location', 'cm-theme-core' ),
        'menu_name'     => __( 'Locations', 'cm-theme-core' ),
    ];


    $args = [
        'label'                 => __( 'Locations', 'cm-theme-core' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'location',
            'with_front' => true,
        ],
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'location',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'location', ['session'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
