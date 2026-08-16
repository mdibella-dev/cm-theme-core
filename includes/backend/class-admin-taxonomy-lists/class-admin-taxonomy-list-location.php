<?php
namespace Congressomat\Backend;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin taxonomy list for taxonomy "location".
 *
 * @uses ACF
 *
 * @since 2.1.0
 */

class Admin_Taxonomy_List_Location extends \WordPress_Helper\Admin_Taxonomy_List {

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
        $columns = [
            'cb'            => $default['cb'],
            'id'            => 'ID',
            'image'         => __( 'Image', 'congressomat' ),
            'name'          => $default['name'],
            'description'   => $default['description'],
            'count-session' => __( 'Sessions', 'congressomat' ),
            'count-space'   => __( 'Booths', 'congressomat' ),
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

        switch( $column_name ) {
            case 'id':
                $output = $term_id;
                break;

            case 'image':
                $image_id = get_field( 'location-image', 'location_' . $term_id );
                $image    = wp_get_attachment_image( $image_id, ['150', '9999'] );

                if ( ! empty( $image ) ) {
                    echo sprintf(
                        '<a class="congressomat-location-image" href="%1$s">%2$s</a>',
                        esc_url( sprintf(
                            '%1$sterm.php?taxonomy=location&tag_ID=%2$s&post_type=session',
                            get_admin_url(),
                            $term_id,
                        ) ),
                        $image
                    );
                } else {
                    $output = '&mdash;';
                }
                break;

            case 'count-session':
                $term  = get_term( $term_id, 'location' );
                $posts = get_posts( [
                    'post_type'   => 'session',
                    'post_status' => 'any',
                    'numberposts' => -1,
                    'tax_query'   => [[
                        'taxonomy' => 'location',
                        'terms'    => $term_id,
                    ]],
                ] );
                $count = sizeof( $posts );

                if ( $count != 0 ) {
                    $output = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( sprintf(
                            '%1$sedit.php?location=%2$s&post_type=session',
                            get_admin_url(),
                            $term->slug,
                        ) ),
                        sizeof( $posts ),
                    );
                } else {
                      $output = '&mdash;';
                }
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
                $term  = get_term( $term_id, 'location' );
                $count = sizeof( $posts );

                if ( $count != 0 ) {
                    $output = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( sprintf(
                            '%1$sedit.php?location=%2$s&post_type=exhibition_space',
                            get_admin_url(),
                            $term->slug,
                        ) ),
                        sizeof( $posts ),
                    );
                } else {
                    $output = '&mdash;';
                }
                break;

            default:
                break;
        }

        return $output;
    }
}


new Admin_Taxonomy_List_Location();
