<?php
/**
 * Custom taxonomy: partnership ('Kooperationsformen').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\taxonomies\partnership;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Specifies the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function manage_edit_columns( $default ) {

    $columns = [
        'cb'          => $default['cb'],
        'id'          => 'ID',
        'name'        => $default['name'],
        'description' => $default['description'],
        'slug'        => $default['slug'],
        'count'       => __( 'Count', 'cm-theme-core' ),
    ];

    return $columns;
}

add_filter( 'manage_edit-partnership_columns', __NAMESPACE__ . '\manage_edit_columns' );



/**
 * Determines the content of the columns in the taxonomy list.
 *
 * @since 1.0.0
 */

function manage_custom_column( $content, $column_name, $term_id ) {

    switch( $column_name ) :
        case 'id':
            $content = $term_id;
        break;

        case 'count':
            $posts = get_posts( [
                'post_type'   => 'partner',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'partnership',
                    'terms'    => $term_id,
                ]],
            ] );
            $term    = get_term( $term_id, 'partnership' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?partnership=%2$s&post_type=partner" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'Show all partners cooperating in this way', 'cm-theme-core' )
            );
        break;

        default:
        break;
    endswitch;

    return $content;
}

add_filter( 'manage_partnership_custom_column', __NAMESPACE__ . '\manage_custom_column', 10, 3 );



/**
 * Registers the partnership taxonomy.
 *
 * @since 1.0.0
 */

function register() {
    
    $labels = [
        'name'          => __( 'Partnerships', 'cm-theme-core' ),
        'singular_name' => __( 'Partnership', 'cm-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Partnerships', 'cm-theme-core' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'partnership',
            'with_front' => true,
        ],
        'show_admin_column'     => false,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'partnership',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'partnership', ['partner'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
