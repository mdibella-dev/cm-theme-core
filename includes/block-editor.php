<?php
/**
 * Block Editor (aka Gutenberg).
 *
 * @author  Marco Di Bella
 * @package cm
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Disable block editor for various post types.
 *
 * @since 1.0.0
 *
 * @see https://digwp.com/2018/04/how-to-disable-gutenberg/
 * @see https://stackoverflow.com/questions/52199629/how-to-disable-gutenberg-editor-for-certain-post-types/52199630
 * @see https://www.billerickson.net/disabling-gutenberg-certain-templates/
 *
 * @param bool   $current_status
 * @param string $post_type
 *
 * @return bool The outcome: true if the blockeditor is allowed, otherwise false
 */

function cm_disable_block_editor( $current_status, $post_type )
{
    if( ( 'session' === $post_type  ) or ( 'exhibitor' === $post_type ) or ( 'speaker'  === $post_type ) ) :
        return false;
    endif;

    return $current_status;
}

add_filter( 'gutenberg_can_edit_post_type', 'cm_disable_block_editor' );
add_filter( 'use_block_editor_for_post_type', 'cm_disable_block_editor', 10, 2);



/**
 * Script and style modifications for the block editor.
 *
 * @since 1.0.0
 *
 * @see https://die-netzialisten.de/wordpress/gutenberg-breite-des-editors-anpassen/
 * @see https://www.billerickson.net/block-styles-in-gutenberg/
 */

function cm_add_block_editor_assets()
{
    wp_enqueue_style(
        'block-editor',
        get_template_directory_uri() . '/assets/build/css/block-editor.min.css',
        array(),
        THEME_VERSION,
        'all'
    );
    wp_enqueue_script(
        'block-editor',
        get_template_directory_uri() . '/assets/build/js/block-editor.js',
        array( 'wp-blocks', 'wp-dom' ),
        THEME_VERSION,
        true
    );
}

add_action( 'enqueue_block_editor_assets', 'cm_add_block_editor_assets' );
