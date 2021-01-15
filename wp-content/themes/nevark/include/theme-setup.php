<?php
/**
 * Theme Setup
 * This file is loaded using 'after_setup_theme' hook at priority 10
 *
 * @package    Nevark
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
	'default-color'      => nevark_default_style( 'site_background' ),
	'default-image'      => hoot_data()->template_uri . 'images/background.jpg',
	'default-repeat'     => 'no-repeat',
	'default-position-x' => 'center',
	'default-position-y' => 'top',
	'default-size'       => 'cover',
	'default-attachment' => 'fixed',
) );

// Adds theme support for WordPress 'featured images'.
add_theme_support( 'post-thumbnails' );

// Automatically add feed links to <head>.
add_theme_support( 'automatic-feed-links' );

// WordPress Jetpack
add_theme_support( 'infinite-scroll', array(
	'type' => apply_filters( 'nevark_jetpack_infinitescroll_type', '' ), // scroll or click - currently add support for both
	'container' => apply_filters( 'nevark_jetpack_infinitescroll_container', 'content-wrap' ),
	'footer' => false,
	'wrapper' => true,
	'render' => apply_filters( 'nevark_jetpack_infinitescroll_render', 'nevark_jetpack_infinitescroll_render' ),
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
add_filter( 'pt-ocdi/disable_pt_branding', 'nevark_disable_pt_branding' );
function nevark_disable_pt_branding() {
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
	add_action( 'wp', 'nevark_tribeevent', 10 );

	// Add hooks based on view
	// @since 2.7.3
	function nevark_tribeevent() {
		if ( is_post_type_archive( 'tribe_events' ) || ( function_exists( 'tribe_is_events_home' ) && tribe_is_events_home() ) ) {
			add_filter( 'theme_mod_archive_type', 'nevark_tribeevent_archivetype', 5 );
			add_filter( 'theme_mod_archive_post_content', 'nevark_tribeevent_archive', 5 );
			add_filter( 'theme_mod_archive_post_meta', 'nevark_tribeevent_archive_postmeta', 5 );
			add_action( 'nevark_display_loop_meta', 'nevark_tribeevent_loopmeta', 5 );
		}
		if ( is_singular( 'tribe_events' ) ) {
			add_action( 'nevark_display_loop_meta', 'nevark_tribeevent_loopmeta_single', 5 );
		}
	}

	// Modify theme options and displays
	// @since 2.7.3
	function nevark_tribeevent_archivetype( $type ) { return 'big'; }
	function nevark_tribeevent_archive( $content ) { return 'full-content'; }
	function nevark_tribeevent_archive_postmeta( $args ) { return ''; }
	function nevark_tribeevent_loopmeta( $display ) { return false; }
	function nevark_tribeevent_loopmeta_single( $display ) {
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
add_action( 'wp', 'nevark_amp', 5 );
function nevark_amp(){
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		add_action( 'wp_enqueue_scripts', 'nevark_amp_remove_scripts', 999 );
		add_filter( 'hoot_attr_body', 'nevark_amp_attr_body' );
		add_filter( 'theme_mod_mobile_submenu_click', 'nevark_amp_emptymod' );
		// add_filter( 'theme_mod_custom_js', 'nevark_amp_emptymod' );
	}
}
function nevark_amp_remove_scripts(){
	$dequeue = array_map( 'wp_dequeue_script', array(
		'comment-reply', 'jquery', 'hoverIntent', 'jquery-superfish', 'jquery-fitvids', 'jquery-parallax', 'resizesensor', 'jquery-theia-sticky-sidebar',
		'hoot-theme', 'hoot-theme-premium',
		'jquery-lightGallery', 'jquery-isotope',
		'jquery-waypoints', 'jquery-waypoints-sticky', 'hoot-scroller',
		'hootkit', 'jquery-lightSlider', 'jquery-circliful',
	) );
}
function nevark_amp_attr_body( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? ' hootamp' : $attr['class'] . ' hootamp';
	return $attr;
}
function nevark_amp_emptymod(){
	return 0;
}


/* === Breadcrumb NavXT Plugin === */


// Load support if plugin active
if ( class_exists( 'bcn_breadcrumb' ) ) {

	// Enclose pretext in span
	add_filter( 'bcn_widget_pretext', 'nevark_bcn_pretext' );

	// Enclose pretext in span
	// @since 2.7.3
	function nevark_bcn_pretext( $pretext ) {
		if ( empty( $pretext ) ) return '';
		return '<span class="hoot-bcn-pretext">' . $pretext . '</span>';
	}

}


/* === Theme Hooks === */


/**
 * Handle content width for embeds and images.
 * Hooked into 'init' so that we can pull custom content width from theme options
 *
 * @since 1.0
 * @return void
 */
function nevark_set_content_width() {
	$width = intval( hoot_get_mod( 'site_width' ) );
	$width = !empty( $width ) ? $width : 1260;
	$GLOBALS['content_width'] = absint( $width );
}
add_action( 'init', 'nevark_set_content_width', 10 );

/**
 * Modify the '[...]' Read More Text
 *
 * @since 1.0
 * @return string
 */
function nevark_readmoretext( $more ) {
	$read_more = esc_html( hoot_get_mod('read_more') );
	/* Translators: %s is the HTML &rarr; symbol */
	// $read_more = ( empty( $read_more ) ) ? sprintf( __( 'Continue Reading %s', 'nevark' ), '&rarr;' ) : $read_more;
	$read_more = ( empty( $read_more ) ) ? __( 'Continue Reading', 'nevark' ) : $read_more;
	return $read_more;
}
add_filter( 'hoot_readmoretext', 'nevark_readmoretext' );

/**
 * Modify the exceprt length.
 * Make sure to set the priority correctly such as 999, else the default WordPress filter on this function will run last and override settng here.
 *
 * @since 1.0
 * @return void
 */
function nevark_custom_excerpt_length( $length ) {
	if ( is_admin() )
		return $length;

	$excerpt_length = intval( hoot_get_mod('excerpt_length') );
	if ( !empty( $excerpt_length ) )
		return $excerpt_length;
	return 50;
}
add_filter( 'excerpt_length', 'nevark_custom_excerpt_length', 999 );

/**
 * Register recommended plugins via TGMPA
 *
 * @since 1.0
 * @return void
 */
function nevark_tgmpa_plugins() {
	// Array of plugin arrays. Required keys are name and slug.
	// Since source is from the .org repo, it is not required.
	$plugins = apply_filters( 'nevark_tgmpa_plugins', array(
		array(
			'name'     => __( '(HootKit) Nevark Sliders, Widgets', 'nevark' ),
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
add_filter( 'tgmpa_register', 'nevark_tgmpa_plugins' );