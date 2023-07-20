<?php
/**
 * Shortcode [exhibition-list].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
* Generates an (exhibitor) list with the cooperation partners.
 *
 * @since 1.0.0
 *
 * @param array $atts The attributes (parameters) of the shorcode..
 *                    - partnership (optional)
 *                      The cooperation form(s) to be filtered by.
 *                      The forms of cooperation must be in the form of a comma-separated list of their identification numbers.
 *
 * @return string The output produced by the shortcode.
 */

function shortcode_exhibition_list( $atts, $content = null )
{
    /** Determine passed parameters. */

    $default_atts = array(
        'partnership' => '',
    );
    extract( shortcode_atts( $default_atts, $atts ) );


    /** Retrieve and prepare data. */

    $query = array(
        'post_type'      => 'partner',
        'post_status'    => 'publish',
        'posts_per_page' => '-1',
        'order'          => 'ASC',
        'orderby'        => 'title',
    );

    // Optionally, you can filter by type of partnership.
    if( ! empty( $partnership ) ) :
        $query[ 'tax_query' ] = array( array(
            'taxonomy' => 'partnership',
            'field'    => 'term_id',
            'terms'    => explode(',', $partnership ),
        ) );
    endif;

    $partners = get_posts( $query );


    /** Do the shortcode stuff and start the output */

    if( $partners ) :
        ob_start();
?>
<ul class="exhibition-list">
    <?php
    foreach( $partners as $partner ) :
        $data = core__get_partner_dataset( $partner->ID );
    ?>

    <li class="exhibition-list-element">
        <a href="<?php echo $data[ 'permalink' ]; ?>">
            <figure>
                <?php echo get_the_post_thumbnail( $partner->ID, 'full' ); ?>
            </figure>
            <div>
                <h3><?php echo $data[ 'title' ]; ?></h3>
                <div class="exhibition-list-layout">
                    <div><?php echo $data[ 'address' ];?></div>
                    <div>

                    <?php
                    /**
                     * Filter out empty entries
                     *
                     * @since 1.0.0
                     */
                    $spaces = array();

                    foreach( $data[ 'exhibition-spaces' ] as $space ) :
                        if( ! empty( $space[ 'location' ] ) and ! empty( $space[ 'signature' ] ) ) :
                            $spaces[] = $space;
                        endif;
                    endforeach;

                    if( ! empty( $spaces ) ) :
                    ?>
                        <div>
                            <div>
                                <div><?php echo __( 'Area', 'cm-theme-core' ); ?></div>
                                <div><?php echo __( 'Booth', 'cm-theme-core' ); ?></div>
                            </div>
                        <?php
                        foreach( $spaces as $space ) :
                        ?>
                            <div>
                                <div><?php echo $space[ 'location' ];?></div>
                                <div><?php echo $space[ 'signature' ];?></div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                        </div>
                    <?php
                    endif;
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
    <?php endforeach; ?>
</ul>
<?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    endif;

    return null;
}

add_shortcode( 'exhibition-list', __NAMESPACE__ . '\shortcode_exhibition_list' );
