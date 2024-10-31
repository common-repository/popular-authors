<?php
/**
 * Main functions
 *
 * @package Popular_Authors
 */

use WebberZone\Popular_Authors\Frontend\Display;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * List all the authors of the site, with several options available.
 *
 * @since 1.0.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param array $args List of arguments. See wzpa_list_popular_authors_args() for full list.
 * @return void|string Void if 'echo' argument is true, list of authors if 'echo' is false.
 */
function wzpa_list_popular_authors( $args = array() ) {
	if ( $args['echo'] ) {
		echo Display::list_popular_authors( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return Display::list_popular_authors( $args );
	}
}

/**
 * Get the popular author IDs.
 *
 * @since 1.0.0
 *
 * @param string|array $args {
 *     Optional. Array or string of default arguments.
 *
 *     @type int          $blog_id       The site ID. Default is the current site.
 *     @type int          $number        Number of authors to limit the query for. Can be used in
 *                                       conjunction with pagination. Value -1 (all) is supported, but
 *                                       should be used with caution on larger sites.
 *                                       Default -1 (all authors).
 *     @type bool         $daily         Whether daily or overall counts. Default false.
 *     @type int          $daily_range   Daily range for custom period. Default empty.
 *     @type int          $hour_range    Hour range for custom period. Default empty.
 *     @type int          $offset        Number of authors to offset in retrieved results. Can be used in
 *                                       conjunction with pagination. Default 0.
 *     @type int          $paged         When used with number, defines the page of results to return. Default 1.
 *     @type array|string $include       Array or comma/space-separated list of author IDs to include. Default empty array.
 *     @type array|string $exclude       Array or comma/space-separated list of author IDs to exclude. Default empty array.
 * }
 * @return object List of popular authors and corresponding view counts.
 */
function wzpa_get_popular_author_ids( $args = array() ) {
	return Display::get_popular_author_ids( $args );
}

/**
 * Fills in missing query variables with default values.
 *
 * @since 1.0.0
 *
 * @param string|array $args {
 *     Optional. Array or string of default arguments.
 *
 *     @type int          $number           Maximum authors to return or display. Default empty (all authors).
 *     @type bool         $daily            Whether daily or overall counts. Default false.
 *     @type int          $daily_range      Daily range for custom period. Default null.
 *     @type int          $hour_range       Hour range for custom period. Default null.
 *     @type int          $offset           Number of authors to offset in retrieved results. Can be used in
 *                                          conjunction with pagination. Default 0.
 *     @type bool         $optioncount      Show the count in parenthesis next to the author's name. Default true.
 *     @type bool         $exclude_admin    Whether to exclude the 'admin' account, if it exists. Default false.
 *     @type bool         $show_fullname    Whether to show the author's full name. Default false.
 *     @type bool         $show_avatar      Whether to show the author's avatar. Default false.
 *     @type bool         $hide_empty       Whether to hide any authors with no posts. Default true.
 *     @type bool         $cache            Whether to cache output. Default false.
 *     @type bool         $echo             Whether to output the result or instead return it. Default true.
 *     @type array|string $include          Array or comma/space-separated list of author IDs to include. Default empty.
 *     @type array|string $exclude          Array or comma/space-separated list of author IDs to exclude. Default empty.
 *     @type string       $before_list      HTML tag before the list. Default <ul>.
 *     @type string       $after_list       HTML tag after the list. Default </ul>.
 *     @type string       $before_list_item HTML tag before the list item. Default <li>.
 *     @type string       $after_list_item  HTML tag after the list item. Default </li>.
 * }
 * @return array Complete query variables with undefined ones filled in with defaults.
 */
function wzpa_list_popular_authors_args( $args = array() ) {
	return Display::list_popular_authors_args( $args );
}
