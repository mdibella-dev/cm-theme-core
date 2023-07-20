<?php
/**
 * Shortcode [speaker-grid].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a grid view with the images, names and position descriptions of the speakers of one or more events
 * If no information is given about the events, the events marked as active in the backend are used as a basis.
 *
 * @since 1.0.0
 *
 * @param array $atts The attributes (parameters) of the shorcode.
 *         - event (optional)
 *           Comma-separated list of events from which to select speakers.
 *         - exclude (optional)
 *           Comma-separated list of speakers not to be displayed.
 *         - show (optional)
 *           The number of sepakers to display. If nothing is specified, all speakers found are displayed.
 *         - shuffle (optional, only in connection with show)
 *           Randomizes the speaker selection before the selection by show.
 *
 * @return string The output produced by the shortcode.
 */

function shortcode_speaker_grid( $atts, $content = null )
{
    /** Determine passed parameters. */

    $default_atts = [
        'event'   => '-1', // only active events
        'exclude' => '',
        'show'    => 0,
        'shuffle' => 0,
    ];
    extract( shortcode_atts( $default_atts, $atts ) );


    /** Retrieve and prepare data. */

    $speakers = core__get_speaker_datasets( ( $event == '-1' )? implode( ',', core__get_active_events() ) : $event );

    if( $speakers ) :

        // Optional: exclusion of certain speakers
        $exclude_ids = explode( ',', str_replace(" ", "", $exclude ) );

        foreach( $speakers as $speaker ) :
            if( false == in_array( $speaker['id'], $exclude_ids ) ) :
                $speaker_list[] = $speaker;
            endif;
        endforeach;


        // Optional: limit the output
        if( ( true == is_numeric( $show ) ) and ( $show > 0 ) and ( $show < sizeof( $speaker_list ) ) ) :

            // Optional: Shuffle output
            if( 1 == $shuffle ) :
                shuffle( $speaker_list );
                $speaker_list = array_slice( $speaker_list, 0, $show );
                $speaker_list = core__sort_speaker_datasets( $speaker_list );
            else :
                $speaker_list = array_slice( $speaker_list, 0, $show );
            endif;

        endif;


        /** Do the shortcode stuff and start the output */

        ob_start();
?>
<div class="speaker-grid">
    <ul>
        <?php foreach( $speaker_list as $speaker ) : ?>
        <li>
            <a  class="speaker-grid-element"
                href="<?php echo $speaker['permalink']; ?>"
                title="<?php echo sprintf( __( 'Learn more about %1$s', 'cm-theme-core' ), $speaker['title_name'] ); ?>">

                <figure>
                    <?php echo get_the_post_thumbnail( $speaker['id'], 'full', array( 'class' => 'speaker-image' ) ); ?>
                    <figcaption>
                        <div>
                            <p class="speaker-title-name"><?php echo $speaker['title_name']; ?></p>
                            <p class="speaker-position"><?php echo $speaker['position']; ?></p>
                        </div>
                    </figcaption>
                </figure>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    endif;

    return null;
}

add_shortcode( 'speaker-grid', __NAMESPACE__ . '\shortcode_speaker_grid' );
