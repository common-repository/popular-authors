<?php
/**
 * Admin module
 *
 * @package Popular_Authors\Admin
 */

namespace WebberZone\Popular_Authors\Admin;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Popular Authors Admin class.
 *
 * @since 1.1.0
 */
class Admin {

	/**
	 * Constructor class.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_filter( 'admin_notices', array( $this, 'admin_notices' ) );
		add_filter( 'tptn_settings_sections', array( $this, 'add_settings_section' ), 11 );
		add_filter( 'tptn_registered_settings', array( $this, 'settings_popular_authors' ), 11 );
	}

	/**
	 * Display notices in the admin screen if Top 10 v3 is not installed.
	 *
	 * @since 1.1.0
	 */
	public static function admin_notices() {
		global $pagenow;
		$admin_pages = array( 'index.php', 'plugins.php' );

		if ( ! function_exists( 'tptn_pop_posts' ) && in_array( $pagenow, $admin_pages, true ) ) {
			?>
			<div class="notice notice-warning">
				<p>
				<?php
				printf(
					/* translators: 1: Force regenerate plugin link. */
					esc_html__( 'Popular Authors requires Top 10 v3 or above. Please install %1$s via Plugins > Add New or deactivate this plugin.', 'popular-authors' ),
					'<a href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=top-10&amp;TB_iframe=true&amp;width=600&amp;height=550' ) ) . '" class="thickbox">Top 10</a>'
				);
				?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Add settings sections to Top 10 Admin.
	 *
	 * @since 1.1.0
	 *
	 * @param array $settings_sections Settings sections array.
	 * @return array Updated Settings sections.
	 */
	public static function add_settings_section( $settings_sections ) {
		$settings_sections['popular-authors'] = __( 'Popular Authors', 'popular-authors' );

		return $settings_sections;
	}

