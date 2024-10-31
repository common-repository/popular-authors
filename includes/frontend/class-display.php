<?php
/**
 * Functions to fetch and display the posts.
 *
 * @package Popular_Authors
 */

namespace WebberZone\Popular_Authors\Frontend;

use WebberZone\Popular_Authors\Frontend\Styles_Handler;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Display class.
 *
 * @since 1.2.0
 */
class Display {

	/**
	 * Constructor class.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
	}

	/**
	 * List all the authors of the site, with several options available.
	 *
	 * @since 1.2.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param array $args List of arguments. See self::list_popular_authors_args() for full list.
	 * @return void|string Void if 'echo' argument is true, list of authors if 'echo' is false.
	 */
	public static function list_popular_authors( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'is_widget'    => false,
			'is_shortcode' => false,
			'is_block'     => false,
			'instance_id'  => 1,
			'extra_class'  => '',
		);

		$defaults = array_merge( $defaults, self::list_popular_authors_args() );

		$args = wp_parse_args( $args, $defaults );

		$output = '';

		if ( ! function_exists( 'tptn_pop_posts' ) ) {
			return __( 'Please install and activate Top 10 plugin to display popular authors.', 'popular-authors' );
		}

		// Check if the cache is enabled and if the output exists. If so, return the output.
		if ( $args['cache'] ) {
			$cache_name = \WebberZone\Top_Ten\Frontend\Display::cache_get_key( $args );

			$output = get_transient( $cache_name );

			if ( false !== $output ) {

				/**
				 * Filter the output
				 *
				 * @since 1.1.0
				 *
				 * @param string $output Formatted list of top authors.
				 * @param array  $args   Array of arguments
				 */
				return apply_filters( 'wzpa_list_popular_authors', $output, $args );
			}
		}

		$authors = self::get_popular_author_ids( $args );

		// Set the post counts for each author.
		$post_counts       = array();
		$post_counts_query = $wpdb->get_results( "SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE " . get_private_posts_cap_sql( 'post' ) . ' GROUP BY post_author' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

		foreach ( (array) $post_counts_query as $row ) {
			$post_counts[ $row->post_author ] = $row->count;
		}

		/**
		 * Filter to create a custom HTML output for a set of Popular Authors.
		 *
		 * @since 1.2.0
		 *
		 * @param   mixed   $custom_template    Custom template. Default value null.
		 * @param   object  $authors            Array of popular author IDs.
		 * @param   array   $args               Array of settings.
		 * @param   array   $post_counts        Array of post counts for each author.
		 * @return  string                      Custom HTML formatted list of popular authors.
		 */
		$custom_template = apply_filters( 'wzpa_custom_template', null, $authors, $args, $post_counts );
		if ( ! empty( $custom_template ) ) {
			if ( $args['cache'] ) {
				/**
				 * Filter already documented in /top-10/includes/public/display-posts.php
				 */
				$cache_time = apply_filters( 'tptn_cache_time', \tptn_get_option( 'cache_time' ), $args );

				$output .= "<br /><!-- Cached output. Cached time is {$cache_time} seconds -->";

				set_transient( $cache_name, $output, $cache_time );
			}

			return $custom_template;
		}

		$style_array = Styles_Handler::get_style( $args['styles'] );

		$post_classes = array(
			'main'        => 'wzpa_authors',
			'daily'       => $args['daily'] ? 'wzpa_authors ' : '',
			'widget'      => $args['is_widget'] ? 'wzpa_authors_widget wzpa_authors_widget' . $args['instance_id'] : '',
			'shortcode'   => $args['is_shortcode'] ? 'wzpa_authors_shortcode' : '',
			'block'       => $args['is_block'] ? 'wzpa_authors_block' : '',
			'extra_class' => $args['extra_class'],
			'style'       => ! empty( $style_array['name'] ) ? 'wzpa-' . $style_array['name'] : '',
		);
		$post_classes = join( ' ', $post_classes );

		/**
		 * Filter the classes added to the div wrapper of the Popular Authors.
		 *
		 * @since 1.0.0
		 *
		 * @param string $post_classes Post classes string.
		 */
		$post_classes = apply_filters( 'wzpa_authors_class', $post_classes );

		$output .= '<div class="' . $post_classes . '">';

		if ( $authors ) {
			$output .= $args['before_list'];

			foreach ( $authors as $author ) {
				$author_id   = $author->author_id;
				$views       = $author->visits;
				$no_of_posts = isset( $post_counts[ $author_id ] ) ? $post_counts[ $author_id ] : 0;

				if ( ! $no_of_posts && $args['hide_empty'] ) {
					continue;
				}

				$author = get_userdata( $author_id );

				if ( ! $author ) {
					continue;
				}

				if ( $args['exclude_admin'] && 'admin' === $author->display_name ) {
					continue;
				}

				if ( $args['show_fullname'] && ( $author->first_name || $author->last_name ) ) {
					$name  = ! empty( $author->first_name ) ? $author->first_name : '';
					$name .= ! empty( $author->last_name ) ? ' ' . $author->last_name : '';
					$name  = trim( $name );
				} else {
					$name = $author->display_name;
				}

				$output .= $args['before_list_item'];

				if ( $args['show_avatar'] ) {
					$output .= self::get_avatar( $author );
				}

				$link  = '<span class="wzpa_authorname">';
				$link .= sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					get_author_posts_url( $author->ID, $author->user_nicename ),
					/* translators: %s: Author's display name. */
					esc_attr( sprintf( __( 'Posts by %s' ), $author->display_name ) ),
					$name
				);

				if ( $args['optioncount'] ) {
					$link .= sprintf(
						' <span class="wzpa_optioncount">(%1$s)</span>',
						number_format_i18n( $views )
					);
				}
				$link .= '</span>';

				$output .= $link;
				$output .= $args['after_list_item'];

			}

			$output .= $args['after_list'];
		}

