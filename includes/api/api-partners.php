<?php
/**
 * CM core functions.
 *
 * @author  Marco Di Bella
 * @package cm-theme-core
 */

namespace cm_theme_core\api;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Returns the record of a specific partner.
 *
 * @since 1.0.0
 *
 * @param int $partner_id
 *
 * @return array
 */

function get_partner_dataset( $partner ) {

    $partner_post = get_post( $partner );

    $data['id']                = $partner;
    $data['permalink']         = get_post_permalink( $partner_post );
    $data['title']             = get_the_title( $partner_post );
    $data['address']           = get_field( 'partner-anschrift', $partner_post );
    $data['phone']             = get_field( 'partner-telefon', $partner_post );
    $data['fax']               = get_field( 'partner-telefax', $partner_post );
    $data['mail']              = get_field( 'partner-mail', $partner_post );
    $data['website']           = get_field( 'partner-webseite', $partner_post );
    $data['description']       = get_field( 'partner-beschreibung', $partner_post );
    $data['exhibition-spaces'] = [];

    while ( have_rows( 'partner-exhibition-spaces', $partner_post ) ) {
        the_row();

        $space          = get_sub_field( 'partner-exhibition-space', $partner_post );
        $space_post     = get_post( $space );
        $space_location = get_term( get_field( 'exhibition-space-location', $space_post ),'location' );
        $space_package  = get_term( get_field( 'exhibition-space-package', $space_post ), 'exhibition_package' );

        if ( ( false == is_wp_error( $space_location ) ) and ( false == is_wp_error( $space_package ) ) ) {

            $data['exhibition-spaces'][] = [
                'signature' => get_the_title( $space_post ),
                'location'  => $space_location->name,
                'package'   => $space_package->name,
                'id'        => $space_post->ID,
            ];
        }
    }

    return $data;
}
