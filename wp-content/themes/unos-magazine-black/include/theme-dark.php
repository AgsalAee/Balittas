<?php
/**
 * This file contains functions and hooks for styling the theme in dark mode
 * This file is loaded at 'after_setup_theme' action @priority 10 ONLY IF user selected dark option in customizer
 *
 * @package    Unos Magazine Black
 */

function unosmbl_enqueue_darkstyle(){
	if ( file_exists( hoot_data()->child_dir . 'style-dark.css' ) )
	wp_enqueue_style( 'unosmbl-dark', hoot_data()->child_uri . 'style-dark.css', array( 'hoot-style', 'unos-hootkit' ), hoot_data()->childtheme_version );
}
add_action( 'wp_enqueue_scripts', 'unosmbl_enqueue_darkstyle', 17 );

function unosmbl_dynamic_css_dark_handle( $handle ) {
	return 'unosmbl-dark';
}
add_filter( 'hoot_style_builder_inline_style_handle', 'unosmbl_dynamic_css_dark_handle', 9 );

function unosmbl_menu_dark_colorset( $set ) {
	return array(
		array( '#e9cb42', '#111111' ),
		array( '#7dc20f', '#111111' ),
		array( '#25b7d1', '#111111' ),
		array( '#ffb22d', '#111111' ),
		array( '#ff503c', '#ffffff' ),
		);
}
add_filter( 'unosmbl_menu_colorset', 'unosmbl_menu_dark_colorset' );

function unosmbl_catblocks_dark_colorset( $set ) {
	return array(
		array( '#ffe42d', '#111111' ),
		array( '#7dc20f', '#111111' ),
		array( '#25b7d1', '#111111' ),
		array( '#ffb22d', '#111111' ),
		array( '#ff503c', '#ffffff' ),
		);
}
add_filter( 'unosmbl_catblocks_colorset', 'unosmbl_catblocks_dark_colorset' );

function unosmbl_dark_default_style( $defaults ){
	$defaults = array_merge( $defaults, array(
		'module_bg_default'    => '#444444',
		'box_background'       => '#333333',
		'site_background'      => '#141414', // Used by WP custom-background

		'menu_icons_color'           => '#ffffff',
		'topbar_background'          => '#333333',
		'header_background'          => '#141414',
		'logo_background'            => '#141414',
		'menu_background'            => '#333333',
		'menu_dropdown_background'   => '#141414',
		'pageheader_background'      => '#3f3f3f',
		'subfooter_background'       => '#222222',
		'footer_background'          => '#222222',
		'topbar_color'               => '#ffffff',
		'font_logo_color'            => '#ffffff',
		'font_tagline_color'         => '#dddddd',
		'font_nav_menu_color'        => '#ffffff',
		'font_nav_dropdown_color'    => '#ffffff',
		'font_body_color'            => '#dddddd',
		'font_h3_style'              => 'uppercase bold',
		'font_h3_color'              => '#ffffff',
		'font_h1_style'              => 'uppercase bold',
		'font_h1_color'              => '#ffffff',
		'font_h2_style'              => 'uppercase bold',
		'font_h2_color'              => '#ffffff',
		'font_h4_style'              => 'uppercase bold',
		'font_h4_color'              => '#ffffff',
		'font_h5_style'              => 'uppercase bold',
		'font_h5_color'              => '#ffffff',
		'font_h6_style'              => 'uppercase bold',
		'font_h6_color'              => '#ffffff',
		'font_sidebar_heading_style' => 'uppercase bold',
		'font_sidebar_heading_color' => '#ffffff',
		'font_sidebar_color'         => '#dddddd',
		'font_footer_heading_style'  => 'uppercase bold',
		'font_footer_heading_color'  => '#ffffff',
		'font_footer_color'          => '#dddddd',

		'font_subheading_color'              => '#999999', // #bbbbbb : same as .entry-byline in parent i.e. 0.8 opacity of body color
	) );
	return $defaults;
}
add_filter( 'unos_default_style', 'unosmbl_dark_default_style', 10 );

function unosmbl_dark_modify_customizer_options( $options ) {
	if ( isset( $options['settings']['headings_fontface_style'] ) )
		$options['settings']['headings_fontface_style']['default'] = 'uppercase';
	return $options;
}
add_filter( 'unos_customizer_options', 'unosmbl_dark_modify_customizer_options', 9 );

function unosmbl_dark_dynamic_cssrules(){
	hoot_add_css_rule( array(
						'selector'  => '#topbar',
						'property'  => array(
							'background' => array( 'rgba(255,255,255,0.13)' ), // #323232 over #141414 (target=>#333)
							'color'      => array( 'inherit' ), // $accent_font
							),
					) );
}
// Priority@6: same as child's, since this file is loaded using 'after_setup_theme' action @priority 10
//             any css rule here will override rules in main functions file
// Priority@6: 5-> base lite ; 7-> base prim prepare (rules removed) ;
//             9-> base prim ; 10-> base hootkit lite/prim
add_action( 'hoot_dynamic_cssrules', 'unosmbl_dark_dynamic_cssrules', 6 );