		$output .= '</div>';

		// Check if the cache is enabled and if the output exists. If so, return the output.
		if ( $args['cache'] ) {
			/**
			 * Filter already documented in /top-10/includes/public/display-posts.php
			 */
			$cache_time = apply_filters( 'tptn_cache_time', \tptn_get_option( 'cache_time' ), $args );

			$output .= "<br /><!-- Cached output. Cached time is {$cache_time} seconds -->";

			set_transient( $cache_name, $output, $cache_time );
		}

		/**
		 * Filter already documented in includes/main.php
		 */
		$output = apply_filters( 'wzpa_list_popular_authors', $output, $args );

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}

	/**
	 * Get the popular author IDs.
	 *
	 * @since 1.0.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
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
	public static function get_popular_author_ids( $args = array() ) {
		global $wpdb;

		// Initialise some variables.
		$fields  = array();
		$where   = '';
		$join    = '';
		$groupby = '';
		$orderby = '';
		$limits  = '';

		$defaults = array(
			'blog_id'     => get_current_blog_id(),
			'number'      => -1,
			'daily'       => false,
			'daily_range' => null,
			'hour_range'  => null,
			'offset'      => '',
			'paged'       => 1,
			'include'     => array(),
			'exclude'     => array(),
		);

		// Parse incomming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( $args['daily'] ) {
			$pop_posts_table = $wpdb->base_prefix . 'top_ten_daily';
		} else {
			$pop_posts_table = $wpdb->base_prefix . 'top_ten';
		}

		$offset = ! empty( $args['offset'] ) ? $args['offset'] : 0;

		// Fields to return.
		$fields[] = "{$wpdb->users}.ID as author_id";
		$fields[] = "SUM({$pop_posts_table}.cntaccess) as visits";

		$fields = implode( ', ', $fields );

		$blog_id = 0;
		if ( isset( $args['blog_id'] ) ) {
			$blog_id = absint( $args['blog_id'] );
		}

		$posts_table = $wpdb->get_blog_prefix( $blog_id ) . 'posts';

		// Create the JOIN clause.
		$join  = " INNER JOIN {$posts_table} ON {$posts_table}.post_author={$wpdb->users}.ID ";
		$join .= " INNER JOIN {$pop_posts_table} ON {$pop_posts_table}.postnumber={$posts_table}.ID ";

		// Create the WHERE clause.
		$where .= $wpdb->prepare( " AND {$pop_posts_table}.blog_id = %d ", $blog_id ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		// Parse and sanitize 'include'.
		if ( ! empty( $args['include'] ) ) {
			$include = wp_parse_id_list( $args['include'] );
		} else {
			$include = false;
		}

		// Parse include or exclude arguments. Include is always prioritised.
		if ( ! empty( $include ) ) {
			$ids    = implode( ',', $include );
			$where .= " AND $wpdb->users.ID IN ($ids)";
		} elseif ( ! empty( $args['exclude'] ) ) {
			$ids    = implode( ',', wp_parse_id_list( $args['exclude'] ) );
			$where .= " AND $wpdb->users.ID NOT IN ($ids)";
		}

		if ( $args['daily'] ) {
			$from_date = \WebberZone\Top_Ten\Util\Helpers::get_from_date( null, (int) $args['daily_range'], (int) $args['hour_range'] );

			$where .= $wpdb->prepare( " AND {$pop_posts_table}.dp_date >= %s ", $from_date ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		// Create the base GROUP BY clause.
		$groupby = ' author_id ';

		// Create the base ORDER BY clause.
		$orderby = ' visits DESC ';

		// Create the base LIMITS clause.
		if ( isset( $args['number'] ) && $args['number'] > 0 ) {
			// If exclude_admin is enabled, then we need to fetch an extra post.
			$number = isset( $args['exclude_admin'] ) && $args['exclude_admin'] ? $args['number'] + 1 : $args['number'];

			if ( $offset ) {
				$limits = $wpdb->prepare( 'LIMIT %d, %d', $offset, $number );
			} else {
				$limits = $wpdb->prepare( 'LIMIT %d, %d', $number * ( $args['paged'] - 1 ), $number );
			}
		}

		$groupby = " GROUP BY {$groupby} ";
		$orderby = " ORDER BY {$orderby} ";

		/**
		 * Filters the SELECT clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $fields The SELECT clause of the query.
		 * @param array  $args   Arguments array.
		 */
		$fields = apply_filters_ref_array( 'wzpa_query_fields', array( $fields, $args ) );

		/**
		 * Filters the JOIN clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $join  The JOIN clause of the query.
		 * @param array  $args  Arguments array.
		 */
		$join = apply_filters_ref_array( 'wzpa_query_join', array( $join, $args ) );

		/**
		 * Filters the WHERE clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $where The WHERE clause of the query.
		 * @param array  $args  Arguments array.
		 */
		$where = apply_filters_ref_array( 'wzpa_query_where', array( $where, $args ) );

		/**
		 * Filters the GROUP BY clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $groupby The GROUP BY clause of the query.
		 * @param array  $args    Arguments array.
		 */
		$groupby = apply_filters_ref_array( 'wzpa_query_groupby', array( $groupby, $args ) );

		/**
		 * Filters the ORDER BY clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $orderby The ORDER BY clause of the query.
		 * @param array  $args    Arguments array.
		 */
		$orderby = apply_filters_ref_array( 'wzpa_query_orderby', array( $orderby, $args ) );

		/**
		 * Filters the LIMIT clause of the query.
		 *
		 * @since 1.2.0
		 *
		 * @param string $limits The LIMIT clause of the query.
		 * @param array  $args   Arguments array.
		 */
		$limits = apply_filters_ref_array( 'wzpa_query_limits', array( $limits, $args ) );

		// Create the mySQL statement.
		$sql = "SELECT $fields FROM {$wpdb->users} $join WHERE 1=1 $where $groupby $orderby $limits";

		$results = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		/**
		 * Filter object containing list of popular authors and corresponding view counts.
		 *
		 * @since 1.0.0
		 *
		 * @param object $results List of popular authors and corresponding view counts.
		 * @param array  $args    Arguments list.
		 */
		return apply_filters( 'wzpa_get_popular_author_ids', $results, $args );
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
	public static function list_popular_authors_args( $args = array() ) {

		$defaults = array(
			'number'           => '',
			'daily'            => false,
			'daily_range'      => null,
			'hour_range'       => null,
			'offset'           => '',
			'optioncount'      => true,
			'exclude_admin'    => false,
			'show_fullname'    => false,
			'show_avatar'      => false,
			'hide_empty'       => true,
			'cache'            => true,
			'echo'             => true,
			'include'          => '',
			'exclude'          => '',
			'before_list'      => '<ul>',
			'after_list'       => '</ul>',
			'before_list_item' => '<li>',
			'after_list_item'  => '</li>',
			'styles'           => '',
		);

		if ( function_exists( 'tptn_get_settings' ) ) {
			$tptn_settings = \tptn_get_settings();

			foreach ( $defaults as $option => $value ) {
				if ( isset( $tptn_settings[ "wzpa_{$option}" ] ) ) {
					$defaults[ $option ] = $tptn_settings[ "wzpa_{$option}" ];
				} else {
					$defaults[ $option ] = \tptn_get_default_option( "wzpa_{$option}" );
				}
			}
		}

		return wp_parse_args( $args, $defaults );
	}


	/**
	 * Retrieve the avatar `<img>` tag for a user, email address, MD5 hash, comment, or post.
	 *
	 * @since 1.1.0
	 * @see get_avatar()
	 *
	 * @param \WP_User|int $author Author's \WP_User object or user ID.
	 * @param array        $args {
	 *      Optional. Arguments to retrieve the avatar.
	 *
	 *     @type int          $size          Height and width of the avatar image file in pixels. Default 96.
	 *     @type string       $default       URL for the default image or a default type. Default is the value of the
	 *                                       'avatar_default' option, with a fallback of 'mystery'.
	 *     @type string       $alt           Alternative text to use in img tag. Default empty.
	 *     @type int          $height        Display height of the avatar in pixels. Defaults to $size.
	 *     @type int          $width         Display width of the avatar in pixels. Defaults to $size.
	 *     @type bool         $force_default Whether to always show the default image, never the Gravatar. Default false.
	 *     @type string       $rating        What rating to display avatars up to. Accepts 'G', 'PG', 'R', 'X', and are
	 *                                       judged in that order. Default is the value of the 'avatar_rating' option.
	 *     @type string       $scheme        URL scheme to use. See set_url_scheme() for accepted values.
	 *                                       Default null.
	 *     @type array|string $class         Array or string of additional classes to add to the img element.
	 *                                       Default null.
	 *     @type bool         $force_display Whether to always show the avatar - ignores the show_avatars option.
	 *                                       Default false.
	 *     @type string       $loading       Value for the `loading` attribute.
	 *                                       Default null.
	 *     @type string       $extra_attr    HTML attributes to insert in the IMG element. Is not sanitized. Default empty.
	 * }
	 * @return string|false `<img>` tag for the user's avatar. False on failure.
	 */
	public static function get_avatar( $author, $args = array() ) {
		$defaults = array(
			'size'          => 96,
			'height'        => null,
			'width'         => null,
			'default'       => get_option( 'avatar_default', 'mystery' ),
			'force_default' => false,
			'rating'        => get_option( 'avatar_rating' ),
			'scheme'        => null,
			'alt'           => '',
			'class'         => null,
			'force_display' => false,
			'loading'       => null,
			'extra_attr'    => '',
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Arguments for author's avatar. Passed to get_avatar().
		 *
		 * @since 1.1.0
		 *
		 * @param array       $avatar_args Avatar arguments.
		 * @param \WP_User|int $author      Author \WP_User object.
		 */
		$avatar_args = apply_filters( 'wzpa_avatar_args', $args, $author );

		$avatar = get_avatar( $author, $avatar_args['size'], $avatar_args['default'], $avatar_args['alt'], $avatar_args );

		$avatar = $avatar ? $avatar : '';

		/**
		 * Filters the HTML for a user's avatar.
		 *
		 * @since 1.1.0
		 *
		 * @param string      $avatar      HTML for the user's avatar.
		 * @param \WP_User|int $author      Author's \WP_User object or user ID.
		 * @param array       $avatar_args Arguments passed to get_avatar(), after processing.
		 */
		return apply_filters( 'wzpa_get_avatar', $avatar, $author, $avatar_args );
	}
}
