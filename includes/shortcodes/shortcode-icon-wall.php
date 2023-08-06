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
 * @since 1.0.0
 *
 * @param array $atts The attributes (parameters) of the shorcode.
 *                    - partnership (optional)
 *                      The cooperation form(s) to be filtered by.
 *                      The forms of cooperation must be in the form of a comma-separated list of their identification numbers.
 *                    - link (optional)
 *                      Defines if and how the logo should be linked (none, internal, external).
 *
 * @return string The output produced by the shortcode.
 */

function shortcode_icon_wall( $atts, $content = null ) {
    
    /** Determine passed parameters. */

    $default_atts = [
        'partnership' => '',
        'link'        => 'none',
    ];
    extract( shortcode_atts( $default_atts, $atts ) );

    $link         = strtolower( trim( $link ) );
    $link_options = [
        'none',
        'internal',
        'external',
    ];

    if( ! in_array( $link, $link_options ) ) :
        $link = 'none';
    endif;


    /** Retrieve and prepare data. */

    $query = [
        'post_type'      => 'partner',
        'post_status'    => 'publish',
        'posts_per_page' => '-1',
        'order'          => 'ASC',
        'orderby'        => 'title',
    ];

    // Optionally, you can filter by type of partnership.
    if( ! empty( $partnership ) ) :
        $query['tax_query'] = [ [
            'taxonomy' => 'partnership',
            'field'    => 'term_id',
            'terms'    => explode(',', $partnership ),
        ] ];
    endif;

    $partners = get_posts( $query );


    /** Do the shortcode stuff and start the output. */

    if( $partners ) :
        ob_start();
?>

<ul class="icon-wall">
    <?php
    foreach( $partners as $partner ) :

        $data = api\get_partner_dataset( $partner->ID );


        // Squared logos?
        $li_class = '';
        $thumb    = wp_get_attachment_metadata( get_post_thumbnail_id( $data['id'] ) );

        if( $thumb['width'] == $thumb['height'] ) :
            $li_class = ' class="is-squared"';
        endif;
    ?>
    <li<?php echo $li_class; ?>>
        <?php
        switch( $link ) :
            case 'internal' :
                echo sprintf(
                    '<a href="%1$s" target="_self" title="%2$s">',
                    $data['permalink'],
                    __( 'View details page', 'cm-theme-core' ),
                );
            break;

            case 'external' :
                if( ! empty( $data['website'] ) ) :
                    echo sprintf(
                        '<a href="%1$s" target="blank" title="%2$s">',
                        $data['website'],
                        __( 'View website', 'cm-theme-core' ),
                    );
                endif;
            break;

            case 'none' :
            break;
        endswitch;

        echo get_the_post_thumbnail( $data['id'], 'full' );

        switch( $link ) :
            case 'internal' :
                echo '</a>';
            break;

            case 'external' :
                if( ! empty( $data['website'] ) ) :
                    echo '</a>';
                endif;
            break;

            case 'none' :
            break;
        endswitch;
        ?>
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

add_shortcode( 'icon-wall', __NAMESPACE__ . '\shortcode_icon_wall' );
