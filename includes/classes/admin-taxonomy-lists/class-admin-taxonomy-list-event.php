<?php
/**
 * Class Admin_Taxonomy_List_Event
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 * @uses    ACF
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin taxonomy list for taxonomy "event".
 *
 * @since 2.1.0
 */

class Admin_Taxonomy_List_Event extends \wordpress_helper\Admin_Taxonomy_List {

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
            'name'        => $default['name'],
            'description' => $default['description'],
            'slug'        => $default['slug'],
            'status'      => __( 'Status', 'cm-theme-core' ),
            'posts'       => __( 'Events', 'cm-theme-core' ),
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
            case 'id' :
                $poutput = $term_id;
                break;

            case 'status' :
                $status = get_field( 'event-status', 'term_' . $term_id );
                $output = sprintf(
                    '<span class="status-icon %1$s" title="%2$s"></span>',
                    (1 == $status)? 'status-icon-active' : 'status-icon-inactive',
                    (1 == $status)? __( 'active', 'cm-theme-core' ) : __( 'inactive', 'cm-theme-core' ),
                );
                break;

            default:
                break;
        }

        return $output;
    }
}


new Admin_Taxonomy_List_Event();
