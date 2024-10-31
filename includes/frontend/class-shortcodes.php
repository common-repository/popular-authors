<?php
/**
 * Shortcode module
 *
 * @package Popular_Authors
 */

namespace WebberZone\Popular_Authors\Frontend;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Admin Columns Class.
 *
 * @since 1.2.0
 */
class Shortcodes {

	/**
	 * Constructor class.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_shortcode( 'wzpa_popular_authors', array( __CLASS__, 'wzpa_popular_authors' ) );
	}

	/**
	 * Creates a shortcode [wzpa_popular_authors number="5" daily="0"].
	 *
	 * @since  1.2.0
	 *
	 * @param  array $atts Shortcode attributes. See wzpa_list_popular_authors_args() for list of additional attributes.
	 * @return string Formatted list of top authors.
	 */
	public static function wzpa_popular_authors( $atts ) {

		$atts = shortcode_atts(
			array_merge(
				Display::list_popular_authors_args(),
				array(
					'is_shortcode' => 1,
					'echo'         => 0,
				)
			),
			$atts,
			'wzpa_popular_authors'
		);

		return Display::list_popular_authors( $atts );
	}
}
