<?php
/**
 * Custom taxonomy: exhibition_package ('Ausstellungspaket').
 *
 * @author  Marco Di Bella
 * @package congressomat
 */

namespace CM_Theme\Core\Taxonomies\Exhibtition_Package;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Registers the exhibition package taxonomy.
 *
 * @since 1.0.0
 */

function register() {

    $labels = [
        'name'          => __( 'Exhibition packages', 'congressomat' ),
        'singular_name' => __( 'Exhibition package', 'congressomat' ),
    ];

    $args = [
        'label'                 => __( 'Exhibition packages', 'congressomat' ),
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
