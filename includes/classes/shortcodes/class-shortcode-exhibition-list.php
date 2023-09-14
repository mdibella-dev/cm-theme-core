<?php
/**
 * Shortcode [exhibition-list].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;

use \cm_theme_core\api as api;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
* Generates an (exhibitor) list with the cooperation partners.
 *
 * @since 2.0.0
 *
 * The attributes (parameters) of the shorcode:
 *
 * - partnership (optional)     The cooperation form(s) to be filtered by.
 *                              The forms of cooperation must be in the form of a comma-separated list of their identification numbers.
 */

class Shortcode_Exhibition_List extends \wordpress_helper\Shortcode {

    /**
     * The shortcode tag.
     *
     * @var string
     */

    protected $tag = 'exhibition-list';



    /**
     * The default shortcode attributes (parameters).
     *
     * @var array
     */

    protected $default_atts = [
        'partnership' => '',
    ];



    /**
     * The result of the query
     *
     * @see prepare()
     */

    protected $partners = null;



    /**
     * Returns the comma separated list of partnerships to filter by (optional)
     *
     * @return string The comma separated list of partnerships
     */

    protected function get_partnership() {
        return $this->atts['partnership'];
    }



    /**
     * Prepares the shortcode (the shortcode logic).
     *
     * @return bool true|false The outcome of the preparation process
     */

    function prepare() {

        /**
         * Do the necessary query
         */

        $query = [
            'post_type'      => 'partner',
            'post_status'    => 'publish',
            'posts_per_page' => '-1',
            'order'          => 'ASC',
            'orderby'        => 'title',
        ];

        // Optionally, you can filter by type of partnership.
        if ( ! empty( $partnership ) ) {
            $query['tax_query'] = [ [
                'taxonomy' => 'partnership',
                'field'    => 'term_id',
                'terms'    => explode( ',', $partnership ),
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
        <ul class="exhibition-list">

            <?php
            foreach ( $this->partners as $partner ) {
                $data = api\get_partner_dataset( $partner->ID );
            ?>

            <li class="exhibition-list-element">
                <a href="<?php echo esc_url( $data['permalink'] ); ?>">
                    <figure>
                        <?php echo get_the_post_thumbnail( $partner->ID, 'full' ); ?>
                    </figure>
                    <div>
                        <h3><?php echo $data['title']; ?></h3>
                        <div class="exhibition-list-layout">
                            <div><?php echo $data['address'];?></div>
                            <div>

                            <?php
                            /**
                             * Filter out empty entries
                             *
                             * @todo move this code fragment into get_partner_dataset()
                             */

                            $spaces = [];

                            foreach ( $data['exhibition-spaces'] as $space ) {
                                if ( ! empty( $space['location'] ) and ! empty( $space['signature'] ) ) {
                                    $spaces[] = $space;
                                }
                            }

                            if ( ! empty( $spaces ) ) {
                            ?>
                                <div>
                                    <div>
                                        <div><?php echo __( 'Area', 'cm-theme-core' ); ?></div>
                                        <div><?php echo __( 'Booth', 'cm-theme-core' ); ?></div>
                                    </div>
                                <?php
                                foreach ( $spaces as $space ) {
                                ?>
                                    <div>
                                        <div><?php echo $space['location'];?></div>
                                        <div><?php echo $space['signature'];?></div>
                                    </div>
                                <?php
                                }
                                ?>
                                </div>
                            <?php
                            }
                            ?>
                            </div>
                            <div>
                                <div class="wp-block-button is-fa-button">
                                    <span class="wp-block-button__link">
                                        <i class="fas fa-chevron-double-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <?php } ?>
        </ul>

        <?php
        }
    }
}


$exhibition_list = new Shortcode_Exhibition_List;
