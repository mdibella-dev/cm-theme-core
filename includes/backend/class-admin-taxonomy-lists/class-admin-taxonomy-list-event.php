<?php
/**
 * Class Admin_Taxonomy_List_Event
 *
 * @author  Marco Di Bella
 * @package congressomat
 * @uses    ACF
 */

namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin taxonomy list for taxonomy "event".
 *
 * @since 2.1.0
 */

class Admin_Taxonomy_List_Event extends \WordPress_Helper\Admin_Taxonomy_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $taxonomy = 'event';



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
            'name'        => __( 'Title', 'congressomat' ),
            'description' => __( 'Description', 'congressomat' ),
            'sessions'    => __( 'Sessions', 'congressomat' ),
            'status'      => __( 'Status', 'congressomat' ),
        ];

        return $columns;
    }



    /**
     * Registers sortable columns (by assigning appropriate orderby parameters).
     *
     * @param array columns The columns
     *
     * @return array An associative array
     */

    public function manage_sortable_columns( $columns ) {
        unset( $columns['description'] );

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

            case 'sessions':
                $term  = get_term( $term_id, 'event' );
                $posts = get_posts( [
                    'post_type'   => 'session',
                    'post_status' => 'any',
                    'numberposts' => -1,
                    'tax_query'   => [ [
                        'taxonomy' => 'event',
                        'terms'    => $term_id,
                    ] ],
                ] );
                $count = sizeof( $posts );

                if ( $count != 0 ) {
                    $output = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( sprintf(
                            '%1$sedit.php?event=%2$s&post_type=session',
                            get_admin_url(),
                            $term->slug,
                        ) ),
                        sizeof( $posts ),
                    );
                } else {
                    $output = '&mdash;';
                }
                break;

            case 'status':
                $status = get_field( 'event-status', 'term_' . $term_id );
                $output = sprintf(
                    '<span class="status-icon %1$s" title="%2$s"></span>',
                    (1 == $status)? 'status-icon-active' : 'status-icon-inactive',
                    (1 == $status)? __( 'Active', 'congressomat' ) : __( 'Inactive', 'congressomat' ),
                );
                break;

            default:
                break;
        }

        return $output;
    }
}


new Admin_Taxonomy_List_Event();
