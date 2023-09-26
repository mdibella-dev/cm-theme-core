<?php
/**
 * Shortcode [event-table].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core;

use \cm_theme_core\api as api;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a table with the schedule of a specific event.
 *
 * @since 2.0.0
 *
 * The attributes (parameters) of the shorcode:
 *
 * - set            The selected setlist.
 * - event          The identification number of the event.
 * - speaker        The identification number of a speaker; is used to filter the contributions of this speaker.
 * - show_details   Allow details to be displayed (TRUE, FALSE).
 */

class Shortcode_Event_Table extends \wordpress_helper\Shortcode {

    /**
     * The shortcode tag.
     *
     * @var string
     */

    protected $tag = 'event-table';



    /**
     * The sessions.
     */

    protected $sessions = null;



    /**
     * Gets the The default attributes of this shortcode.
     *
     * @return array The default attributes
     */

    protected function get_default_atts() {
        return [
            'set'          => '1',
            'speaker'      => '',
            'event'        => '',
            'date'         => '',
            'show_details' => 'false',
        ];
    }



    /**
     * Gets an array with all available setlists.
     *
     * @return array The setlists.
     */

    protected function get_setlists() {
        return [
            '1' => [
                'a' => 'session-date,session-time-range,session-location',
                'b' => 'session-title,session-subtitle',
            ],
            '2' => [
                'a' => 'session-time-begin,session-location',
                'b' => 'session-title,session-subtitle,session-speaker',
            ],
            '3' => [
                'a' => 'session-time-range',
                'b' => 'session-title,session-subtitle,session-speaker',
            ],
            '4' => [
                'a' => 'session-date,session-time-range',
                'b' => 'session-title,session-subtitle',
            ],
        ];
    }



    /**
     * Gets the selected set.
     *
     * @return int The setlist number.
     *
     */

     protected function get_setlist() {
        return (int) $this->atts['set'];
    }



    /**
     * Gets the selected speaker.
     *
     * @return int The speaker ID.
     *
     */

     protected function get_speaker() {
        return (int) $this->atts['speaker'];
    }



    /**
     * Gets the selected event.
     *
     * @return int The event ID.
     *
     */

     protected function get_event() {
        return (int) $this->atts['event'];
    }



    /**
     * Gets the selected event date.
     *
     * @return string The event date.
     *
     */

     protected function get_event_date() {
        return $this->atts['date'];
    }



    /**
     * Gets the state of the show_details flag.
     *
     * @return bool true\false
     *
     */

     protected function get_show_details() {
        return (bool) $this->atts['get_show_details'];
    }



    /**
     * Prepares the shortcode (the shortcode logic).
     *
     * @return bool true|false The outcome of the preparation process
     */

    function prepare() {

        if ( true == array_key_exists( $this->get_setlist(), $this->get_setlists() ) ) {

            // Variant 1: Search for (active) sessions of the specified speaker
            if ( ! empty( $this->get_speaker() ) ) {
                $this->sessions = api\get_sessions_by_speaker( $this->get_speaker() );
            // Variant 2: Search for the sessions of the specified event
            } elseif( ! empty( $this->get_event() ) ) {
                $this->sessions = api\get_sessions_by_event( $this->get_event(), $this->get_event_date() );
            // Nothing to search
            } else {
                $this->sessions = null;
            }
        }

        return (bool) $this->sessions;
    }



    /**
     * Renders the shortcode (the shortcode output).
     */

    function render() {

        if ( $this->sessions ) {

            $setlists = $this->get_setlists();
            $a_set    = explode( ',', $setlists[$this->get_setlist()]['a'] );
            $b_set    = explode( ',', $setlists[$this->get_setlist()]['b'] );
            ?>

            <div class="event-table has-set-<?php echo esc_attr( $this->get_setlist() );?>">

            <?php

            foreach ( $this->sessions as $session ) {
                ?>
                <div class="event-table__session">

                    <div class="event-table__session-schedule">

                        <?php
                        // Process the elements configured by a_set
                        foreach ( $a_set as $data_key ) {

                            $data_content = '';

                            switch ( $data_key ) {

                                case 'session-date':
                                    $data_content = get_field( 'programmpunkt-datum', $session->ID );
                                    break;

                                case 'session-time-begin':
                                    $data_content = get_field( 'programmpunkt-von', $session->ID );
                                    break;

                                case 'session-time-range':
                                    $data_content = get_field( 'programmpunkt-alternative-zeitangabe', $session->ID );

                                    if ( true == empty( $data_content ) ) {
                                        $data_content = sprintf(
                                            __( 'from %1$s to %2$s', 'cm-theme-core' ),
                                            get_field( 'programmpunkt-von', $session->ID ),
                                            get_field( 'programmpunkt-bis', $session->ID ) );
                                    }
                                    break;

                                case 'session-location':
                                    $data_content = api\get_location( get_field( 'programmpunkt-location', $session->ID ) );
                                    break;
                            }
                            ?>

                            <div data-type="<?php echo $data_key; ?>"><?php echo $data_content; ?></div>

                        <?php
                        }
                        ?>

                    </div>

                    <div class="event-table__session-overview">

                        <?php
                        // Process the elements configured by b_set
                        foreach ( $b_set as $data_key ) {

                            $data_content = '';

                            switch ( $data_key ) {

                                case 'session-title':
                                    $data_content = $session->post_title;
                                    break;

                                case 'session-subtitle':
                                    $data_content = get_field( 'programmpunkt-untertitel', $session->ID );
                                    break;

                                case 'session-speaker':
                                    $speakers = get_field( 'programmpunkt-referenten', $session->ID );

                                    if ( null != $speakers ) {
                                        unset( $speakers_list );

                                        foreach ( $speakers as $speaker ) {
                                            $speaker_dataset = api\get_speaker_dataset( $speaker );
                                            $speakers_list[] = sprintf(
                                                '<a href="%1$s" title="%2$s">%3$s</a>',
                                                esc_url( $speaker_dataset['permalink'] ),
                                                sprintf(
                                                    __( 'Learn more about %1$s', 'cm-theme-core' ),
                                                    $speaker_dataset['title_name']
                                                ),
                                                get_the_post_thumbnail( $speaker_dataset['id'], 'full' ) );
                                        }

                                        $data_content = implode( ' ', $speakers_list );
                                    }
                                    break;
                            }
                            ?>

                            <div data-type="<?php echo $data_key; ?>"><?php echo $data_content; ?></div>

                        <?php
                        }
                        ?>

                    </div>

                    <?php
                    // Enable display of detailed information (if available)

                    $details = apply_filters( 'the_content', get_field( 'programmpunkt-beschreibung', $session->ID ) );

                    if ( ( true == $this->get_show_details ) and ! empty( $details ) ) {
                        ?>
                        <div class="event-table__session-toggle"><span><i class="far fa-angle-down"></i></span></div>
                        <div class="event-table__session-details"><?php echo $details; ?></div>
                        <?php
                    }

                    ?>

                </div>

            <?php
            }
            ?>

            </div>

        <?php
        }
    }
}


new Shortcode_Event_Table();
