<?php
/**
 * Main plugin class.
 *
 * @package WebberZone\Popular_Authors
 */

namespace WebberZone\Popular_Authors;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Main plugin class.
 *
 * @since 1.2.0
 */
final class Main {
	/**
	 * The single instance of the class.
	 *
	 * @var Main
	 */
	private static $instance;

	/**
	 * Admin.
	 *
	 * @since 1.2.0
	 *
	 * @var object Admin.
	 */
	public $admin;

	/**
	 * Shortcodes.
	 *
	 * @since 1.2.0
	 *
	 * @var object Shortcodes.
	 */
	public $shortcodes;

	/**
	 * Blocks.
	 *
	 * @since 1.2.0
	 *
	 * @var object Blocks.
	 */
	public $blocks;

	/**
	 * Styles.
	 *
	 * @since 1.2.0
	 *
	 * @var object Styles.
	 */
	public $styles;

	/**
	 * Gets the instance of the class.
	 *
	 * @since 1.2.0
	 *
	 * @return Main
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * A dummy constructor.
	 *
	 * @since 1.2.0
	 */
	private function __construct() {
		// Do nothing.
	}

	/**
	 * Initializes the plugin.
	 *
	 * @since 1.2.0
	 */
	private function init() {
		$this->shortcodes = new Frontend\Shortcodes();
		$this->blocks     = new Frontend\Blocks\Blocks();
		$this->styles     = new Frontend\Styles_Handler();

		$this->hooks();

		if ( is_admin() ) {
			$this->admin = new Admin\Admin();
		}
	}

	/**
	 * Run the hooks.
	 *
	 * @since 1.2.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'initiate_plugin' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Initialise the plugin translations and media.
	 *
	 * @since 1.2.0
	 */
	public function initiate_plugin() {
		load_plugin_textdomain( 'popular-authors', false, dirname( plugin_basename( POP_AUTHOR_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Initialise the Top 10 widgets.
	 *
	 * @since 1.2.0
	 */
	public function register_widgets() {
		register_widget( '\WebberZone\Popular_Authors\Frontend\Widgets\Authors_Widget' );
	}
}
