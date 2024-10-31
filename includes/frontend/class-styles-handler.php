<?php
/**
 * Functions dealing with styles.
 *
 * @package Popular_Authors
 */

namespace WebberZone\Popular_Authors\Frontend;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Styles_Handler class.
 *
 * @since 1.2.0
 */
class Styles_Handler {

	/**
	 * Constructor class.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_styles' ) );
	}

	/**
	 * Enqueue styles.
	 *
	 * @since 1.2.0
	 */
	public static function register_styles() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$style_array = self::get_style();

		if ( ! empty( $style_array['name'] ) ) {
			$style     = $style_array['name'];
			$extra_css = $style_array['extra_css'];

			wp_register_style(
				"wzpa-style-{$style}",
				plugins_url( "includes/css/{$style}{$suffix}.css", POP_AUTHOR_PLUGIN_FILE ),
				array(),
				POP_AUTHOR_VERSION
			);
			wp_enqueue_style( "wzpa-style-{$style}" );
			wp_add_inline_style( "wzpa-style-{$style}", $extra_css );
		}
	}

	/**
	 * Get the current style for the popular posts.
	 *
	 * @since 3.0.0
	 * @since 3.2.0 Added parameter $style
	 *
	 * @param string $style Style parameter.
	 *
	 * @return array Contains two elements:
	 *               'name' holding style name and 'extra_css' to be added inline.
	 */
	public static function get_style( $style = '' ) {

		$style_array = array();
		$wzpa_style  = ! empty( $style ) ? $style : \tptn_get_option( 'wzpa_styles' );

		switch ( $wzpa_style ) {
			case 'card':
				$style_array['name']      = 'card';
				$style_array['extra_css'] = '';
				break;

			case 'left_thumbs':
				$style_array['name']      = 'left-thumbs';
				$style_array['extra_css'] = '';
				break;

			default:
				$style_array['name']      = '';
				$style_array['extra_css'] = '';
				break;
		}

		/**
		 * Filter the style array which contains the name and extra_css.
		 *
		 * @since 3.2.0
		 *
		 * @param array  $style_array  Style array containing name and extra_css.
		 * @param string $wzpa_style    Style name.
		 */
		return apply_filters( 'wzpa_get_style', $style_array, $wzpa_style );
	}
}
