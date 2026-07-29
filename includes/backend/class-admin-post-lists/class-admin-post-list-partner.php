<?php
/**
 * Class Admin_Post_List_Partner
 *
 * @author  Marco Di Bella
 * @package congressomat
 * @uses    ACF
 */

namespace Congressomat\Backend;

use \Congressomat\Core\API as API;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin post list for post type "partner".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Partner extends \WordPress_Helper\Admin_Post_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $post_type = 'partner';



    /**
     * Determines the columns of the admin post list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns = [
            'cb'                   => $default['cb'],
            'image'                => __( 'Exhibitor Logo', 'congressomat' ),
            'title'                => __( 'Exhibitor', 'congressomat' ),
            'taxonomy-partnership' => __( 'Exhibitor Roles', 'congressomat' ),
            'exhibition'           => __( 'Booths', 'congressomat' ),
            'update'               => __( 'Last Update', 'congressomat' ),
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
            case 'image':
                if ( true === has_post_thumbnail( $post_id ) ) {

                    echo sprintf(
                        '<a class="congressomat-exhibitor-image" href="%1$s">%2$s</a>',
                        esc_url( sprintf(
                            '%1$spost.php?post=%2$s&action=edit',
                            get_admin_url(),
                            $post_id,
                        ) ),
                        get_the_post_thumbnail(
                            $post_id,
                            [100, 0]
                        ),
                    );

                        //get_the_post_thumbnail( $post_id, [ 100, 0 ] ),
                } else {
                    echo '&mdash;';
                }
                break;

            case 'exhibition':
                $data = API\get_partner_dataset( $post_id );

                if ( ! empty( $data['exhibition-spaces'] ) ) {
                    $spaces = [];

                    foreach ( $data['exhibition-spaces'] as $space ) {
                        if ( ! empty( $space['location'] ) and ! empty( $space['signature'] ) ) {
                            $spaces[] = sprintf(
                                '<a href="%1$s">%2$s</a>%3$s',
                                esc_url( sprintf(
                                    '%1$spost.php?post=%2$s&action=edit',
                                    get_admin_url(),
                                    $space['id']
                                ) ),
                                $space['signature'],
                                ( ! empty( $space['package'] ) )? ' (' . $space['package'] . ')' : '',
                            );
                        }
                    }

                    if ( ! empty( $spaces ) ) {
                        echo implode( ', ', $spaces );
                    } else {
                        echo '&mdash;';
                    }
                } else {
                    echo '&mdash;';
                }
                break;

            case 'update':
                show_modified_date( $post_id );
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
        $columns['title']  = 'title';
        $columns['update'] = 'update';

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
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Partner();
