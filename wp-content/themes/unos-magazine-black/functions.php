<?php
/**
 *                  _   _             _   
 *  __      ___ __ | | | | ___   ___ | |_ 
 *  \ \ /\ / / '_ \| |_| |/ _ \ / _ \| __|
 *   \ V  V /| |_) |  _  | (_) | (_) | |_ 
 *    \_/\_/ | .__/|_| |_|\___/ \___/ \__|
 *           |_|                          
 *
 * :: Theme's main functions file ::::::::::::
 * :: Initialize and setup the theme :::::::::
 *
 * Hooks, Actions and Filters are used throughout this theme. You should be able to do most of your
 * customizations without touching the main code. For more information on hooks, actions, and filters
 * @see http://codex.wordpress.org/Plugin_API
 *
 * @package    Unos Magazine Black
 */


/* === Theme Setup === */


/**
 * Theme Setup
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_theme_setup(){

	// Load theme's Hootkit functions if plugin is active
	if ( class_exists( 'HootKit' ) && file_exists( hoot_data()->child_dir . 'hootkit/functions.php' ) )
		include_once( hoot_data()->child_dir . 'hootkit/functions.php' );

}
add_action( 'after_setup_theme', 'unosmbl_theme_setup', 10 );

/**
 * Set dynamic css handle to child stylesheet
 * if HK active : earlier set to hootkit@parent @priority 5; set to hootkit@child @priority 9
 * This is preferred in case of pre-built child themes where we want child stylesheet to come before
 * dynamic css (not after like in the case of user blank child themes purely used for customizations)
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'unosmbl_dynamic_css_child_handle' ) ) :
function unosmbl_dynamic_css_child_handle( $handle ) {
	return 'hoot-child-style';
}
endif;
add_filter( 'hoot_style_builder_inline_style_handle', 'unosmbl_dynamic_css_child_handle', 7 );

/**
 * Add theme name in body class
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'unosmbl_default_body_class' ) ) :
function unosmbl_default_body_class( $class ) {
	return 'unos-black';
}
endif;
add_filter( 'unos_default_body_class', 'unosmbl_default_body_class', 7 );

/**
 * Update tags in Template's About Page
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosmbl_abouttags( $tags ) {
	return array(
		'slug' => 'unos-magazine-black',
		'name' => __( 'Unos Magazine Black', 'unos-magazine-black' ),
		'vers' => hoot_data( 'childtheme_version' ),
		'shot' => ( file_exists( hoot_data()->child_dir . 'screenshot.jpg' ) ) ? hoot_data()->child_uri . 'screenshot.jpg' : (
					( file_exists( hoot_data()->child_dir . 'screenshot.png' ) ) ? hoot_data()->child_uri . 'screenshot.png' : ''
					),
		);
}
add_filter( 'unos_abouttags', 'unosmbl_abouttags', 5 );

/**
 * Alter Customizer Section Pro args
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_customize_section_pro( $args ) {
	if ( isset( $args['title'] ) )
		$args['title'] = esc_html__( 'Unos Magazine Black Premium', 'unos-magazine-black' );
	if ( isset( $args['pro_url'] ) )
		$args['pro_url'] = esc_url( admin_url('themes.php?page=unos-magazine-black-welcome') );
	return $args;
}
add_filter( 'hoot_theme_customize_section_pro', 'unosmbl_customize_section_pro' );

/**
 * Modify custom-header
 * Priority@5 to come before 10 used by unos for adding support
 *    @ref wp-includes/theme.php #2440
 *    // Merge in data from previous add_theme_support() calls.
 *    // The first value registered wins. (A child theme is set up first.)
 * For remove_theme_support, use priority@15
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_custom_header() {
	add_theme_support( 'custom-header', array(
		'width' => 1440,
		'height' => 500,
		'flex-height' => true,
		'flex-width' => true,
		'default-image' => '',
		'header-text' => false
	) );
}
add_filter( 'after_setup_theme', 'unosmbl_custom_header', 5 );


/* === Attr === */


/**
 * Topbar meta attributes.
 * Priority@10: 7-> base lite ; 9-> base prim
 *
 * @since 1.0
 * @param array $attr
 * @param string $context
 * @return array
 */
function unosmbl_attr_topbar( $attr, $context ) {
	if ( !empty( $attr['classes'] ) )
		$attr['classes'] = str_replace( 'social-icons-invert', '', $attr['classes'] );
	return $attr;
}
add_filter( 'hoot_attr_topbar', 'unosmbl_attr_topbar', 10, 2 );

/**
 * Loop meta attributes.
 * Priority@10: 7-> base lite ; 9-> base prim
 *
 * @since 1.0
 * @param array $attr
 * @param string $context
 * @return array
 */
function unosmbl_attr_premium_loop_meta_wrap( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	/* Overwrite all and apply background class for both */
	$attr['class'] = str_replace( array( 'loop-meta-wrap pageheader-bg-default', 'loop-meta-wrap pageheader-bg-stretch', 'loop-meta-wrap pageheader-bg-incontent', 'loop-meta-wrap pageheader-bg-both', 'loop-meta-wrap pageheader-bg-none', ), '', $attr['class'] );
	$attr['class'] .= ' loop-meta-wrap pageheader-bg-both';

	return $attr;
}
add_filter( 'hoot_attr_loop-meta-wrap', 'unosmbl_attr_premium_loop_meta_wrap', 10, 2 );


/* === Dynamic CSS === */


/* Update user based style values for premium dynamic css */
/**
 * Create user based style values for premium dynamic css
 * Priority@6: apply_filters -> base lite ; 5-> base prim
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosmbl_user_style( $styles ){

	/* Override Base styles */

	/* Add child styles */
	$styles['body_fontface']              = hoot_get_mod( 'body_fontface' );
	$styles['subheadings_fontface']       = hoot_get_mod( 'subheadings_fontface' );
	$styles['subheadings_fontface_style'] = hoot_get_mod( 'subheadings_fontface_style' );

	return $styles;
}
add_filter( 'unos_user_style', 'unosmbl_user_style', 6 );

