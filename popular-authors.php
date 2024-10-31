<?php
/**
 * Popular Authors.
 *
 * Display a list of the popular authors. A Top 10 WordPress plugin addon.
 *
 * @package   Popular_Authors
 * @author    Ajay D'Souza
 * @license   GPL-2.0+
 * @link      https://webberzone.com
 * @copyright 2020-2024 WebberZone
 *
 * @wordpress-plugin
 * Plugin Name: Popular Authors
 * Plugin URI:  https://webberzone.com/downlods/popular-authors/
 * Description: Display a list of the popular authors. A Top 10 WordPress plugin addon.
 * Version:     1.2.1
 * Author:      WebberZone
 * Author URI:  https://webberzone.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: popular-authors
 * Domain Path: /languages
 */

namespace WebberZone\Popular_Authors;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Popular Authors Plugin Version
 *
 * @since 1.1.0
 */
if ( ! defined( 'POP_AUTHOR_VERSION' ) ) {
	define( 'POP_AUTHOR_VERSION', '1.2.1' );
}

/**
 * Holds the filesystem directory path (with trailing slash) for Popular Authors
 *
 * @since 1.1.0
 */
if ( ! defined( 'POP_AUTHOR_PLUGIN_DIR' ) ) {
	define( 'POP_AUTHOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Holds the filesystem directory path (with trailing slash) for Popular Authors
 *
 * @since 1.1.0
 */
if ( ! defined( 'POP_AUTHOR_PLUGIN_URL' ) ) {
	define( 'POP_AUTHOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Holds the filesystem directory path (with trailing slash) for Popular Authors
 *
 * @since 1.1.0
 */
if ( ! defined( 'POP_AUTHOR_PLUGIN_FILE' ) ) {
	define( 'POP_AUTHOR_PLUGIN_FILE', __FILE__ );
}

// Load the autoloader.
require_once POP_AUTHOR_PLUGIN_DIR . 'includes/autoloader.php';

/**
 * The main function responsible for returning the one true WebberZone Snippetz instance to functions everywhere.
 */
function load_wzpa() {
	\WebberZone\Popular_Authors\Main::get_instance();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\load_wzpa' );
