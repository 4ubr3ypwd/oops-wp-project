<?php

namespace aubreypwd\OopsWpProject;

class Project implements \WebDevStudios\OopsWP\Utility\Hookable {

	/**
	 * Plugin basename.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @var    string
	 * @since 1.0.0
	 */
	public $basename = '';

	/**
	 * URL of plugin directory.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @var    string
	 * @since 1.0.0
	 */
	public $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @var    string
	 * @since 1.0.0
	 */
	public $path = '';

	/**
	 * Is WP_DEBUG set?
	 *
	 * @since 1.0.0
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 *
	 * @var boolean
	 */
	public $wp_debug = false;

	/**
	 * The plugin file.
	 *
	 * @since 1.0.0
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 *
	 * @var string
	 */
	public $plugin_file = '';

	/**
	 * The plugin headers.
	 *
	 * @since 1.0.0
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 *
	 * @var array
	 */
	public $plugin_headers = [];

	/**
	 * Construct.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @param string $plugin_file The plugin file, usually __FILE__ of the base plugin.
	 *
	 * @throws Exception If $plugin_file parameter is invalid (prevents plugin from loading).
	 */
	public function __construct( $plugin_file ) {

		// Check input validity.
		if ( empty( $plugin_file ) || ! stream_resolve_include_path( $plugin_file ) ) {

			// Translators: Displays a message if a plugin file is not passed.
			throw new Exception( sprintf( esc_html__( 'Invalid plugin file %1$s supplied to %2$s', 'company-slug-project-slug' ), $plugin_file, __METHOD__ ) );
		}

		// Plugin setup.
		$this->plugin_file = $plugin_file;
		$this->basename    = plugin_basename( $plugin_file );
		$this->url         = plugin_dir_url( $plugin_file );
		$this->path        = plugin_dir_path( $plugin_file );
		$this->wp_debug    = defined( 'WP_DEBUG' ) && WP_DEBUG;

		// Plugin information.
		$this->plugin_headers = get_file_data( $plugin_file, array(
			'Plugin Name' => 'Plugin Name',
			'Description' => 'Description',
			'Version'     => 'Version',
			'Author'      => 'Author',
			'Author URI'  => 'Author URI',
			'Text Domain' => 'Text Domain',
			'Network'     => 'Network',
			'License'     => 'License',
			'License URI' => 'License URI',
		), 'plugin' );

		// Load language files.
		load_plugin_textdomain( 'company-slug-project-slug', false, basename( dirname( $plugin_file ) ) . '/languages' );
	}

	/**
	 * Get the plugin version.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @return string The version of this plugin.
	 */
	public function version() {
		return $this->header( 'Version' );
	}

	/**
	 * Get a header.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @param  string $header The header you want, e.g. Version, Author, etc.
	 * @return string         The value of the header.
	 */
	public function header( $header ) {
		return isset( $this->plugin_headers[ $header ] )
			? trim( (string) $this->plugin_headers[ $header ] )
			: '';
	}

	/**
	 * Load and attach app services to the app class.
	 *
	 * To add a new service go add a new class to e.g. `services/my-service/class-my-service.php`,
	 * then add it below like:
	 *
	 *     $this->my_service = new My_Service();
	 *
	 * The app will autoload it, run hooks and run methods automatically.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	public function attach_services() {
		$this->attach_service( 'example_service', new Services\Example_Service\Example_Service() );
	}

	/**
	 * Load a service.
	 *
	 * @author Aubrey Portwood <aubrey@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param  string $property The attachment name.
	 * @param  object $object   Service object.
	 *
	 * @throws \Exception If $object does not use \WebDevStudios\OopsWP\Structure\Service.
	 */
	private function attach_service( string $property, $object ) {
		if ( ! is_object( $object ) || ! is_subclass_of( $object, '\WebDevStudios\OopsWP\Structure\Service' ) ) {
			throw new \Exception( '$object must extend \WebDevStudios\OopsWP\Structure\Service' );
		}

		// Attach and load the service.
		$this->property = $object;
		$this->property->register_hooks();
		$this->property->run();
	}

	/**
	 * Fire hooks!
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	public function hooks() {
		$this->auto_call_hooks(); // If you want to run your own hook methods, just strip this.
	}

	/**
	 * Autoload hooks method.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	private function auto_call_hooks() {
		$this->autocall( 'hooks' );
	}

	/**
	 * Run the app.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	public function run() {
		$this->auto_call_run();
	}

	/**
	 * Automatically call run methods.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	private function auto_call_run() {
		$this->autocall( 'run' );
	}

	/**
	 * Call a property on attached objects.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @param  string $call The call.
	 */
	private function autocall( $call ) {
		foreach ( get_object_vars( $this ) as $prop ) {
			if ( is_object( $prop ) ) {
				if ( method_exists( $prop, $call ) ) {
					$prop->$call();
				}
			}
		}
	}

	/**
	 * This plugin's url.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 *
	 * @param  string $path (Optional) appended path.
	 * @return string       URL and path.
	 */
	public function url( $path ) {
		return is_string( $path ) && ! empty( $path ) ?
			trailingslashit( $this->url ) . $path :
			trailingslashit( $this->url );
	}

	/**
	 * Re-attribute user content to site author.
	 *
	 * @author Aubrey Portwood <code@aubreypwd.com>
	 * @since 1.0.0
	 */
	public function deactivate_plugin() {
		foreach ( get_object_vars( $this ) as $prop ) {
			if ( is_object( $prop ) ) {
				if ( method_exists( $prop, 'deactivate_plugin' ) ) {
					$prop->deactivate_plugin();
				}
			}
		}
	}

	/**
	 * Register Hooks.
	 *
	 * @author Aubrey Portwood <aubrey@webdevstudios.com>
	 * @since 1.0.0
	 */
	public function register_hooks() {
		$this->hooks();
	}
}
