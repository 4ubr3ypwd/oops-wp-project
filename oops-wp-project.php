<?php
/**
 * Plugin Name: oops-wp-project
 * Description:
 * Version:     1.0.0-dev
 * Author:      Aubrey Portwood
 * Author URI:  http://github.com/aubreypwd/oops-wp-project
 * Text Domain: company-slug-project-slug
 * Network:     False
 * License:     GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @since       1.0.0
 * @package     aubreypwd\oops-wp-project
 *
 * Built with:  oops-wp-project
 */

namespace aubreypwd\OopsWpProject;

require_once 'vendor/autoload.php';

/**
 * Project function.
 *
 * @author Aubrey Portwood <code@aubreypwd.com>
 * @since  1.0.0
 *
 * @return \Project Project object.
 */
function project() {
	static $project = null;

	if ( null === $project ) {
		$project = new Project( __FILE__ );

		$project->register_hooks();
		$project->attach_services();
		$project->run();
	}

	return $project;
}

// Wait until WordPress is ready, then go!
add_action( 'plugins_loaded', 'aubreypwd\OopsWpProject\project' );

// When we deactivate this plugin...
register_deactivation_hook( __FILE__, array( project(), 'deactivate_plugin' ) );
