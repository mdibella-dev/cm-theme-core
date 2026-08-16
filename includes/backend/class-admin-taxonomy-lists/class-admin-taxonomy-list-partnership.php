<?php
namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin taxonomy list for taxonomy "partnership".
 *
 * @uses ACF
 *
 * @since 2.1.0
 */

class Admin_Taxonomy_List_Partnership extends \WordPress_Helper\Admin_Taxonomy_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $taxonomy = 'partnership';



    /**
     * Determines the columns of the admin taxonomy list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns = [
            'cb'          => $default['cb'],
            'id'          => 'ID',
            'name'        => __( 'Role Name', 'congressomat' ),
            'description' => __( 'Role Description', 'congressomat' ),
            'count'       => __( 'Count', 'congressomat' ),
        ];

        return $columns;
    }



    /**
     * Generates the column output.
     *
     * @see https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
     *
     * @param string $output      Custom column output. Default empty
     * @param string $column_name Designation of the column to be output
     * @param int    $term_id     The term ID
     */

    public function manage_custom_column( $output, $column_name, $term_id ) {

        switch ( $column_name ) {
            case 'id':
                $output = $term_id;
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
                $term   = get_term( $term_id, 'partnership' );
                $output = sprintf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url( sprintf(
                        '%1$sedit.php?post_type=%2$s&post_type=partner',
                        get_admin_url(),
                        $term->slug,
                    ) ),
                    sizeof( $posts ),
                );
                break;

            default:
                break;
        }

        return $output;
    }
}


new Admin_Taxonomy_List_Partnership();
