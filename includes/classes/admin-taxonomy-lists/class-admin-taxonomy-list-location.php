<?php
/**
 * Class Admin_Taxonomy_List_Location
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 * @uses    ACF
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin taxonomy list for taxonomy "location".
 *
 * @since 2.1.0
 */

class Admin_Taxonomy_List_Location extends \wordpress_helper\Admin_Taxonomy_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $taxonomy = 'location';



    /**
     * Determines the columns of the admin taxonomy list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns['cb']            = $default['cb'];
        $columns['id']            = 'ID';
        $columns['image']         = __( 'Image', 'cm-theme-core' );
        $columns['name']          = $default['name'];
        $columns['description']   = $default['description'];
        $columns['slug']          = $default['slug'];
        $columns['count-session'] = __( 'Sessions', 'cm-theme-core' );
        $columns['count-space']   = __( 'Exhibition spaces', 'cm-theme-core' );

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

        switch( $column_name ) {
            case 'id' :
                $output = $term_id;
                break;

            case 'image' :
                $image_id = get_field( 'location-image', 'location_' . $term_id );
                $image    = wp_get_attachment_image( $image_id, [ '150', '9999' ] );

                if ( ! empty( $image ) ) {
                    $output = $image;
                } else {
                    $output = '&mdash;';
                }
                break;

            case 'count-session' :
                $posts = get_posts( [
                    'post_type'   => 'session',
                    'post_status' => 'any',
                    'numberposts' => -1,
                    'tax_query'   => [[
                        'taxonomy' => 'location',
                        'terms'    => $term_id,
                    ]],
                ] );
                $term   = get_term( $term_id, 'location' );
                $output = sprintf(
                    '<a href="/wp-admin/edit.php?location=%2$s&post_type=session" title="%3$s">%1$s</a>',
                    sizeof( $posts ),
                    $term->slug,
                    __( 'View all sessions at this location', 'cm-theme-core' )
                );
                break;

            case 'count-space' :
                $posts = get_posts( [
                    'post_type'   => 'exhibition_space',
                    'post_status' => 'any',
                    'numberposts' => -1,
                    'tax_query'   => [[
                        'taxonomy' => 'location',
                        'terms'    => $term_id,
                    ]],
                ] );
                $term   = get_term( $term_id, 'location' );
                $output = sprintf(
                    '<a href="/wp-admin/edit.php?location=%2$s&post_type=exhibition_space" title="%3$s">%1$s</a>',
                    sizeof( $posts ),
                    $term->slug,
                    __( 'View all exhibition spaces in this location', 'cm-theme-core' )
                );
                break;

            default:
                break;
        }

        return $output;
    }
}


new Admin_Taxonomy_List_Location();