/**
 * Custom CSS built from user theme options
 * For proper sanitization, always use functions from library/sanitization.php
 * Priority@6: 5-> base lite ; 7-> base prim prepare (rules removed) ;
 *             9-> base prim ; 10-> base hootkit lite/prim
 *
 * @since 1.0
 * @access public
 */
function unosmbl_dynamic_cssrules() {

	global $hoot_style_builder;

	// Get user based style values
	$styles = unos_user_style(); // echo '<!-- '; print_r($styles); echo ' -->';
	extract( $styles );

	$bodyfontface = '';
	if ( 'fontla' == $body_fontface )
		$bodyfontface = '"Lato", sans-serif';
	elseif ( 'fontos' == $body_fontface )
		$bodyfontface = '"Open Sans", sans-serif';
	elseif ( 'fontcf' == $body_fontface )
		$bodyfontface = '"Comfortaa", sans-serif';
	elseif ( 'fontow' == $body_fontface )
		$bodyfontface = '"Oswald", sans-serif';
	elseif ( 'fontim' == $body_fontface )
		$bodyfontface = 'Impact, Arial, sans-serif';
	elseif ( 'fontno' == $body_fontface )
		$bodyfontface = '"Noto Serif", serif';
	elseif ( 'fontsl' == $body_fontface )
		$bodyfontface = '"Slabo 27px", serif';
	elseif ( 'fontgr' == $body_fontface )
		$bodyfontface = 'Georgia, serif';
	hoot_add_css_rule( array(
						'selector'  => 'body' . ',' . '.enforce-body-font' . ',' . '.site-title-body-font',
						'property'  => 'font-family',
						'value'     => $bodyfontface,
					) ); // Removed in prim

	$headingproperty = array();
	if ( 'fontla' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontos' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Open Sans", sans-serif' );
	elseif ( 'fontcf' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontim' == $headings_fontface )
		$headingproperty['font-family'] = array( 'Impact, Arial, sans-serif' );
	elseif ( 'fontno' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $headings_fontface )
		$headingproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $headings_fontface_style )
		$headingproperty['text-transform'] = array( 'uppercase' );
	else
		$headingproperty['text-transform'] = array( 'none' );
	if ( !empty( $headingproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => 'h1, h2, h3, h4, h5, h6, .title, .titlefont',
						'property'  => $headingproperty,
					) ); // Removed in prim
		hoot_add_css_rule( array(
						'selector'  => '.sidebar .widget-title, .sub-footer .widget-title, .footer .widget-title',
						'property'  => $headingproperty,
					) ); // Removed in prim
		hoot_add_css_rule( array(
						'selector'  => '.post-gridunit-title, .hk-gridunit-title', // 'grid-widget' Hootkit <= 1.1.3 support // @todo remove in future version
						'property'  => $headingproperty,
					) ); // Changed in prim
	}

	$subheadingproperty = array();
	if ( 'fontla' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontos' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Open Sans", sans-serif' );
	elseif ( 'fontcf' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontim' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( 'Impact, Arial, sans-serif' );
	elseif ( 'fontno' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $subheadings_fontface_style || 'uppercasei' == $subheadings_fontface_style )
		$subheadingproperty['text-transform'] = array( 'uppercase' );
	else
		$subheadingproperty['text-transform'] = array( 'none' );
	if ( 'standardi' == $subheadings_fontface_style || 'uppercasei' == $subheadings_fontface_style )
		$subheadingproperty['font-style'] = array( 'italic' );
	else
		$subheadingproperty['font-style'] = array( 'normal' );
	if ( !empty( $subheadingproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline', // 'grid-widget' Hootkit <= 1.1.3 support // @todo remove in future version // 'list-widget' Hootkit <= 1.1.3 support // @todo remove in future version
						'property'  => $subheadingproperty,
					) ); // Removed in prim
	}

	hoot_add_css_rule( array(
						'selector'  => '#topbar',
						'property'  => array(
							'background' => array( 'rgba(0,0,0,0.04)' ), // #f7f7f7 // $accent_color
							'color'      => array( 'inherit' ), // $accent_font
							),
					) );

	hoot_add_css_rule( array(
						'selector'  => '#topbar.js-search .searchform.expand .searchtext',
						'property'  => 'background',
						'value'     => '#f7f7f7', /* $content_bg_color, */ // $accent_color
					) );
	hoot_add_css_rule( array(
						'selector'  => '#topbar.js-search .searchform.expand .searchtext' . ',' . '#topbar .js-search-placeholder',
						'property'  => 'color',
						'value'     => 'inherit', // $accent_font
					) );

	$logoproperty = array();
	if ( 'fontla' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontos' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Open Sans", sans-serif' );
	elseif ( 'fontcf' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontim' == $logo_fontface )
		$logoproperty['font-family'] = array( 'Impact, Arial, sans-serif' );
	elseif ( 'fontno' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $logo_fontface )
		$logoproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $logo_fontface_style )
		$logoproperty['text-transform'] = array( 'uppercase' );
	else
		$logoproperty['text-transform'] = array( 'none' );
	if ( !empty( $logoproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => '#site-title',
						'property'  => $logoproperty,
					) ); // Removed in prim
	}

	$sitetitleheadingfont = '';
	if ( 'fontla' == $headings_fontface )
		$sitetitleheadingfont = '"Lato", sans-serif';
	elseif ( 'fontos' == $headings_fontface )
		$sitetitleheadingfont = '"Open Sans", sans-serif';
	elseif ( 'fontcf' == $headings_fontface )
		$sitetitleheadingfont = '"Comfortaa", sans-serif';
	elseif ( 'fontow' == $headings_fontface )
		$sitetitleheadingfont = '"Oswald", sans-serif';
	elseif ( 'fontim' == $headings_fontface )
		$sitetitleheadingfont = 'Impact, Arial, sans-serif';
	elseif ( 'fontno' == $headings_fontface )
		$sitetitleheadingfont = '"Noto Serif", serif';
	elseif ( 'fontsl' == $headings_fontface )
		$sitetitleheadingfont = '"Slabo 27px", serif';
	elseif ( 'fontgr' == $headings_fontface )
		$sitetitleheadingfont = 'Georgia, serif';
	hoot_add_css_rule( array(
						'selector'  => '.site-title-heading-font',
						'property'  => 'font-family',
						'value'     => $sitetitleheadingfont,
					) ); // Overridden in prim
	hoot_add_css_rule( array(
						'selector'  => '.entry-grid .more-link',
						'property'  => 'font-family',
						'value'     => $sitetitleheadingfont,
					) ); // Overridden in prim

	$hoot_style_builder->remove( array(
		'.menu-items li.current-menu-item, .menu-items li.current-menu-ancestor, .menu-items li:hover',
		'.menu-items li.current-menu-item > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
	) );

	hoot_add_css_rule( array(
						'selector'  => '.menu-items ul li.current-menu-item, .menu-items ul li.current-menu-ancestor, .menu-items ul li:hover',
						'property'  => 'background',
						'value'     => $accent_color,
						'idtag'     => 'accent_color'
					) );
	hoot_add_css_rule( array(
						'selector'  => '.menu-items ul li.current-menu-item > a, .menu-items ul li.current-menu-ancestor > a, .menu-items ul li:hover > a',
						'property'  => 'color',
						'value'     => $accent_font,
						'idtag'     => 'accent_font'
					) );

	hoot_add_css_rule( array(
						'selector'  => '.menu-items > li',
						'property'  => array(
							'border-color' => array( $accent_color, 'accent_color' ),
							'color'        => array( $accent_color, 'accent_color' ),
							),
					) );
	$topmenuitems = unosmbl_nav_menu_toplevel_items();
	$colorset = apply_filters( 'unosmbl_menu_colorset', array(
		array( '#e9cb42', '#ffffff' ),
		array( '#7dc20f', '#ffffff' ),
		array( '#25b7d1', '#ffffff' ),
		array( '#ffb22d', '#ffffff' ),
		array( '#ff2d2d', '#ffffff' ),
		) );
	$colorcount = 0;
	foreach ( $topmenuitems as $topitem ) { if ( !empty( $topitem->ID ) ) {
		$colorbg = ( !empty( $topitem->hootmenu['hoot_tagbg'] ) ) ? $topitem->hootmenu['hoot_tagbg'] : '';
		$colorfont = ( !empty( $topitem->hootmenu['hoot_tagbg'] ) ) ? $topitem->hootmenu['hoot_tagfont'] : '';
		if ( !$colorbg && !$colorfont ) {
			$colorbg = $colorset[ $colorcount ][0];
			$colorfont = $colorset[ $colorcount ][1];
			$colorcount++; if ( $colorcount == count( $colorset ) ) $colorcount = 0; 
		}
		if ( $colorbg ) {
			hoot_add_css_rule( array(
						'selector'  => "#menu-item-{$topitem->ID}" . ',' . "#menu-item-{$topitem->ID} .menu-tag",
						'property'  => array(
							'border-color' => $colorbg,
							'color'        => $colorbg,
							),
					) );
			hoot_add_css_rule( array(
						'selector'  => "#menu-item-{$topitem->ID} ul li.current-menu-item, #menu-item-{$topitem->ID} ul li.current-menu-ancestor, #menu-item-{$topitem->ID} ul li:hover" . ',' . "#menu-item-{$topitem->ID} .menu-tag",
						'property'  => 'background',
						'value'     => $colorbg,
					) );
		}
		if ( $colorfont ) {
			hoot_add_css_rule( array(
						'selector'  => "#menu-item-{$topitem->ID} ul li.current-menu-item > a, #menu-item-{$topitem->ID} ul li.current-menu-ancestor > a, #menu-item-{$topitem->ID} ul li:hover > a" . ',' . "#menu-item-{$topitem->ID} .menu-tag",
						'property'  => 'color',
						'value'     => $colorfont,
					) );
		}
	} }

	$categories = get_categories( array( 'orderby' => 'name', 'order' => 'ASC' ) );
	$colorset = apply_filters( 'unosmbl_catblocks_colorset', array(
		array( '#ffe42d', '#ffffff' ),
		array( '#7dc20f', '#ffffff' ),
		array( '#25b7d1', '#ffffff' ),
		array( '#ffb22d', '#ffffff' ),
		array( '#ff503c', '#ffffff' ),
		) );
	$colorcount = 0;
	foreach ( $categories as $category ) { if ( !empty( $category->term_id ) ) {
		$property = array();
		$colorbg = get_term_meta( $category->term_id, 'hoot_term_bg', true );
		$colorfont = get_term_meta( $category->term_id, 'hoot_term_font', true );
		if ( !$colorbg && !$colorfont ) {
			$colorbg = $colorset[ $colorcount ][0];
			$colorfont = $colorset[ $colorcount ][1];
			$colorcount++; if ( $colorcount == count( $colorset ) ) $colorcount = 0; 
		}
		if ( $colorbg ) $property['background'] = $colorbg;
		if ( $colorfont ) $property['color'] = $colorfont;
		if ( !empty( $property ) )
			hoot_add_css_rule( array(
						'selector'  => ".catblock-{$category->term_id}",
						'property'  => $property,
					) );
	} }

	$halfwidgetmargin = false;
	if ( intval( $widgetmargin ) )
		$halfwidgetmargin = ( intval( $widgetmargin ) / 2 > 25 ) ? ( intval( $widgetmargin ) / 2 ) . 'px' : '25px';
	if ( $halfwidgetmargin )
		hoot_add_css_rule( array(
						'selector'  => '.main > .main-content-grid:first-child' . ',' . '.content-frontpage > .frontpage-area-boxed:first-child',
						'property'  => 'margin-top',
						'value'     => $halfwidgetmargin,
					) );

	hoot_add_css_rule( array(
						'selector'  => '.widget_newsletterwidget, .widget_newsletterwidgetminimal',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

}
add_action( 'hoot_dynamic_cssrules', 'unosmbl_dynamic_cssrules', 6 );


/* === Customizer Options === */


/**
 * Update theme defaults
 * Prim @priority 5
 * Prim child @priority 9
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'unosmbl_default_style' ) ) :
function unosmbl_default_style( $defaults ){
	$defaults = array_merge( $defaults, array(
		'accent_color'         => '#ffe42d',
		'accent_font'          => '#141414',
		'widgetmargin'         => 45,
		'logo_fontface'        => 'fontim',
		'headings_fontface'    => 'fontla',
	) );
	return $defaults;
}
endif;
add_filter( 'unos_default_style', 'unosmbl_default_style', 7 );

/**
 * Add Options (settings, sections and panels) to Hoot_Customize class options object
 *
 * Parent Lite/Prim add options using 'init' hook both at priority 0. Currently there is no way
 * to hook in between them. Hence we hook in later at 5 to be able to remove options if needed.
 * The only drawback is that options involving widget areas cannot be modified/created/removed as
 * those have already been used during widgets_init hooked into init at priority 1. For adding options
 * involving widget areas, we can alterntely hook into 'after_setup_theme' before lite/prim options
 * are built. Modifying/removing such options from lite/prim still needs testing.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unosmbl_add_customizer_options' ) ) :
function unosmbl_add_customizer_options() {

	$hoot_customize = Hoot_Customize::get_instance();

	// Modify Options
	$hoot_customize->remove_settings( array( 'logo_tagline_size', 'logo_tagline_style' ) );
	$hoot_customize->remove_settings( 'pageheader_background_location' );

	// Define Options
	$options = array(
		'settings' => array(),
		'sections' => array(),
		'panels' => array(),
	);

	$options['settings']['themestyle'] = array(
		'label'       => esc_html__( 'Theme Style', 'unos-magazine-black' ),
		'section'     => 'colors',
		'type'        => 'radio',
		'priority'    => 180,
		'choices'     => array(
			'light' => esc_html__( 'Light', 'unos-magazine-black'),
			'dark'  => esc_html__( 'Dark', 'unos-magazine-black'),
		),
		'default'     => 'dark',
		'transport'   => 'postMessage',
	);

	$options['settings']['subheadings_fontface'] = array(
		'label'       => esc_html__( 'Sub Headings Font (Free Version)', 'unos-magazine-black' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array( ),
		'default'     => 'fontgr',
	); // Removed in premium

	$options['settings']['subheadings_fontface_style'] = array(
		'label'       => esc_html__( 'Sub Heading Font Style', 'unos-magazine-black' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'standard'   => esc_html__( 'Standard', 'unos-magazine-black'),
			'standardi'  => esc_html__( 'Standard Italics', 'unos-magazine-black'),
			'uppercase'  => esc_html__( 'Uppercase', 'unos-magazine-black'),
			'uppercasei' => esc_html__( 'Uppercase Italics', 'unos-magazine-black'),
		),
		'default'     => 'standardi',
		'transport' => 'postMessage',
	); // Removed in premium

	$options['settings']['body_fontface'] = array(
		'label'       => esc_html__( 'Body Font (Free Version)', 'unos-magazine-black' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array( ),
		'default'     => 'fontla',
	); // Removed in premium

	// Add Options
	$hoot_customize->add_options( apply_filters( 'unosmbl_customizer_options', array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) ) );

}
endif;
add_action( 'init', 'unosmbl_add_customizer_options', 5 );

/**
 * Modify Lite customizer options
 * Prim hooks in later at priority 9
 *
 * @since 1.0
 * @access public
 */
function unosmbl_modify_customizer_options( $options ){

	if ( isset( $options['settings']['widgetmargin'] ) )
		$options['settings']['widgetmargin']['input_attrs']['placeholder'] = esc_html__( 'default: 35', 'unos-magazine-black' );
	if ( isset( $options['settings']['menu_location'] ) )
		$options['settings']['menu_location']['default'] = 'bottom';
	if ( isset( $options['settings']['logo'] ) )
		$options['settings']['logo']['default'] = 'custom';
	if ( isset( $options['settings']['logo_side'] ) )
		$options['settings']['logo_side']['default'] = 'widget-area';
	if ( isset( $options['settings']['fullwidth_menu_align'] ) )
		$options['settings']['fullwidth_menu_align']['default'] = 'left';
	if ( isset( $options['settings']['logo_custom'] ) )
		$options['settings']['logo_custom']['default'] = array(
			'line1'  => array( 'text' => wp_kses_post( __( '<b>HOOT UNOS</b>', 'unos-magazine-black' ) ), 'size' => '18px', 'font' => 'standard' ),
			'line2'  => array( 'text' => wp_kses_post( __( '<em>MAGAZINE</em><mark>BLACK</mark>', 'unos-magazine-black' ) ), 'size' => '60px' ),
			// 'line3'  => array( 'sortitem_hide' => 1, 'font' => 'standard' ),
			// 'line4'  => array( 'sortitem_hide' => 1, ),
		);
	if ( !empty( $options['settings']['logo_custom']['description'] ) )
		$options['settings']['logo_custom']['description'] = sprintf( esc_html__( 'Use &lt;b&gt; &lt;em&gt; and &lt;mark&gt; tags in "Line Text" fields below to emphasize different words. Example:%1$s%2$s&lt;b&gt;Unos&lt;/b&gt; &lt;em&gt;Magazine&lt;/em&gt; &lt;mark&gt;Black&lt;/mark&gt;%3$s', 'unos-magazine-black' ), '<br />', '<code>', '</code>' );

	if ( isset( $options['settings']['logo_custo']['options'] ) ) {
		foreach ( $options['settings']['logo_custom']['options'] as $linekey => $linevalue ) {
			$options['settings']['logo_custom']['options'][$linekey] = array_merge( $options['settings']['logo_custom']['options'][$linekey], array(
				'accentbg' => array(
					'label'       => esc_html__( 'Accent Background', 'unos-magazine-black' ),
					'type'        => 'checkbox',
				),
			) );
		}
	}
	if ( isset( $options['settings']['logo_fontface_style'] ) )
		$options['settings']['logo_fontface_style']['default'] = 'standard';
	return $options;
}
add_filter( 'unos_customizer_options', 'unosmbl_modify_customizer_options', 7 );

/**
 * Modify customizer options before being added to Class options variable
 *
 * @since 1.0
 * @access public
 */
function unosmbl_hoot_customize_add_settings( $settings ){
	$fontoptions = array( 'logo_fontface', 'headings_fontface', 'subheadings_fontface', 'body_fontface' );
	foreach ( $fontoptions as $key ) if ( !empty( $settings[ $key ] ) )
		$settings[ $key ]['choices'] = array(
			'fontla' => esc_html__( 'Standard Font 1 (Lato)', 'unos-magazine-black'),
			'fontos' => esc_html__( 'Standard Font 2 (Open Sans)', 'unos-magazine-black'),
			'fontcf' => esc_html__( 'Alternate Font (Comfortaa)', 'unos-magazine-black'),
			'fontow' => esc_html__( 'Display Font 1 (Oswald)', 'unos-magazine-black'),
			'fontim' => esc_html__( 'Display Font 2 (Impact)', 'unos-magazine-black'),
			'fontno' => esc_html__( 'Heading Font 1 (Noto Serif)', 'unos-magazine-black'),
			'fontsl' => esc_html__( 'Heading Font 2 (Slabo)', 'unos-magazine-black'),
			'fontgr' => esc_html__( 'Heading Font 3 (Georgia)', 'unos-magazine-black'),
		);
	return $settings;
}
add_filter( 'hoot_customize_add_settings', 'unosmbl_hoot_customize_add_settings' );

/**
 * Modify Customizer Link Section
 *
 * @since 1.0
 * @access public
 */
function unosmbl_customizer_option_linksection( $lcontent ){
	if ( is_array( $lcontent ) ) {
		if ( !empty( $lcontent['demo'] ) )
			$lcontent['demo'] = str_replace( 'demo.wphoot.com/unos', 'demo.wphoot.com/unos-magazine-black', $lcontent['demo'] );
		if ( !empty( $lcontent['install'] ) )
			$lcontent['install'] = str_replace( 'wphoot.com/support/unos', 'wphoot.com/support/unos-magazine-black', $lcontent['install'] );
		if ( !empty( $lcontent['rateus'] ) )
			$lcontent['rateus'] = str_replace( 'wordpress.org/support/theme/unos', 'wordpress.org/support/theme/unos-magazine-black', $lcontent['rateus'] );
	}
	return $lcontent;
}
add_filter( 'unos_customizer_option_linksection', 'unosmbl_customizer_option_linksection' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0
 * @return void
 */
function unosmbl_customize_preview_js() {
	if ( file_exists( hoot_data()->child_dir . 'admin/customize-preview.js' ) )
		wp_enqueue_script( 'unosmbl-customize-preview', hoot_data()->child_uri . 'admin/customize-preview.js', array( 'hoot-customize-preview', 'customize-preview' ), hoot_data()->childtheme_version, true );
}
add_action( 'customize_preview_init', 'unosmbl_customize_preview_js', 12 );

/**
 * Add style tag to support dynamic css via postMessage script in customizer preview
 *
 * @since 1.0
 * @access public
 */

function unosmbl_customize_dynamic_selectors( $settings ) {
	if ( !is_array( $settings ) ) return $settings;
	$hootpload = ( function_exists( 'hoot_lib_premium_core' ) ) ? 1 : '';

	$modify = array(
		'box_background_color' => array(
			'color'			=> array( 'remove' => array(), 'add' => array(), ),
			'background'	=> array( 'remove' => array(), 'add' => array(), ),
		),
		'accent_color' => array(
			'color' => array(
				'remove' => array(
				),
				'add' => array(
					'.menu-items ul li.current-menu-item > a, .menu-items ul li.current-menu-ancestor > a, .menu-items ul li:hover > a',
					'.content-block-subtitle',
				),
			),
			'background' => array(
				'add' => array(
					'.widget_newsletterwidget, .widget_newsletterwidgetminimal',
				),
				'remove' => array(
					'.menu-items li.current-menu-item, .menu-items li.current-menu-ancestor, .menu-items li:hover',
					'.social-icons-icon',
				),
			),
			'border-color' => array(
				'add' => array(
					'.menu-items > li.current-menu-item:after, .menu-items > li.current-menu-ancestor:after, .menu-items > li:hover:after' . ',' . '.menu-hoottag',
				),
			),
		),
		'accent_font' => array(
			'color' => array(
				'add' => array(
					'.widget_newsletterwidget, .widget_newsletterwidgetminimal',
				),
				'remove' => array(
					'.menu-items li.current-menu-item > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
					'#topbar .social-icons-icon, #page-wrapper .social-icons-icon',
				),
			),
			'background' => array(
				'remove' => array(
				),
				'add' => array(
					'.menu-items ul li.current-menu-item, .menu-items ul li.current-menu-ancestor, .menu-items ul li:hover',
				),
			),
		),
	);

	if ( !$hootpload ) {
		array_push( $modify['accent_color']['background']['remove'], '#topbar', '#topbar.js-search .searchform.expand .searchtext' );
		array_push( $modify['accent_font']['color']['remove'], '#topbar', '#topbar.js-search .searchform.expand .searchtext', '#topbar .js-search-placeholder' );
		/* array_push( $modify['box_background_color']['background']['add'], '#topbar.js-search .searchform.expand .searchtext' ); */
		$modify['headings_fontface_style']['text-transform']['add'] = array( '.sidebar .widget-title, .sub-footer .widget-title, .footer .widget-title', '.post-gridunit-title, .hk-gridunit-title' ); // 'grid-widget' Hootkit <= 1.1.3 support // @todo remove in future version
	}

	foreach ( $modify as $id => $props ) {
		foreach ( $props as $prop => $ops ) {
			foreach ( $ops as $op => $values ) {
				if ( $op == 'remove' ) {
					foreach ( $values as $val ) {
						$akey = array_search( $val, $settings[$id][$prop] );
						if ( $akey !== false ) unset( $settings[$id][$prop][$akey] );
					}
				} elseif ( $op == 'add' ) {
					foreach ( $values as $val ) {
						$settings[$id][$prop][] = $val;
					}
				}
			}
		}
	}

	if ( !$hootpload ) {
		$settings['subheadings_fontface_style'] = array(
			'font-style'=> array( '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline' ), // 'grid-widget' Hootkit <= 1.1.3 support // @todo remove in future version // 'list-widget' Hootkit <= 1.1.3 support // @todo remove in future version
		);
		$settings['subheadings_fontface_style_trans'] = array(
			'text-transform'=> array( '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline' ), // 'grid-widget' Hootkit <= 1.1.3 support // @todo remove in future version // 'list-widget' Hootkit <= 1.1.3 support // @todo remove in future version
		);
	}

	return $settings;
}
add_filter( 'hoot_customize_dynamic_selectors', 'unosmbl_customize_dynamic_selectors', 5 );


/* === Fonts === */


/**
 * Build URL for loading Google Fonts
 * Priority@5 : Prim loads at priority 10
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_google_fonts_preparearray( $fonts ) {
	$fonts = array();

		$modsfont = array( hoot_get_mod( 'body_fontface' ), hoot_get_mod( 'logo_fontface' ), hoot_get_mod( 'headings_fontface' ), hoot_get_mod( 'subheadings_fontface' ) );

		if ( in_array( 'fontla', $modsfont ) ) {
			$fonts[ 'Lato' ] = array(
				'normal' => array( '400','500','700' ),
				'italic' => array( '400','500','700' ),
			);
		}
		if ( in_array( 'fontos', $modsfont ) ) {
			$fonts[ 'Open Sans' ] = array(
				'normal' => array( '300','400','500','600','700','800' ),
				'italic' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontcf', $modsfont ) ) {
			$fonts[ 'Comfortaa' ] = array(
				'normal' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontow', $modsfont ) ) {
			$fonts[ 'Oswald' ] = array(
				'normal' => array( '400', 700 ),
			);
		}
		if ( in_array( 'fontno', $modsfont ) ) {
			$fonts[ 'Noto Serif' ] = array(
				'normal' => array( '400','700' ),
				'italic' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontsl', $modsfont ) ) {
			$fonts[ 'Slabo 27px' ] = array(
				'normal' => array( '400' ),
			);
		}

	return $fonts;
}
add_filter( 'unos_google_fonts_preparearray', 'unosmbl_google_fonts_preparearray', 5, 2 );

/**
 * Modify the font (websafe) list
 * Font list should always have the form:
 * {css style} => {font name}
 * 
 * Even though this list isn't currently used in customizer options (no typography options)
 * this is still needed so that sanitization functions recognize the font.
 * Priority@15 to overwrite Lite @priority 10
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosmbl_fonts_list( $fonts ) {
	if ( !function_exists( 'hoot_lib_premium_core' ) ) {
		$fonts['Impact, Arial, sans-serif'] = 'Impact';
		if ( isset( $fonts['"Lora", serif'] ) )
			unset( $fonts['"Lora", serif'] );
		$fonts['"Lato", sans-serif'] = 'Lato';
		$fonts['"Noto Serif", serif'] = 'Noto Serif';
	} else {
		// let those fonts occur in their natural order as stated in hoot_googlefonts_list()
		return $fonts;
	}
	return $fonts;
}
add_filter( 'hoot_fonts_list', 'unosmbl_fonts_list', 15 );


/* === Menu === */


/**
 * Add default values for Nav Menu
 *
 * @since 1.0
 */
function unosmbl_nav_menu_defaults( $defaults ){
	return array(
		'tagbg' => '#ffe42d',
		'tagfont' => '#ffffff',
		'tagbg_label' => __( 'Tag &amp; Hover Background (leave empty for automatic color)', 'unos-magazine-black' ),
		'tagfont_label' => __( 'Tag &amp; Hover Font (leave empty for automatic color)', 'unos-magazine-black' ),
	);
}
add_filter( 'unos_nav_menu_defaults', 'unosmbl_nav_menu_defaults' );

/**
 * Disable menu tag hover change
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosmbl_menutag_inverthover( $enable ){
	return false;
}
add_filter( 'unos_menutag_inverthover', 'unosmbl_menutag_inverthover', 5 );

/**
 * Get the top level menu items array
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_nav_menu_toplevel_items( $theme_location = 'hoot-primary-menu' ) {
	static $location_items;
	if ( !isset( $location_items[$theme_location] ) && ($theme_locations = get_nav_menu_locations()) && isset( $theme_locations[$theme_location] ) ) {
		$menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
		if ( !empty( $menu_obj->term_id ) ) {
			$menu_items = wp_get_nav_menu_items($menu_obj->term_id);
			if ( $menu_items )
				foreach( $menu_items as $menu_item )
					if ( empty( $menu_item->menu_item_parent ) )
						$location_items[$theme_location][] = $menu_item;
		}
	}
	if ( !empty( $location_items[$theme_location] ) )
		return $location_items[$theme_location];
	else
		return array();
}


/* === Category Colors === */


/**
 * Display separate Category Blocks in meta info
 *
 * @since 1.0
 * @access public
 * @return string
 */
function unosmbl_display_meta_catblock( $blocks, $context, $display, $editlink ){
	$dofor = apply_filters( 'unosmbl_display_meta_catblocks', array(
				'loop-meta', // meta info shown in header along with title
				// 'post', 'page', // meta info shown in page/post footer after content
				'archive-big', 'archive-medium', 'archive-small', 'archive-block2', 'archive-block3', 'mixedunit-big', 'mixedunit-medium', 'mixedunit-small', 'mixedunit-block2', 'mixedunit-block3', 'archive-mosaic2', 'archive-mosaic3', 'archive-mosaic4',
				'customizer',
				'post-gridunit', // Hootkit <= 1.2.1 support // JNES@todo remove in future version
				'hk-gridunit', 'posts-listunit', 'post-listcarouselunit', 'content-post-block'
			), $blocks, $context, $display, $editlink );
	if ( !empty( $blocks ) && !empty( $blocks['cats'] ) && in_array( $context, $dofor ) ) {
		$categories = get_the_category();
		if ( !empty( $categories ) ) {
			$print = '';
			foreach ( $categories as $category ) {
				$print .= '<span class="catblock catblock-' . $category->term_id . '"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category">' . esc_html( $category->name ) . '</a></span>';
			}
		}
		if ( !empty( $print ) ) {
			unset( $blocks['cats'] );
			if ( apply_filters( 'unosmbl_display_meta_catblock_inline', true, $blocks, $context, $display, $editlink, $dofor ) && !empty( $print ) ) {
				array_unshift( $blocks, array( 'label' => '', 'content' => $print ) );
			} else {
				echo '<div class="entry-byline-catblock">' . $print . '</div>';
			}
		}
	}
	return $blocks;
}
add_filter( 'hoot_display_meta_info', 'unosmbl_display_meta_catblock', 5, 4 );

/*
 * Category Colors - Admin
 * @since 1.0
 */
function unosmbl_taxonomy_fields_init(){
	if ( is_admin() ) :
		$taxonomies = apply_filters( 'unosmbl_taxonomy_fields_taxonomies', array( 'category' ) );
		if ( !empty( $taxonomies ) ) {
			add_action( 'admin_enqueue_scripts', 'unosmbl_taxonomy_enqueue' );
			foreach ( $taxonomies as $taxonomy ) {
				add_filter( "manage_edit-{$taxonomy}_columns", 'unosmbl_taxonomy_columns_header' );
				add_filter( "manage_{$taxonomy}_custom_column", 'unosmbl_taxonomy_column', 10, 3 );
				add_action( "{$taxonomy}_add_form_fields", 'unosmbl_add_taxonomy_field' );
				add_filter( "{$taxonomy}_edit_form_fields", 'unosmbl_edit_taxonomy_field' );
			}
			// add_action( 'create_term', 'unosmbl_term_save' );
			add_action( 'created_term', 'unosmbl_term_update', 10, 3 );
			add_action( 'edit_term', 'unosmbl_term_update', 10, 3 );
		}
	endif;
}
add_action( 'after_setup_theme', 'unosmbl_taxonomy_fields_init' );

function unosmbl_taxonomy_enqueue( $hook ) {
	$screen = get_current_screen();
	$currenttax = str_replace( 'edit-', '', $screen->id );
	if ( in_array( $currenttax, apply_filters( 'unosmbl_taxonomy_fields_taxonomies', array( 'category' ) ) ) ) {
		wp_enqueue_style( 'wp-color-picker' );
		if ( file_exists( hoot_data()->child_dir . 'admin/taxedit.js' ) )
			wp_enqueue_script( 'unosmbl-taxedit', hoot_data()->child_uri . 'admin/taxedit.js', array( 'wp-color-picker' ), hoot_data()->hoot_version, true );
	}
}

function unosmbl_taxonomy_columns_header( $defaults ){
	$defaults['hoot_term_colors']  = __( 'Label Color', 'unos-magazine-black' );
	return $defaults;
}

function unosmbl_taxonomy_column( $columns, $column, $id ){
	if ( 'hoot_term_colors' === $column ) {
		$bg = get_term_meta( $id, 'hoot_term_bg', true );
		$font = get_term_meta( $id, 'hoot_term_font', true );
		$columns .= ( empty( $bg ) ) ? __( 'Auto', 'unos-magazine-black' ) . ' / ' : '<span style="display:inline-block;height:15px;width:15px;border:solid 1px #999;background:' . sanitize_hex_color( $bg ) . ';margin-right:5px;"></span>';
		$columns .= ( empty( $font ) ) ? __( 'Auto', 'unos-magazine-black' ) : '<span style="display:inline-block;height:15px;width:15px;border:solid 1px #999;background:' . sanitize_hex_color( $font ) . ';"></span>';
	}
	return $columns;
}

function unosmbl_add_taxonomy_field( $term ) {
	?><div class="form-field">
		<label for="hoot_term_bg"><?php esc_html_e( 'Label Background', 'unos-magazine-black' ); ?></label>
		<input type="input" id="hoot_term_bg" class="hoot-color" name="hoot_term_bg" value="" data-default-color="#ffe42d" />
	</div><div class="form-field">
		<label for="hoot_term_font"><?php esc_html_e( 'Label Font', 'unos-magazine-black' ); ?></label>
		<input type="input" id="hoot_term_font" class="hoot-color" name="hoot_term_font" value="" data-default-color="#ffffff" />
	</div><?php
}

function unosmbl_edit_taxonomy_field( $term ) {
	$bg = get_term_meta( $term->term_id, 'hoot_term_bg', true );
	$font = get_term_meta( $term->term_id, 'hoot_term_font', true );
	?><tr class="form-field">
		<th scope="row" valign="top">
			<label for="hoot_term_bg"><?php esc_html_e( 'Label Background', 'unos-magazine-black' ); ?></label>
		</th>
		<td>
			<input type="input" id="hoot_term_bg" class="hoot-color" name="hoot_term_bg" value="<?php echo sanitize_hex_color( $bg ); ?>" data-default-color="#ffe42d" />
			<p class="description" style="margin:0"><?php _e( 'Leave empty for automatic color selection', 'unos-magazine-black' ) ?></p>
		</td>
	</tr><tr class="form-field">
		<th scope="row" valign="top">
			<label for="hoot_term_font"><?php esc_html_e( 'Label Font', 'unos-magazine-black' ); ?></label>
		</th>
		<td>
			<input type="input" id="hoot_term_font" class="hoot-color" name="hoot_term_font" value="<?php echo sanitize_hex_color( $font ); ?>" data-default-color="#ffffff" />
			<p class="description" style="margin:0"><?php _e( 'Leave empty for automatic color selection', 'unos-magazine-black' ) ?></p>
		</td>
	</tr><?php
}

// @ref. https://developer.wordpress.org/reference/hooks/edit_term/
function unosmbl_term_update( $term_id, $tt_id = '', $taxonomy = '' ){
	if ( in_array( $taxonomy, apply_filters( 'unosmbl_taxonomy_fields_taxonomies', array( 'category' ) ) ) ) {
		if ( isset( $_POST['hoot_term_bg'] ) )
			update_term_meta( $term_id, 'hoot_term_bg', sanitize_hex_color( $_POST['hoot_term_bg'] ) );
		if ( isset( $_POST['hoot_term_font'] ) )
			update_term_meta( $term_id, 'hoot_term_font', sanitize_hex_color( $_POST['hoot_term_font'] ) );
	}
}


/* === Meta Info Option === */


/**
 * Edit Customizer Options
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosmbl_postmeta_customizer_options( $options ){
	unset( $options['settings']['post_meta_location'] );
	$options['settings']['post_meta']['label'] = esc_html__( 'Meta Information on Posts (After Title)', 'unos-magazine-black' );
	$options['settings']['post_meta']['default'] = 'cats';
	unset( $options['settings']['post_meta']['selective_refresh'] );
	$options['settings']['post_meta_bottom'] = array(
		'label'       => esc_html__( 'Meta Information on Posts (After Content)', 'unos-magazine-black' ),
		'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Post' page", 'unos-magazine-black' ),
		'section'     => 'singular',
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'unos-magazine-black' ),
			'date'     => esc_html__( 'Date', 'unos-magazine-black' ),
			'cats'     => esc_html__( 'Categories', 'unos-magazine-black' ),
			'tags'     => esc_html__( 'Tags', 'unos-magazine-black' ),
			'comments' => esc_html__( 'No. of comments', 'unos-magazine-black' )
		),
		'default'     => 'author, date, cats, tags, comments',
		'priority'    => '325',
	);
	return $options;
}
add_filter( 'unos_customizer_options', 'unosmbl_postmeta_customizer_options' );

/**
 * Display Loop Meta
 * Hook to a later priority for 'meta_hide_info' meta option to work
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosmbl_display_meta( $hide, $context ){
	if ( $hide ) return true;
	if ( is_attachment() ):
		return;
	elseif ( $context == 'top' ):
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ):
			if ( bbp_is_single_forum() ) {
				?><div <?php hoot_attr( 'loop-description' ); ?>><?php
					bbp_forum_content();
				?></div><!-- .loop-description --><?php
			};
		else:
			$metarray = ( is_page() ) ? hoot_get_mod('page_meta') : hoot_get_mod('post_meta');
			if ( hoot_meta_info( $metarray, 'loop-meta', true ) ) :
				?><div <?php hoot_attr( 'loop-description' ); ?>><?php
					hoot_display_meta_info( $metarray, 'loop-meta', false );
				?></div><!-- .loop-description --><?php
			endif;
		endif;
	elseif ( $context == 'bottom' ):
		if ( is_page() ) return true;
		$metarray = hoot_get_mod('post_meta_bottom');
		if ( hoot_meta_info( $metarray, 'post', true ) ) :
			?><footer class="entry-footer"><?php
				hoot_display_meta_info( $metarray, 'post', false );
			?></footer><!-- .entry-footer --><?php
		endif;
	else:
		return false;
	endif;
	return true;
}
add_filter( 'unos_hide_meta', 'unosmbl_display_meta', 99, 2 );




/* === Misc === */


/**
 * Disable accent typography for sidebar and footer widget titles
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosmbl_sidebarwidgettitle_accenttypo( $enable ){
	return false;
}
add_filter( 'unos_sidebarwidgettitle_accenttypo', 'unosmbl_sidebarwidgettitle_accenttypo', 5 );

/**
 * Dark Style Theme Setup
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosmbl_darkstyle_setup(){

	// hoot_get_mod is available only after 'init' action priority@5
	// hoot_data is set using 'after_setup_theme' hook priority@1
	if ( get_theme_mod( 'themestyle' ) !== 'light' )
		include_once( hoot_data()->child_dir . 'include/theme-dark.php' );

	// if ( is_customize_preview() ) {
	// 	global $currentthemestyle;
	// 	$currentthemestyle = get_theme_mod('themestyle');
	// }

}
add_action( 'after_setup_theme', 'unosmbl_darkstyle_setup', 10 );

/**
 * Actions in 'theme-dark.php' need to run before 'wp_loaded'
 * However the new option set in customizer is available only at wp_loaded
 * Hence we set_theme_mod (if it has changed from $currentthemestyle) and refresh the preview
 *   refreshing the preview could not be done by adding
 *   <script>( function( $ ) { wp.customize.preview.send( 'refresh' ); } )( jQuery );</script>
 *   in wp_head (customize-preview.js not loaded) or wp_footer@999 (wp.customize.preview is undefined)
 *   => use postMessage for themestyle and set_theme_mod using ajax
 */
function unosmbl_customize_preview_setthemestyle() {
	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'unosmbl-customize-preview' ) )
		wp_die( __( 'Invalid request.', 'unos-magazine-black' ), 403 );
	if ( current_user_can( 'edit_theme_options' ) ) {
		$newval = ( !empty( $_REQUEST['newval'] ) ) ? $_REQUEST['newval'] : 'dark';
		set_theme_mod( 'themestyle', $newval );
	}
	wp_send_json_success();
	wp_die();
}
function unosmbl_customize_preview_localize() {
	wp_localize_script( 'unosmbl-customize-preview', 'unosmblData', array(
		'ajaxurl' => wp_nonce_url( admin_url('admin-ajax.php'), 'unosmbl-customize-preview' )
	) );
}
add_action( 'wp_ajax_unos_set_themestyle', 'unosmbl_customize_preview_setthemestyle' );
add_action( 'customize_preview_init', 'unosmbl_customize_preview_localize', 13 ); // enqueued @12