<?php
/**
 * Class Admin_Post_List_Exhibition_Space
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin post list for post type "partner".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Exhibition_Space extends \wordpress_helper\Admin_Post_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $post_type = 'exhibition_space';



    /**
     * Determines the columns of the admin post list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns = [
            'cb'                          => $default['cb'],
            'title'                       => __( 'Partner', 'cm-theme-core' ),
            'taxonomy-location'           => __( 'Location', 'cm-theme-core' ),
            'taxonomy-exhibition_package' => __( 'Exhibition package', 'cm-theme-core' ),
            'update'                      => __( 'Last updated', 'cm-theme-core' ),
        ];
        
        return $columns;
    }



    /**
     * Generates the column output.
     *
     * @param string $column_name Designation of the column to be output
     * @param int    $post_id     ID of the post (aka record) to be output
     */

    public function manage_custom_column( $column_name, $post_id ) {

        switch ( $column_name ) {
            case 'update' :
                echo sprintf(
                    __( '%1$s at %2$s', 'cm-theme-core' ),
                    get_the_modified_date( 'd.m.Y', $post_id ),
                    get_the_modified_date( 'H:i', $post_id ),
                );
                break;
        }
    }



    /**
     * Registers sortable columns (by assigning appropriate orderby parameters).
     *
     * @param array columns The columns
     *
     * @return array An associative array
     */

    public function manage_sortable_columns( $columns ) {
        $columns['title']             = 'title';
        $columns['taxonomy-location'] = 'taxonomy-location';
        $columns['update']            = 'update';


        return $columns;
    }



    /**
     * Modifys the query string (by assigning appropriate parameters).
     *
     * @param WP_Query $query   A data object of the last query made
     */

    public function manage_sorting( &$query ) {
        $orderby = $query->get( 'orderby' );
        $order   = $query->get( 'order' );

        switch ( $orderby ) {
            case 'update' :
                $query->set( 'orderby', 'modified' );
                break;

            default :
            case '' :
                $query->set( 'orderby', 'title' );
                $query->set( 'order', 'asc' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Exhibition_Space();
