<?php
/**
 * Class Shortcode
 *
 * @author  Marco Di Bella
 * @package wordpress-helper
 */

namespace wordpress_helper;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



abstract class Shortcode {

    /**
     * The shortcode tag.
     *
     * @var string
     */

    protected $tag = '';


    /**
     * The default shortcode attributes (parameters).
     *
     * @var array
     */

    protected $default_atts = [];


    /**
     * The shortcode attributes (parameters).
     *
     * @var array
     */

    protected $atts = [];



    /**
     * The shortcode content (the code between the opening and closing shortcode clamp).
     *
     * @var string
     */

    protected $content = '';


    /**
     * Sets the content.
     *
     * @param string $content The content
     */

    protected function set_content( $content ) {
        $this->content = $content;
    }


    /**
     * Merges the custom defined shortcode attributes with the default shortcode attributes.
     *
     * @param array $atts The array with shortcode attributes
     */

    protected function set_atts( $atts ) {
        if( ( true == is_array( $atts ) ) and ( 0 != count( $atts ) ) ) :
            $this->atts = array_merge( $this->default_atts, $atts );
        endif;
    }


    /**
     * Prepares the shortcode (the shortcode logic).
     *
     * Note: Should be overloaded!
     *
     * @return bool true|false The outcome of the preparation process
     */

    function prepare() {
        // This is a placeholder for shortcodes that have no processing logic and are for output only.
        // Should be overloaded, if necessary.
        return true;
    }


    /**
     * Renders the shortcode (the shortcode output).
     *
     * Note: Must be overloaded!
     */

    abstract function render();


    /**
     * Processes all shortcode calls.
     */

    public function callback( $atts, $content = '' ) {
        // Set up shortcode parameters
        $this->set_atts( $atts );
        $this->set_content( $content );

        // Prepare the shortcode; exit if preparation process fails
        if( false == $this->prepare() ) :
            return null;
        endif;

        // Render the shortcode
        ob_start();
        $this->render();
        $output = ob_get_contents();
        ob_end_clean();

        if( false === $output ) :   // buffering isn't active?
            $output = '';
        endif;

        return $output;
    }


    /**
     * Adds the shortcode to the WordPress ecosystem.
     */

    public function init() {
        if( ! empty( $this->tag ) ) :
            add_shortcode( $this->tag, [$this, 'callback'] );
        endif;
    }
}