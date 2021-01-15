<?php
/**
 * Plugin Name:       HootKit
 * Description:       HootKit is a great companion plugin for WordPress themes by wpHoot.
 * Version:           1.2.3
 * Author:            wphoot
 * Author URI:        https://wphoot.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hootkit
 * Domain Path:       /languages
 *
 * @package           Hootkit
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Uncomment the line below to load unminified CSS and JS, and add other developer data to code.
 */
// define( 'HOOT_DEBUG', true );
if ( !defined( 'HOOT_DEBUG' ) && defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
	define( 'HOOT_DEBUG', true );

/**
 * The core plugin class.
 *
 * @since   1.0.0
 * @package Hootkit
 */
if ( ! class_exists( 'HootKit' ) ) :

	class HootKit {

		/**
		 * Plugin version number.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $version = '1.2.3';

		/**
		 * Plugin name.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $name = 'HootKit';

		/**
		 * Plugin slug.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $slug = 'hootkit';

		/**
		 * Plugin strings.
		 *
		 * @since  1.1.0
		 * @access public
		 * @var    string
		 */
		public $strings = array();

		/**
		 * Plugin directory path with trailing slash.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $dir = '';

		/**
		 * Plugin directory URI with trailing slash.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $uri = '';

		/**
		 * Plugin basename
		 *
		 * @since  1.1.0
		 * @access public
		 * @var    string
		 */
		public $plugin_basename = '';

		/**
		 * Config variable.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $config = array();

		/**
		 * Plugin Modules
		 *
		 * @since  1.1.1
		 * @access public
		 * @var    array
		 */
		public $hootkitmods = array();

		/**
		 * Set marker for older theme versions
		 *
		 * @since  1.1.0
		 * @access public
		 * @var    string
		 */
		public $hootdeprecated = false;

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function __construct() {}

		/**
		 * Sets up the plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup() {

			// Set the properties.
			$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->uri = trailingslashit( plugin_dir_url( __FILE__ ) );
			$this->plugin_basename = plugin_basename(__FILE__);
			$this->strings = include( $this->dir . 'include/strings.php' );

			// Setup Hooks
			$this->setup_hooks();

			// Run on plugin activation
			register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );

		}

		/**
		 * Sets up the plugin hooks.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup_hooks() {

			// Load Text Domain
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			// -> Register theme's HootKit configuration after theme has loaded (so it can hook in to alter Hootkit configs)
			// -> init hook may be a bit late for us to load since 'widgets_init' is used to intialize widgets
			//    (unless we hook into init at 0, which is a bit messy)
			add_action( 'after_setup_theme', array( $this, 'themeregister' ), 90 );

			// Set active modules and launch the plugin
			add_action( 'after_setup_theme', array( $this, 'setactivemodules' ), 93 );
			add_action( 'after_setup_theme', array( $this, 'loadplugin' ), 95 );

			// Add admin settings
			add_action( 'init', array( $this, 'admin_settings' ) );

		}

		/**
		 * Run when plugin is activated
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function plugin_activate() {
			add_option( 'hootkit-activate', time() );
		}

		/**
		 * Load Plugin Text Domain
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				$this->slug,
				false,
				basename( dirname( __FILE__ ) ) . '/languages/' // dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

		/**
		 * Register the theme's cofiguration
		 * This function is hooked to 'init' action so that themes can register their supported configuration settings
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function themeregister() {

			/* Set marker for older theme versions */
			$this->hootdeprecated = ( class_exists( 'Hoot_Theme' ) || class_exists( 'Hootubix_Theme' ) || class_exists( 'Maghoot_Theme' ) || class_exists( 'Dollah_Theme' ) ) ? true : false;

			/* Set hootkit plugin modules and supported settings */
			$this->hootkitmods = array(
				'modules' => array(
					// Slider Widgets
					'slider-image' => array(
						'requires' => array( 'widget', 'slider' ),
						'sets'     => array( 'sliders' ),
						'desc'     => '',
					),
					'carousel' => array(
						'requires' => array( 'widget', 'slider' ),
						'sets'     => array( 'sliders' ),
						'desc'     => '',
					),
					'ticker' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'sliders' ),
					),
					// Post Widgets
					'content-posts-blocks' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'post' ),
					),
					'post-grid' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'post' ),
					),
					'post-list' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'post' ),
					),
					'postcarousel' => array(
						'requires' => array( 'widget', 'slider' ),
						'sets'     => array( 'sliders', 'post' ),
					),
					'postlistcarousel' => array(
						'requires' => array( 'widget', 'slider' ),
						'sets'     => array( 'sliders', 'post' ),
					),
					'ticker-posts' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'sliders', 'post' ),
					),
					'slider-postimage' => array(
						'requires' => array( 'widget', 'slider' ),
						'sets'     => array( 'sliders', 'post' ),
					),
					// Content Widgets
					'announce' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'profile' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'cta' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'content-blocks' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'content-grid' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'contact-info' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'icon-list' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'notice' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'number-blocks' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'tabs' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'toggle' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					'vcards' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'content' ),
					),
					// Display Widgets
					'buttons' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'display' ),
					),
					'cover-image' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'display' ),
					),
					'icon' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'display' ),
					),
					'social-icons' => array(
						'requires' => array( 'widget' ),
						'sets'     => array( 'display' ),
					),
					// Misc
					'top-banner' => array(
						'requires' => array( 'customize' ),
						'sets'     => array( 'misc' ),
					),
					'shortcode-timer' => array(
						'requires' => array( 'shortcode' ),
						'sets'     => array( 'misc' ),
					),
					// Woo
					'fly-cart' => array(
						'requires' => array( 'customize', 'woocommerce' ),
						'sets'     => array( 'woocom', 'misc' ),
					),
					'products-carticon' => array(
						'requires' => array( 'widget', 'woocommerce' ),
						'sets'     => array( 'woocom' ),
					),
					'content-products-blocks' => array(
						'requires' => array( 'widget', 'woocommerce' ),
						'sets'     => array( 'woocom' ),
					),
					'product-list' => array(
						'requires' => array( 'widget', 'woocommerce' ),
						'sets'     => array( 'woocom' ),
					),
					'productcarousel' => array(
						'requires' => array( 'widget', 'slider', 'woocommerce' ),
						'sets'     => array( 'sliders', 'woocom' ),
					),
					'productlistcarousel' => array(
						'requires' => array( 'widget', 'slider', 'woocommerce' ),
						'sets'     => array( 'sliders', 'woocom' ),
					),
					'products-ticker' => array(
						'requires' => array( 'widget', 'woocommerce' ),
						'sets'     => array( 'sliders', 'woocom' ),
					),
					'products-search' => array(
						'requires' => array( 'widget', 'woocommerce' ),
						'sets'     => array( 'woocom' ),
					),
				),

				// 'grid-widget' : HootKit <= 1.1.3 // @todo remove in future version
				'supports'    => array( 'cta-styles', 'content-blocks-style5', 'content-blocks-style6', 'slider-styles', 'widget-subtitle', 'grid-widget', 'list-widget' ),
			);

			/* Let themes modify the strings array */
			$this->strings = wp_parse_args( apply_filters( 'hootkit_strings', array() ), $this->strings );

			/* Let themes modify the config array */
			$themeconfig = apply_filters( 'hootkit_register', array() );
			/* Restructure array to new format */
			if ( !empty( $themeconfig['modules'] ) && is_array( $themeconfig['modules'] ) ) {
				if ( !empty( $themeconfig['modules']['sliders'] ) ) {
					foreach ( $themeconfig['modules']['sliders'] as $slkey => $rename ) {
						if ( $rename == 'image' || $rename == 'postimage' )
							$themeconfig['modules']['sliders'][$slkey] = 'slider-' . $rename;
					}
				}
				$newthemeconfig = array();
				foreach ( $themeconfig['modules'] as $mergearray ) $newthemeconfig = array_merge( $newthemeconfig, $mergearray );
				$themeconfig['modules'] = $newthemeconfig;
			}

			$this->config = wp_parse_args( $themeconfig, array(
				/** Set true for all non wphoot themes **/
				'nohoot'    => true,
				/** If theme is loading its own css, hootkit wont load its own default styles **/
				'theme_css' => false,
				/** Theme Supported Modules **/
				'modules'   => array( 'slider-image', 'slider-postimage', 'announce', 'content-blocks', 'content-posts-blocks', 'cta', 'icon', 'post-grid', 'post-list', 'social-icons', 'ticker', 'content-grid', 'cover-image', ), // @todo 'ticker' width bug: css width percentage does not work inside table/flex layout => theme should remove support if theme markup does not explicitly support this (i.e. max-width provided for ticker boxes inside table cells)
				/** Theme Supported Modules which are active by default (before user settings saved) **/
				/** Leave empty for all active by default; Set to bool (false) for none active **/
				'default_activemodules' => array(),
				/** Placeholder - User active modules preference **/
				'activemodules'         => array(),
				'activemodulegroups'    => array(),
				/** Misc theme specific settings // Unos <= 2.7.1 // @todo remove in future version **/
				'settings' => array(),
				/** Misc theme specific settings **/
				'supports' => array(),
				/** Premium modules list **/
				'premium' => array(),
				/** wpHoot Themes **/
				'themelist' => array(
					'chromatic',		'dispatch',			'responsive-brix',
					'brigsby',			'creattica',
					'metrolo',			'juxter',			'divogue',
					'hoot-ubix',		'magazine-hoot',	'dollah',
					'hoot-business',	'hoot-du',
					'unos',				'unos-publisher',	'unos-magazine-vu',
					'unos-business',	'unos-glow',		'unos-magazine-black',
					'unos-storebell',	'unos-minimastore',
					'nevark',			'neux',				'magazine-news-byte',
				),
				/** Default Styles **/
				'presets'   => array(
					'white'  => $this->get_string('white'),
					'black'  => $this->get_string('black'),
					'brown'  => $this->get_string('brown'),
					'blue'   => $this->get_string('blue'),
					'cyan'   => $this->get_string('cyan'),
					'green'  => $this->get_string('green'),
					'yellow' => $this->get_string('yellow'),
					'amber'  => $this->get_string('amber'),
					'orange' => $this->get_string('orange'),
					'red'    => $this->get_string('red'),
					'pink'   => $this->get_string('pink'),
				),
				/** Default Styles **/
				'presetcombo'   => array(
					'white'        => $this->get_string('white'),
					'black'        => $this->get_string('black'),
					'brown'        => $this->get_string('brown'),
					'brownbright'  => $this->get_string('brownbright'),
					'blue'         => $this->get_string('blue'),
					'bluebright'   => $this->get_string('bluebright'),
					'cyan'         => $this->get_string('cyan'),
					'cyanbright'   => $this->get_string('cyanbright'),
					'green'        => $this->get_string('green'),
					'greenbright'  => $this->get_string('greenbright'),
					'yellow'       => $this->get_string('yellow'),
					'yellowbright' => $this->get_string('yellowbright'),
					'amber'        => $this->get_string('amber'),
					'amberbright'  => $this->get_string('amberbright'),
					'orange'       => $this->get_string('orange'),
					'orangebright' => $this->get_string('orangebright'),
					'red'          => $this->get_string('red'),
					'redbright'    => $this->get_string('redbright'),
					'pink'         => $this->get_string('pink'),
					'pinkbright'   => $this->get_string('pinkbright'),
				),
			) );

			/* Sanitize Theme Supported Modules against HootKit modules and arrange in order of hootkitmods */
			$modarray = array();
			if ( !empty( $this->config['modules'] ) && is_array( $this->config['modules'] ) ) {
				foreach ( $this->hootkitmods['modules'] as $modname => $modsettings ) {
					if ( in_array( $modname, $this->config['modules'] ) )
						$modarray[] = $modname;
				}
			}
			$this->config['modules'] = $modarray;

			/* Sanitize Theme Supported Premium Modules against HootKit modules */
			$themeslug = ( function_exists( 'hoot_data' ) ) ? hoot_data( 'theme_slug' ) : '';
			if ( !empty( $themeslug ) && in_array( $themeslug, $this->config['themelist'] ) ) {
				if ( !empty( $this->config['premium'] ) && is_array( $this->config['premium'] ) ) {
					foreach ( $this->config['premium'] as $modkey => $modname ) {
						if ( !array_key_exists( $modname, $this->hootkitmods['modules'] ) )
							unset( $this->config['premium'][$modkey] );
					}
				}
			} else {
				$this->config['premium'] = array();
			}

			/* Sanitize Theme specific supported settings against HootKit supported settings */
			if ( !empty( $this->config['supports'] ) && is_array( $this->config['supports'] ) ) {
				foreach ( $this->config['supports'] as $skey => $support ) {
					if ( !in_array( $support, $this->hootkitmods['supports'] ) )
						unset( $this->config['supports'][ $skey ] );
				}
			}

			/* Remove woocommerce modules if plugin is inactive */
			if ( ! class_exists( 'WooCommerce' ) ) {
				foreach ( $this->config['modules'] as $modkey => $modname ) {
					if ( in_array( 'woocommerce', $this->hootkitmods['modules'][$modname]['requires'] ) )
						unset( $this->config['modules'][$modkey] );
				}
			}

		}

		/**
		 * Set User Activated modules
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function setactivemodules() {

			$activemodules = get_option( 'hootkit-activemodules', false );
			/* Restructure array to new format */
			if ( !empty( $activemodules ) ) {
				$newacm = array();
				foreach ( $activemodules as $key => $value ) {
					if ( is_array( $value ) ) $newacm = array_merge( $newacm, $value );
					else $newacm[] = $value;
				}
				$activemodules = $newacm;
				foreach ( $activemodules as $slkey => $rename ) {
					if ( $rename == 'image' || $rename == 'postimage' )
						$activemodules[$slkey] = 'slider-' . $rename;
				}
			}

			if ( $activemodules === false ) {
				// Set default active modules - set to all if empty ; none if bool false
				if ( $this->config['default_activemodules'] === false ) $this->config['default_activemodules'] = array();
				elseif ( empty( $this->config['default_activemodules'] ) || !is_array( $this->config['default_activemodules'] ) ) $this->config['default_activemodules'] = $this->config['modules'];

				$this->config['activemodules'] = $this->config['default_activemodules'];
			} else {
				$this->config['activemodules'] = $activemodules;
			}

			$activesliders = $activewidgets = $activecustomize = $activeshortcode = $activewoocommerce = array();
			foreach ( $this->config['activemodules'] as $modkey => $modname ) {
				if ( empty( $this->hootkitmods['modules'][$modname] ) || empty( $this->hootkitmods['modules'][$modname]['requires'] ) ) continue;
				if ( in_array( 'slider',      $this->hootkitmods['modules'][$modname]['requires'] ) ) $activesliders[] = $modname;
				if ( in_array( 'widget',      $this->hootkitmods['modules'][$modname]['requires'] ) ) $activewidgets[] = $modname;
				if ( in_array( 'customize',   $this->hootkitmods['modules'][$modname]['requires'] ) ) $activecustomize[] = $modname;
				if ( in_array( 'woocommerce', $this->hootkitmods['modules'][$modname]['requires'] ) ) $activewoocommerce[] = $modname;
				if ( in_array( 'shortcode',   $this->hootkitmods['modules'][$modname]['requires'] ) ) $activeshortcode[] = $modname;
			}
			$this->config['activemodulegroups'] = array(
				'activesliders'     => $activesliders,
				'activewidgets'     => $activewidgets,
				'activecustomize'   => $activecustomize,
				'activeshortcode'   => $activeshortcode,
				'activewoocommerce' => $activewoocommerce,
			);
		}

		/**
		 * Load the supported module files
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function loadplugin() {

			if ( $this->hootdeprecated ) return;

			/* Set activation for version prior to 1.1.0 */
			if ( is_admin() && empty( get_option( 'hootkit-activate' ) ) )
				add_option( 'hootkit-activate', time() - ( 7 * 24 * 60 * 60 ) );

			/* Load Limited Core/Helper Functions */
			// Template Functions - may be required in admin for creating live preview eg. so page builder
			require_once( $this->dir . 'include/template-functions.php' );
			// Admin Functions
			if ( is_admin() ) require_once( $this->dir . 'admin/functions.php' );
			// if ( is_admin() ) require_once( $this->dir . 'admin/notice.php' );

			/* Load Limited Library for Non Hoot themes :: some deprecated theme versions 'may' have nohoot set to true */
			if ( $this->get_config( 'nohoot' ) ) {
				require_once( $this->dir . 'include/hoot-library.php' );
				require_once( $this->dir . 'include/hoot-library-icons.php' );
			}

			/* Register/Enqueue Scripts and styles */
			add_action( 'wp_enqueue_scripts',    array( $this, 'wp_register' )   , 0  );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_register' ), 0  );
			add_action( 'wp_enqueue_scripts',    array( $this, 'wp_enqueue' )    , 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) , 10 );

			/* Module Groups */
			extract( $this->config['activemodulegroups'] );

			/* Load Files */
			if ( !empty( $activewidgets ) ) {
				require_once( $this->dir . 'admin/widgets.php' );
			}
			if ( !empty( $activecustomize ) ) {
				require_once( $this->dir . 'admin/customizer.php' );
			}

			/* Load Active Sliders */
			if ( !empty( $activesliders ) ) {
				foreach ( $activesliders as $slider ) {
					$slider = preg_replace('/^slider-/', '', $slider);
					if ( file_exists( $this->dir . 'admin/slider-' . sanitize_file_name( $slider ) . '.php' ) ) {
						require_once( $this->dir . 'admin/slider-' . sanitize_file_name( $slider ) . '.php' );
					}
				}
			}

			/* Load Active Widgets */
			if ( !empty( $activewidgets ) ) {
				foreach ( $activewidgets as $widget ) {
					$widget = preg_replace('/^widget-/', '', $widget);
					if ( file_exists( $this->dir . 'admin/widget-' . sanitize_file_name( $widget ) . '.php' ) ) {
						require_once( $this->dir . 'admin/widget-' . sanitize_file_name( $widget ) . '.php' );
					}
				}
			}

			/* Load Active Woocommerce Modules */
			if ( !empty( $activewoocommerce ) ) {
				foreach ( $activewoocommerce as $wc ) {
					$wc = preg_replace('/^wc-/', '', $wc);
					if ( file_exists( $this->dir . 'admin/woocommerce-' . sanitize_file_name( $wc ) . '.php' ) ) {
						require_once( $this->dir . 'admin/woocommerce-' . sanitize_file_name( $wc ) . '.php' );
					}
				}
			}

			/* Load Active Customize Modules */
			if ( !empty( $activecustomize ) ) {
				foreach ( $activecustomize as $customize ) {
					$customize = preg_replace('/^customize-/', '', $customize);
					if ( file_exists( $this->dir . 'admin/customize-' . sanitize_file_name( $customize ) . '.php' ) ) {
						require_once( $this->dir . 'admin/customize-' . sanitize_file_name( $customize ) . '.php' );
					}
				}
			}

			/* Load Active Shortcodes */
			if ( !empty( $activeshortcode ) ) {
				foreach ( $activeshortcode as $shortcode ) {
					$shortcode = preg_replace('/^shortcode-/', '', $shortcode);
					if ( file_exists( $this->dir . 'admin/shortcode-' . sanitize_file_name( $shortcode ) . '.php' ) ) {
						require_once( $this->dir . 'admin/shortcode-' . sanitize_file_name( $shortcode ) . '.php' );
					}
				}
			}

		}

		/**
		 * Add admin settings
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function admin_settings() {
			if ( $this->hootdeprecated ) return;
			require_once( $this->dir . 'admin/settings.php' );
			$hootkit_settings = HootKit_Settings::get_instance();
		}

		/**
		 * Register Scripts and Styles
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function wp_register() {

			extract( $this->config['activemodulegroups'] );

			// Register Styles
			if ( !empty( $activesliders ) )
				wp_register_style( 'lightSlider', $this->asset_uri( 'assets/lightSlider', 'css' ), false, '1.1.2' );
			wp_register_style( 'font-awesome', $this->asset_uri( 'assets/font-awesome', 'css' ), false, '5.0.10' );
			wp_register_style( $this->slug, $this->asset_uri( 'assets/hootkit', 'css' ), array(), $this->version, 'all' );

			// Register Script
			if ( !empty( $activesliders ) )
				wp_register_script( 'jquery-lightSlider', $this->asset_uri( 'assets/jquery.lightSlider', 'js' ), array( 'jquery' ), '1.1.2', true );
			if ( in_array( 'number-blocks', $activewidgets ) )
				wp_register_script( 'jquery-circliful', $this->asset_uri( 'assets/jquery.circliful', 'js' ), array( 'jquery' ), '20160309', true );
			wp_register_script( $this->slug, $this->asset_uri( 'assets/hootkit', 'js' ), array( 'jquery' ), $this->version, true );

		}

		/**
		 * Enqueue Scripts and Styles
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function wp_enqueue() {

			extract( $this->config['activemodulegroups'] );

			// Enqueue Styles
			if ( !empty( $activesliders ) ) wp_enqueue_style( 'lightSlider' );
			wp_enqueue_style( 'font-awesome' );
			if( !$this->get_config( 'theme_css' ) ) wp_enqueue_style( $this->slug );

			// Enqueue Scripts
			if ( !empty( $activesliders ) ) wp_enqueue_script( 'jquery-lightSlider' );
			if ( in_array( 'number-blocks', $activewidgets ) ) wp_enqueue_script( 'jquery-circliful' ); // ::=> Hootkit does not load Waypoints. It is upto the theme to deploy waypoints.
			wp_enqueue_script( $this->slug );

			// Localize Script
			wp_localize_script( $this->slug, 'hootkitData', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		}

		/**
		 * Register admin Scripts and Styles
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function admin_register( $hook ) {

			// Register Styles
			wp_register_style( $this->slug . '-widgets', $this->asset_uri( 'admin/assets/widgets', 'css' ), array(), $this->version, 'all' );
			wp_register_style( 'select2', $this->asset_uri( 'admin/assets/select2', 'css' ), array(), '4.0.7', 'all' );
			wp_register_style( 'font-awesome', $this->asset_uri( 'assets/font-awesome', 'css' ), false, '5.0.10' );

			// Register Script
			wp_register_script( $this->slug . '-widgets', $this->asset_uri( 'admin/assets/widgets', 'js' ), array( 'jquery', 'select2', 'wp-color-picker' ), $this->version, true );
			wp_register_script( 'select2', $this->asset_uri( 'admin/assets/select2', 'js' ), array( 'jquery' ), '4.0.7', true );

		}

		/**
		 * Enqueue admin Scripts and Styles
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function admin_enqueue( $hook ) {

			extract( $this->config['activemodulegroups'] );

			// Enqueue for Active modules
			if ( !empty( $activewidgets ) ) {

				// SiteOrigin Page Builder compatibility - Load widget assets for SiteOrigin Page Builder plugin on Edit screens
				$widgetload = ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( defined( 'SITEORIGIN_PANELS_VERSION' ) && version_compare( SITEORIGIN_PANELS_VERSION, '2.0' ) >= 0 ) ) ? true : false;

				if ( 'widgets.php' == $hook || $widgetload ) {

					// Enqueue Styles
					wp_enqueue_style( 'font-awesome' );
					wp_enqueue_style( 'select2' );
					wp_enqueue_style( $this->slug . '-widgets' );
					wp_enqueue_style( 'wp-color-picker' );

					// Enqueue Script
					wp_enqueue_media();
					wp_enqueue_script( 'select2' );
					wp_enqueue_script( $this->slug . '-widgets' );

				}

			}

			// SiteOrigin Page Builder compatibility - Load css for Live Preview in backend
			// > Limitation: dynamic css is not loaded // @todo test all widgets (inc sliders)
			// if( $widgetload && $this->get_config( 'theme_css' ) && function_exists( 'hoot_locate_style' ) ) {
			// 	wp_enqueue_style( 'theme-hootkit', hoot_data()->template_uri . 'hootkit/hootkit.css' );
			// 	// wp_enqueue_style( 'theme-style', hoot_data()->template_uri . 'style.css' ); // Loads all styles including headings, grid etc -> Not Needed // Loads grid etc for widget post grid etc -> Needed
			// }

		}

		/**
		 * Get asset file uri
		 *
		 * @since  1.0.0
		 * @access public
		 * @param string $location
		 * @param string $type
		 * @return string
		 */
		public function asset_uri( $location, $type ) {

			$location = str_replace( array( $this->dir, $this->uri ), '', $location );

			// Return minified uri if not in debug mode and minified file is available
			if (
				( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) &&
				( ! defined( 'HOOT_DEBUG'   ) || ! HOOT_DEBUG   ) &&
				( file_exists( $this->dir . "{$location}.min.{$type}" ) )
			) {
				return $this->uri . "{$location}.min.{$type}";
			}

			// Return uri if file is available
			if ( file_exists( $this->dir . "{$location}.{$type}" ) )
				return $this->uri . "{$location}.{$type}";
			elseif ( file_exists( $this->dir . "{$location}.min.{$type}" ) ) // debug true, but unminified doesnt exist
				return $this->uri . "{$location}.min.{$type}";

			return '';

		}

		/**
		 * Get String values.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $key
		 * @param  string $default
		 * @return string
		 */
		public function get_string( $key, $default = '' ) {
			$return = '';
			if ( !is_array( $this->strings ) ) {
				$return = '';
			} else {
				$return = ( !empty( $this->strings[ $key ] ) ? $this->strings[ $key ] : '' );
			}
			if ( !empty( $return ) && is_string( $return ) )
				return $return;
			elseif ( !empty( $default ) && is_string( $default ) )
				return $default;
			else return ucwords( str_replace( array( '-', '_' ), ' ' , $key ) );
		}

		/**
		 * Get Config values.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $key    Config value to return / else return entire array
		 * @param  string $subkey Check for $subkey if config value is an array
		 * @return mixed
		 */
		public function get_config( $key = '', $subkey = '' ) {

			// Early Check in case config has changed
			if ( !is_array( $this->config ) )
				return array();

			// Return the value
			if ( $key && is_string( $key ) ) {
				if ( isset( $this->config[ $key ] ) ) {
					if ( $subkey && ( is_string( $subkey ) || is_integer( $subkey ) ) ) {
						return ( isset( $this->config[ $key ][ $subkey] ) ) ? $this->config[ $key ][ $subkey ] : array();
					} else {
						return $this->config[ $key ];
					}
				} else {
					return array();
				}
			} else {
				return $this->config;
			}

		}

		/**
		 * Get HootKit modules
		 *
		 * @since  1.2.0
		 * @access public
		 * @param  string $key
		 * @return mixed
		 */
		public function get_hootkitmods( $key = '' ) {
			if ( $key && is_string( $key ) ) {
				if ( isset( $this->hootkitmods[ $key ] ) )
					return $this->hootkitmods[ $key ];
				else
					return array();
			} else {
				return $this->hootkitmods;
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			static $instance = null;

			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup();
			}

			return $instance;
		}

	}

	/**
	 * Gets the instance of the `HootKit` class. This function is useful for quickly grabbing data
	 * used throughout the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	function hootkit() {
		return HootKit::get_instance();
	}

	// Lets roll!
	hootkit();

endif;