	/**
	 * Popular author settings for the Top 10 settings page.
	 *
	 * @since 1.1.0
	 *
	 * @param array $settings Top 10 Settings array.
	 * @return array Updated Top 10 settings array.
	 */
	public static function settings_popular_authors( $settings ) {

		$new_settings = array(
			'popular-authors' => array(
				'wzpa_cache'               => array(
					'id'      => 'cache',
					'name'    => esc_html__( 'Cache output', 'top-10' ),
					'desc'    => esc_html__( 'Turn this ON to cache the HTML output. This option uses the same settings as Top 10 and creates similar cache keys.', 'top-10' ),
					'type'    => 'checkbox',
					'options' => true,
				),
				'wzpa_number'              => array(
					'id'      => 'wzpa_number',
					'name'    => esc_html__( 'Number of authors to display', 'popular-authors' ),
					'desc'    => esc_html__( 'Maximum number of authors that will be displayed in the list. This option is used if you do not specify the number of posts in the block, widget or shortcode.', 'popular-authors' ),
					'type'    => 'number',
					'options' => -1,
					'size'    => 'small',
					'min'     => -1,
				),
				'wzpa_range_desc'          => array(
					'id'   => 'wzpa_range_desc',
					'name' => '<strong>' . esc_html__( 'Default custom period range', 'popular-authors' ) . '</strong>',
					'desc' => esc_html__( 'The next two options allow you to set the default range for the custom period.', 'popular-authors' ),
					'type' => 'descriptive_text',
				),
				'wzpa_daily_range'         => array(
					'id'      => 'wzpa_daily_range',
					'name'    => esc_html__( 'Day(s)', 'popular-authors' ),
					'desc'    => '',
					'type'    => 'number',
					'options' => '1',
					'min'     => '0',
					'size'    => 'small',
				),
				'wzpa_hour_range'          => array(
					'id'      => 'wzpa_hour_range',
					'name'    => esc_html__( 'Hour(s)', 'popular-authors' ),
					'desc'    => '',
					'type'    => 'number',
					'options' => '0',
					'min'     => '0',
					'max'     => '23',
					'size'    => 'small',
				),
				'wzpa_optioncount'         => array(
					'id'      => 'wzpa_optioncount',
					'name'    => esc_html__( 'Show views', 'top-10' ),
					'desc'    => esc_html__( "Show the total number of views in parenthesis next to the author's name", 'top-10' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'wzpa_exclude_admin'       => array(
					'id'      => 'wzpa_exclude_admin',
					'name'    => esc_html__( "Exclude 'admin' account", 'top-10' ),
					'desc'    => '',
					'type'    => 'checkbox',
					'options' => false,
				),
				'wzpa_show_fullname'       => array(
					'id'      => 'wzpa_show_fullname',
					'name'    => esc_html__( 'Show full name', 'top-10' ),
					'desc'    => esc_html__( "Whether to show the author's full name instead of the display name", 'top-10' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'wzpa_show_avatar'         => array(
					'id'      => 'wzpa_show_avatar',
					'name'    => esc_html__( 'Show Avatar', 'top-10' ),
					'desc'    => esc_html__( "Whether to show the author's avatar", 'top-10' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'wzpa_html_wrapper_header' => array(
					'id'   => 'wzpa_html_wrapper_header',
					'name' => '<h3>' . esc_html__( 'HTML to display', 'top-10' ) . '</h3>',
					'desc' => '',
					'type' => 'header',
				),
				'wzpa_before_list'         => array(
					'id'      => 'wzpa_before_list',
					'name'    => esc_html__( 'Before the list of posts', 'top-10' ),
					'desc'    => '',
					'type'    => 'text',
					'options' => '<ul>',
				),
				'wzpa_after_list'          => array(
					'id'      => 'wzpa_after_list',
					'name'    => esc_html__( 'After the list of posts', 'top-10' ),
					'desc'    => '',
					'type'    => 'text',
					'options' => '</ul>',
				),
				'wzpa_before_list_item'    => array(
					'id'      => 'wzpa_before_list_item',
					'name'    => esc_html__( 'Before each list item', 'top-10' ),
					'desc'    => '',
					'type'    => 'text',
					'options' => '<li>',
				),
				'wzpa_after_list_item'     => array(
					'id'      => 'wzpa_after_list_item',
					'name'    => esc_html__( 'After each list item', 'top-10' ),
					'desc'    => '',
					'type'    => 'text',
					'options' => '</li>',
				),
				'wzpa_styles_desc'         => array(
					'id'   => 'wzpa_styles_desc',
					'name' => '<h3>' . esc_html__( 'Styles', 'popular-authors' ) . '</h3>',
					'desc' => '',
					'type' => 'descriptive_text',
				),
				'wzpa_styles'              => array(
					'id'      => 'wzpa_styles',
					'name'    => esc_html__( 'Popular Authors style', 'top-10' ),
					'desc'    => '',
					'type'    => 'radiodesc',
					'default' => 'no_style',
					'options' => self::get_styles(),
				),
			),
		);

		return array_merge( $settings, $new_settings );
	}

	/**
	 * Get the various styles.
	 *
	 * @since 1.2.0
	 * @return array Style options.
	 */
	public static function get_styles() {
		$styles = array(
			array(
				'id'          => 'no_style',
				'name'        => esc_html__( 'No styles', 'top-10' ),
				'description' => esc_html__( 'Select this option if you plan to add your own styles', 'top-10' ),
			),
			array(
				'id'          => 'card',
				'name'        => esc_html__( 'Card Layout', 'top-10' ),
				'description' => esc_html__( 'Display the popular authors in a card layout using CSS grid', 'top-10' ),
			),
			array(
				'id'          => 'left_thumbs',
				'name'        => esc_html__( 'Left Thumbs', 'top-10' ),
				'description' => esc_html__( 'Display the popular authors in a grid with the image to the left of the text', 'top-10' ),
			),
		);

		/**
		 * Filter the array containing the types of styles to add your own.
		 *
		 * @since 2.5.0
		 *
		 * @param array $styles Different styles.
		 */
		return apply_filters( 'wzpa_get_styles', $styles );
	}
}
