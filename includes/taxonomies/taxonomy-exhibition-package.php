<?php
/**
 * Custom taxonomy: exhibition_package ('Ausstellungspaket').
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\taxonomies\exhibtition_package;


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
        'cb'          => $default['cb'],
        'id'          => 'ID',
        'name'        => $default['name'],
        'description' => $default['description'],
        'slug'        => $default['slug'],
        'count'       => __( 'Count', 'cm-theme-core' ),
    ];
    return $columns;
}
add_filter( 'manage_edit-exhibition_package_columns', __NAMESPACE__ . '\manage_edit_columns' );



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

        case 'count':
            $posts = get_posts( [
                'post_type'   => 'exhibition_space',
                'post_status' => 'any',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'exhibition_package',
                    'terms'    => $term_id,
                ]],
            ] );
            $term    = get_term( $term_id, 'exhibition_package' );
            $content = sprintf(
                '<a href="/wp-admin/edit.php?exhibition_package=%2$s&post_type=exhibition_space" title="%3$s">%1$s</a>',
                sizeof( $posts ),
                $term->slug,
                __( 'Show all exhibition spaces with this exhibition package', 'cm-theme-core' )
            );
        break;

        default:
        break;
    endswitch;

    return $content;
}
add_filter( 'manage_exhibition_package_custom_column', __NAMESPACE__ . '\manage_custom_column', 10, 3 );



/**
 * Registers the exhibition package taxonomy.
 *
 * @since 1.0.0
 */

function register()
{
    $labels = [
        'name'          => esc_html__( 'Exhibition packages', 'cm-theme-core' ),
        'singular_name' => esc_html__( 'Exhibition package', 'cm-theme-core' ),
    ];


    $args = [
        'label'                 => esc_html__( 'Exhibition packages', 'cm-theme-core' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'       => 'exhibition_package',
            'with_front' => true,
        ],
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'exhibition_package',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'exhibition_package', ['exhibition_space'], $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );
