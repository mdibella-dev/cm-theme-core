<?php
/**
 * Shortcode [speaker-grid].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;

use \cm_theme_core\api as api;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a grid view with the images, names and position descriptions of the speakers of one or more events
 * If no information is given about the events, the events marked as active in the backend are used as a basis.
 *
 * @since 2.0.0
 *
 * The attributes (parameters) of the shorcode:
 *
 * - event (optional)       A comma-separated list of events from which to select speakers.
 * - exclude (optional)     A comma-separated list of speakers not to be displayed.
 * - show (optional)        The number of sepakers to display. If nothing is specified, all speakers found are displayed.
 * - shuffle (optional)     Randomizes the speaker selection before the selection by show (only in connection with show).
 */

class Shortcode_Speaker_Grid extends \wordpress_helper\Shortcode {

    /**
     * The shortcode tag.
     *
     * @var string
     */

    protected $tag = 'speaker-grid';


    /**
     * The default shortcode attributes (parameters).
     *
     * @var array
     */

    protected $default_atts = [
        'event'   => '-1',      // only active events
        'exclude' => '',
        'show'    => 0,
        'shuffle' => 0,
    ];


    /**
     * The result of the query
     *
     * @see prepare()
     */

    protected $speaker_list = [];



    /**
     * Gets a comma-separated list of events from which to select speakers.
     *
     * @return string The list
     */

    protected function get_event() {
        return $this->atts['event'];
    }


    /**
     * Gets a comma-separated list of speakers not to be displayed in the grid.
     *
     * @return string
     */

    protected function get_speakers_to_exclude() {
        return $this->atts['exclude'];
    }


    /**
     * Gets the number of sepakers to display.
     *
     * @return int
     */

    protected function get_show() {
        return (int) $this->atts['show'];
    }


    /**
     * Indicates whether to randomize the selection of speakers before output.
     *
     * @return bool
     */

    protected function is_shuffle_mode() {
        return (bool) $this->atts['shuffle'];
    }



    /**
     * Prepares the shortcode (the shortcode logic).
     *
     * @return bool true|false The outcome of the preparation process
     */

    function prepare() {

        $speakers = api\get_speaker_datasets( ( '-1' == $this->get_event() )? implode( ',', api\get_active_events() ) : $this->get_event() );

        if ( $speakers ) {
            // Optional: Exclusion of certain speakers
            $exclude_ids = explode( ',', str_replace( " ", "", $this->get_speakers_to_exclude() ) );

            foreach ( $speakers as $speaker ) {
                if ( false == in_array( $speaker['id'], $exclude_ids ) ) {
                    $this->speaker_list[] = $speaker;
                }
            }


            // Optional: Limit the output
            if ( ( true == is_numeric( $this->get_show() ) )
                and ( $this->get_show() > 0 )
                and ( $this->get_show() < count( $this->speaker_list ) ) ) {

                // Optional: Shuffle output
                if ( true == $this->is_shuffle_mode() ) {
                    shuffle( $this->speaker_list );
                    $this->speaker_list = array_slice( $this->speaker_list, 0, $this->get_show() );
                    $this->speaker_list = api\sort_speaker_datasets( $this->speaker_list );
                } else {
                    $this->speaker_list = array_slice( $this->speaker_list, 0, $this->get_show() );
                }

            }

        }

        return (bool) count( $this->speaker_list );
    }



    /**
     * Renders the shortcode (the shortcode output).
     */

    function render() {

        if ( 0 != count( $this->speaker_list ) ) {
        ?>
        <div class="speaker-grid">
            <ul>
                <?php foreach ( $this->speaker_list as $speaker ) { ?>
                <li>
                    <a class="speaker-grid-element"
                       href="<?php echo esc_url( $speaker['permalink'] ); ?>"
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
                <?php } ?>
            </ul>
        </div>
        <?php
        }
    }
}


new Shortcode_Speaker_Grid();
