<?php
/**
 * Class Admin_Post_List_Speaker
 *
 * @author  Marco Di Bella
 * @package congressomat
 * @uses    ACF
 */

namespace CM_Theme\Core;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * A class for the implementation of the admin post list for post type "speaker".
 *
 * @since 2.1.0
 */

class Admin_Post_List_Speaker extends \WordPress_Helper\Admin_Post_List {

    /**
     * The post type.
     *
     * @var string
     */

    protected $post_type = 'speaker';



    /**
     * Determines the columns of the admin post list.
     *
     * @param array $default The defaults for columns
     *
     * @return $array An associative array describing the columns to use
     */

    public function manage_columns( $default ) {
        $columns = [
            'cb'               => $default['cb'],
            'image'            => __( 'Image', 'congressomat' ),
            'title'            => __( 'Speaker', 'congressomat' ),
            'shortdescription' => __( 'Short Description', 'congressomat' ),
            'update'           => __( 'Last Update', 'congressomat' ),
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

                    $speaker_id = $post_id;

                    echo sprintf(
                    '<a href="%1$s" title="%2$s">%3$s</a>',
                    esc_url( sprintf(
                        '%1$spost.php?post=%2$s&action=edit',
                        get_admin_url(),
                        $speaker_id,
                    ) ),
                    __( 'Edit Speaker', 'congressomat' ),
                    get_the_post_thumbnail(
                        $speaker_id,
                        'thumbnail',
                        [
                            'class' => 'speaker-icon'
                        ]
                    ),
                    );
                } else {
                    echo '&mdash;';
                }
                break;

            case 'shortdescription':
                echo trim( implode( ' ', array(
                    get_field( 'referent-titel', $post_id ),
                    get_field( 'referent-vorname', $post_id ),
                    get_field( 'referent-nachname', $post_id ),
                ) ) );

                $position = get_field( 'referent-position', $post_id );

                if ( ! empty( $position ) ) {
                    echo '<br>' . $position;
                }
                break;

            case 'update':
                echo sprintf(
                    __( '%1$s at %2$s','congressomat' ),
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
            case 'update':
                $query->set( 'orderby', 'modified' );
                break;
        }

        // Default
        $query->set( 'order', ( '' === $order )? 'ASC' : $order );
    }
}


new Admin_Post_List_Speaker();
