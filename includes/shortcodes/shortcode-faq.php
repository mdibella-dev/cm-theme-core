<?php
/**
 * Shortcode [faq].
 *
 * @author  Marco Di Bella
 * @package cm
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Erzeugt ein Akkordion-Elements für FAQ
 *
 * @since 1.0.0
 *
 * @param array $atts The attributes (parameters) of the shorcode.
 *                    - faq
 *                      The selected FAQ set.
 *
 * @return string The output produced by the shortcode.
 */

function cm_shortcode_faq( $atts, $content = null )
{
    /** Determine passed parameters. */

    $default_atts = array(
        'faq' => '',
    );
    extract( shortcode_atts( $default_atts, $atts ) );


    /** Do the shortcode stuff and start the output. */

    if( have_rows( 'faq', $faq ) ) :
        ob_start();
?>

<div class="faq-accordion">
    <ul>
        <?php
        while( have_rows( 'faq', $faq ) ) :
            the_row();
        ?>
        <li class="faq-element">
            <div class="faq-question">
                <span class="faq-arrow"><i class="fal fa-long-arrow-right"></i></span>
                <span><?php echo wp_strip_all_tags( apply_filters( 'the_content', get_sub_field( 'question' ) ) ); ?></span>
            </div>

            <div class="faq-answer">
                <?php echo apply_filters( 'the_content', get_sub_field( 'answer' ) );?>
            </div>
        </li>
        <?php
        endwhile;
        ?>
    </ul>
</div>
<?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    endif;

    return null;
}

add_shortcode( 'faq', 'cm_shortcode_faq' );
