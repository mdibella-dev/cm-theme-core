<?php
/**
 * Shortcode [icon-wall].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;

use \cm_theme_core\api as api;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Creates a "wall" with the logos of the cooperation partners.
 *
 * @since 2.0.0
 *
 * The attributes (parameters) of the shorcode:
 *
 * - partnership (optional)     The cooperation form(s) to be filtered by.
 *                              The forms of cooperation must be in the form of a comma-separated list of their identification numbers.
 * - link (optional)            Defines if and how the logo should be linked (none, internal, external).
 */

class Shortcode_Icon_Wall extends \wordpress_helper\Shortcode {

    /**
     * The shortcode tag.
     *
     * @var string
     */

    protected $tag = 'icon-wall';




    /**
     * The result of the query
     *
     * @see prepare()
     */

    protected $partners = null;



    /**
     * Gets the The default attributes of this shortcode.
     *
     * @return array The default attributes
     */

    protected function get_default_atts() {
        return [
            'partnership' => '',
            'link'        => 'none',
        ];
    }



    /**
     * Gets how links are displayed (link mode)
     *
     * @return string The link mode
     */

    protected function get_link_mode() {
        return $this->atts['link'];
    }



    /**
     * Returns the comma separated list of partnerships to filter by (optional)
     *
     * @return string The comma separated list of partnerships
     */

    protected function get_partnership() {
        return $this->atts['partnership'];
    }



    /**
     * Sets how links are displayed (link mode)
     *
     * @param string $link_mode One of following options:
     *                          - none
     *                          - internal
     *                          - external
     */

    protected function set_link_mode( $link_mode ) {
        $this->atts['link'] = $link_mode;
    }



    /**
     * Prepares the shortcode (the shortcode logic).
     *
     * @return bool true|false The outcome of the preparation process
     */

    function prepare() {

        /**
         * Step 1: Verify the link mode
         */

        $link_mode         = strtolower( trim( $this->get_link_mode() ) );
        $link_mode_options = [
            'none',
            'internal',
            'external',
        ];

        if ( ! in_array( $link_mode, $link_mode_options ) ) {
            $link = 'none';
        }

        $this->set_link_mode( $link_mode );


        /**
         * Step 2: Do the necessary query
         */

        $query = [
            'post_type'      => 'partner',
            'post_status'    => 'publish',
            'posts_per_page' => '-1',
            'order'          => 'ASC',
            'orderby'        => 'title',
        ];

        // Add partnership filtering (optional)
        if ( ! empty( $this->get_partnership() ) ) {
            $query['tax_query'] = [ [
                'taxonomy' => 'partnership',
                'field'    => 'term_id',
                'terms'    => explode( ',', $this->get_partnership() ),
            ] ];
        }

        // Do the query
        $this->partners = get_posts( $query );

        return (bool) $this->partners;
    }



    /**
     * Renders the shortcode (the shortcode output).
     */

    function render() {

        if ( $this->partners ) {
        ?>
        <ul class="icon-wall">
            <?php
            foreach ( $this->partners as $partner ) {

                $data     = api\get_partner_dataset( $partner->ID );
                $li_class = '';
                $thumb    = wp_get_attachment_metadata( get_post_thumbnail_id( $data['id'] ) );

                if ( $thumb['width'] == $thumb['height'] ) { // squared logos?
                    $li_class = ' class="is-squared"';
                }
            ?>
            <li<?php echo esc_attr( $li_class); ?>>
                <?php
                switch ( $this->get_link_mode() ) {

                    case 'internal':
                        echo sprintf(
                            '<a href="%1$s" target="_self" title="%2$s">',
                            esc_url( $data['permalink'] ),
                            __( 'View details page', 'cm-theme-core' ),
                        );
                        break;

                    case 'external':
                        if ( ! empty( $data['website'] ) ) {
                            echo sprintf(
                                '<a href="%1$s" target="blank" title="%2$s">',
                                esc_url( $data['website'] ),
                                __( 'View website', 'cm-theme-core' ),
                            );
                        }
                        break;

                    case 'none':
                        break;
                }

                echo get_the_post_thumbnail( $data['id'], 'full' );

                switch ( $this->get_link_mode() ) {
                    case 'internal':
                        echo '</a>';
                        break;

                    case 'external':
                        if ( ! empty( $data['website'] ) ) {
                            echo '</a>';
                        }
                        break;

                    case 'none':
                        break;
                }
                ?>
            </li>
        <?php } ?>
        </ul>
        <?php
        }
    }
}


new Shortcode_Icon_Wall();
