<?php
/**
 * Theme Setup
 * This file is loaded using 'after_setup_theme' hook at priority 10
 *
 * @package    Unos
 * @subpackage Theme
 */


/* === WordPress === */


// Automatically add <title> to head.
add_theme_support( 'title-tag' );

// Adds core WordPress HTML5 support.
add_theme_support( 'html5', array( 'script', 'style', 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add theme support for WordPress Custom Logo
add_theme_support( 'custom-logo' );

// Add theme support for WordPress Custom Background
add_theme_support( 'custom-background', array(
	'default-color'      => unos_default_style( 'site_background' ),
	// 'default-image'      => hoot_data()->template_uri . 'images/background.jpg',
	// 'default-repeat'     => 'no-repeat',
	// 'default-position-x' => 'center',
	// 'default-position-y' => 'top',
	// 'default-size'       => 'cover',
	// 'default-attachment' => 'fixed',
) );

// Add theme support for custom headers
add_theme_support( 'custom-header', array(
	'width' => 1440,
	'height' => 500,
	'flex-height' => true,
	'flex-width' => true,
	'default-image' => hoot_data()->template_uri . 'images/header.jpg',
	'header-text' => false
) );

// Adds theme support for WordPress 'featured images'.
add_theme_support( 'post-thumbnails' );

// Automatically add feed links to <head>.
add_theme_support( 'automatic-feed-links' );

// WordPress Jetpack
add_theme_support( 'infinite-scroll', array(
	'type' => apply_filters( 'unos_jetpack_infinitescroll_type', '' ), // scroll or click - currently add support for both
	'container' => apply_filters( 'unos_jetpack_infinitescroll_container', 'content-wrap' ),
	'footer' => false,
	'wrapper' => true,
	'render' => apply_filters( 'unos_jetpack_infinitescroll_render', 'unos_jetpack_infinitescroll_render' ),
) );


/* === WooCommerce Plugin === */


// Woocommerce support and init load theme woo functions
if ( class_exists( 'WooCommerce' ) ) {
	add_theme_support( 'woocommerce' );
	if ( file_exists( hoot_data()->template_dir . 'woocommerce/functions.php' ) )
		include_once( hoot_data()->template_dir . 'woocommerce/functions.php' );
}


/** One click demo import **/

// Disable branding
add_filter( 'pt-ocdi/disable_pt_branding', 'unos_disable_pt_branding' );
function unos_disable_pt_branding() {
	return true;
}


/* === Hootkit Plugin === */


// Load theme's Hootkit functions if plugin is active
if ( class_exists( 'HootKit' ) && file_exists( hoot_data()->template_dir . 'hootkit/functions.php' ) )
	include_once( hoot_data()->template_dir . 'hootkit/functions.php' );


/* === Tribe The Events Calendar Plugin === */


// Load support if plugin active
if ( class_exists( 'Tribe__Events__Main' ) ) {

	// Hook into 'wp' to use conditional hooks
	add_action( 'wp', 'unos_tribeevent', 10 );

	// Add hooks based on view
	// @since 2.7.3
	function unos_tribeevent() {
		if ( is_post_type_archive( 'tribe_events' ) || ( function_exists( 'tribe_is_events_home' ) && tribe_is_events_home() ) ) {
			add_filter( 'theme_mod_archive_type', 'unos_tribeevent_archivetype', 5 );
			add_filter( 'theme_mod_archive_post_content', 'unos_tribeevent_archive', 5 );
			add_filter( 'theme_mod_archive_post_meta', 'unos_tribeevent_archive_postmeta', 5 );
			add_action( 'unos_display_loop_meta', 'unos_tribeevent_loopmeta', 5 );
		}
		if ( is_singular( 'tribe_events' ) ) {
			add_action( 'unos_display_loop_meta', 'unos_tribeevent_loopmeta_single', 5 );
		}
	}

	// Modify theme options and displays
	// @since 2.7.3
	function unos_tribeevent_archivetype( $type ) { return 'big'; }
	function unos_tribeevent_archive( $content ) { return 'full-content'; }
	function unos_tribeevent_archive_postmeta( $args ) { return ''; }
	function unos_tribeevent_loopmeta( $display ) { return false; }
	function unos_tribeevent_loopmeta_single( $display ) {
		the_post(); rewind_posts(); // Bug Fix
		return false;
	}

}


/* === AMP Plugin ===
 * @ref https://wordpress.org/plugins/amp/
 * @ref https://www.hostinger.in/tutorials/wordpress-amp/
 * @ref https://validator.ampproject.org/
 * @ref https://amp.dev/documentation/guides-and-tutorials/learn/validation-workflow/validation_errors/
 * @credit https://amp-wp.org/documentation/developing-wordpress-amp-sites/how-to-develop-with-the-amp-plugin/
 * @credit https://amp-wp.org/documentation/how-the-plugin-works/amp-plugin-serving-strategies/
*/
// Call 'is_amp_endpoint' after 'parse_query' hook
add_action( 'wp', 'unos_amp', 5 );
function unos_amp(){
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		add_action( 'wp_enqueue_scripts', 'unos_amp_remove_scripts', 999 );
		add_filter( 'hoot_attr_body', 'unos_amp_attr_body' );
		add_filter( 'theme_mod_mobile_submenu_click', 'unos_amp_emptymod' );
		// add_filter( 'theme_mod_custom_js', 'unos_amp_emptymod' );
	}
}
function unos_amp_remove_scripts(){
	$dequeue = array_map( 'wp_dequeue_script', array(
		'comment-reply', 'jquery', 'hoverIntent', 'jquery-superfish', 'jquery-fitvids', 'jquery-parallax', 'resizesensor', 'jquery-theia-sticky-sidebar',
		'hoot-theme', 'hoot-theme-premium',
		'jquery-lightGallery', 'jquery-isotope',
		'jquery-waypoints', 'jquery-waypoints-sticky', 'hoot-scroller',
		'hootkit', 'jquery-lightSlider', 'jquery-circliful',
	) );
}
function unos_amp_attr_body( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? ' hootamp' : $attr['class'] . ' hootamp';
	return $attr;
}
function unos_amp_emptymod(){
	return 0;
}


/* === Breadcrumb NavXT Plugin === */


// Load support if plugin active
if ( class_exists( 'bcn_breadcrumb' ) ) {

	// Enclose pretext in span
	add_filter( 'bcn_widget_pretext', 'unos_bcn_pretext' );

	// Enclose pretext in span
	// @since 2.7.3
	function unos_bcn_pretext( $pretext ) {
		if ( empty( $pretext ) ) return '';
		return '<span class="hoot-bcn-pretext">' . $pretext . '</span>';
	}

}


/* === Theme Hooks === */


/**
 * Handle content width for embeds and images.
 * This file is loaded using 'after_setup_theme' hook at priority 10
 */
$GLOBALS['content_width'] = apply_filters( 'unos_content_width', 1440 );

/**
 * Modify the '[...]' Read More Text
 *
 * @since 1.0
 * @return string
 */
function unos_readmoretext( $more ) {
	$read_more = esc_html( hoot_get_mod('read_more') );
	/* Translators: %s is the HTML &rarr; symbol */
	// $read_more = ( empty( $read_more ) ) ? sprintf( __( 'Continue Reading %s', 'unos' ), '&rarr;' ) : $read_more;
	$read_more = ( empty( $read_more ) ) ? __( 'Continue Reading', 'unos' ) : $read_more;
	return $read_more;
}
add_filter( 'hoot_readmoretext', 'unos_readmoretext' );

/**
 * Modify the exceprt length.
 * Make sure to set the priority correctly such as 999, else the default WordPress filter on this function will run last and override settng here.
 *
 * @since 1.0
 * @return void
 */
function unos_custom_excerpt_length( $length ) {
	if ( is_admin() )
		return $length;

	$excerpt_length = intval( hoot_get_mod('excerpt_length') );
	if ( !empty( $excerpt_length ) )
		return $excerpt_length;
	return 50;
}
add_filter( 'excerpt_length', 'unos_custom_excerpt_length', 999 );

/**
 * Register recommended plugins via TGMPA
 *
 * @since 1.0
 * @return void
 */
function unos_tgmpa_plugins() {
	// Array of plugin arrays. Required keys are name and slug.
	// Since source is from the .org repo, it is not required.
	$plugins = apply_filters( 'unos_tgmpa_plugins', array(
		array(
			'name'     => __( '(HootKit) Unos Sliders, Widgets', 'unos' ),
			'slug'     => 'hootkit',
			'required' => false,
		),
	) );
	// Array of configuration settings.
	$config = array(
		'is_automatic' => true,
	);
	// Register plugins with TGM_Plugin_Activation class
	tgmpa( $plugins, $config );
}
add_filter( 'tgmpa_register', 'unos_tgmpa_plugins' );