<?php
namespace Congressomat\Third_Party;



/**
 * Filter decision if taxonomy is excluded from the XML sitemap.
 *
 * @param bool   $exclude Default false.
 * @param string $type    Taxonomy name.
 */

function exclude_taxonomy( $exclude, $type ) {
    $taxonomies = [
        'event',
        'exhibition-package',
        'location',
        'partnership'
    ];

    if ( in_array( $type, $taxonomies ) ) {
        $exclude = true;
    }

    return $exclude;
}

add_filter( 'rank_math/sitemap/exclude_taxonomy', __NAMESPACE__ . '\exclude_taxonomy', 10, 2 );



/**
 * Filter decision if post type is excluded from the XML sitemap.
 *
 * @param bool   $exclude Default false.
 * @param string $type    Post type name.
 */

function exclude_post_type( $exclude, $type ) {
    $post_types = [
        'speaker',
        'partner',
        'session',
        'exhibition-space'
    ];

    if ( in_array( $type, $post_types ) ) {
        $exclude = true;
    }

    return $exclude;
}

add_filter( 'rank_math/sitemap/exclude_post_type', __NAMESPACE__ . '\exclude_post_type', 10, 2 );



/**
 * Filter to exclude post types from Analytics Index.
 *
 * @see https://rankmath.com/kb/filters-hooks-api-developer/
 */

function exclude_post_type_from_analytics( $post_types = [] ) {
    $excludes = [
        'speaker',
        'partner',
        'session',
        'exhibition-space'
    ];

    return array_diff_key( $post_types, array_flip( $excludes ) );
}

add_filter( 'rank_math/analytics/post_types', __NAMESPACE__ . '\exclude_post_type_from_analytics', 10, 1 );
