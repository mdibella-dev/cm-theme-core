<?php
/**
 * Shortcode [partner-table].
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Generates a table with the cooperation partners.
 *
 * @since 1.0.0
 *
 * @param array $atts The attributes (parameters) of the shorcode.
 *                    - partnership (optional)
 *                      The cooperation form(s) to be filtered by.
 *                      The forms of cooperation must be in the form of a comma-separated list of their identification numbers.
 *                    - fieldset
 *                      A comma-separated list of field keys used to select and sort table rows.
 *                      The following values are currently possible: LOGO, BESCHREIBUNG, MESSESTAND
 *
 * @return string The output produced by the shortcode.
 */

function cm_shortcode_partner_table( $atts, $content = null )
{
    /** Determine passed parameters. */

    $default_atts = array(
        'partnership' => '',
        'fieldset'    => '',
    );
    extract( shortcode_atts( $default_atts, $atts ) );


    /** Retrieve and prepare data. */

    $query = array(
        'post_type'      => 'partner',
        'post_status'    => 'publish',
        'posts_per_page' => '-1',
        'order'          => 'ASC',
        'orderby'        => 'title'
    );

    // Optionally, you can filter by type of partnership
    if( ! empty( $partnership ) ) :
        $query[ 'tax_query' ] = array( array(
            'taxonomy' => 'partnership',
            'field'    => 'term_id',
            'terms'    => explode(',', $partnership )
        ) );
    endif;

    $partners = get_posts( $query );


    /** Do the shortcode stuff and start the output */

    $output = '';

    if( $partners ) :
        $rows       = array();
        $field_keys = explode( ',', strtoupper( str_replace(" ", "", $fieldset ) ) );


        /** Loop through all partners found and generate corresponding table rows. */

        foreach( $partners as $partner ) :
            unset( $cells );

            foreach( $field_keys as $field_key ) :

                switch( $field_key ) :

                    case 'LOGO':
                        $link  = get_field( 'partner-webseite', $partner->ID );
                        $image = get_the_post_thumbnail( $partner->ID, 'full' );

                        if( !empty( $link ) ) :
                            $cells[ 'partner-logo' ] = sprintf(
                                '<a href="%1$s" target="_blank" title="%2$s" rel="external">%3$s</a>',
                                $link,
                                __( 'Externen Link aufrufen', 'cm' ),
                                $image
                            );
                        else :
                            $cells[ 'partner-logo' ] = $image;
                        endif;

                    break;

                    case 'BESCHREIBUNG':
                        $title       = get_the_title( $partner->ID);
                        $description = get_field( 'partner-beschreibung', $partner->ID );
                        $link        = get_field( 'partner-webseite', $partner->ID );

                        if( ! empty( $title ) ) :
                            $cells['partner-description'] .= sprintf(
                                '<h2 class="title">%1$s</h2>',
                                $title
                            );
                        endif;

                        if( ! empty( $description ) ) :
                            $cells['partner-description'] .= sprintf(
                                '<span class="description">%1$s</span>',
                                apply_filters( 'the_content', $description )
                            );
                        endif;

                        if( ! empty( $link ) ) :
                            $url                           = parse_url( $link );
                            $cells['partner-description'] .= sprintf(
                                '<span class="link">%1$s</span>',
                                sprintf(
                                    '<a href="%1$s" target="_blank" title="%2$s" rel="external">%3$s</a>',
                                    $link,
                                    __( 'Externen Link aufrufen', 'cm' ),
                                    $url['host']
                                )
                            );
                        endif;

                    break;

                    case 'MESSESTAND':
                        $exhibition = get_field( 'messestand', $partner->ID );
                        $location   = cm_get_location( $exhibition['partner-messestand-ort'] );
                        $number     = $exhibition['partner-messestand-nummer'];

                        if( ! empty( $number ) or !empty( $location ) ) :
                            $strings = array();

                            if( ! empty( $number ) ) :
                                $strings[] = sprintf(
                                    __( 'Stand %1$s', 'cm' ),
                                    $number
                                );
                            endif;

                            if( ! empty( $location ) ) :
                                $strings[] = $location;
                            endif;

                            $cells['partner-exhibition'] = sprintf(
                                '<span class="exhibition">%1$s</span>',
                                implode( ', ', $strings )
                            );
                        else :
                            $cells['partner-exhibition'] = '';
                        endif;

                    break;

                endswitch;

            endforeach;


            /** Assemble all table cells into a table row. */

            $row_content = '';

            foreach( $cells as $class => $cell ) :
                $row_content .= sprintf(
                    '<td class="%1$s">%2$s</td>',
                    $class,
                    $cell
                );
            endforeach;

            $rows[] = sprintf(
                '<tr>%1$s</tr>',
                $row_content
            );

        endforeach;


        /** Prepare output. */

        $output .= '<table class="partner-table">';
        $output .= '<tbody>';

        foreach( $rows as $row ) :
            $output .= $row;
        endforeach;

        $output .= '</tbody>';
        $output .= '</table>';
    endif;

    return $output;
}

add_shortcode( 'partner-table', 'cm_shortcode_partner_table' );
