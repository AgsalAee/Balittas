<?php
/**
 * HTML attribute filters.
 * Filter schema ('library/attr-schema.php') for generic container's attributes based on specific theme options
 * Attributes for non-generic structural elements (mostly theme specific) are also loaded in this file.
 *
 * @package    Unos
 * @subpackage Theme
 */

/* Modify Original Schema for Generic container's Option specific attributes */
add_filter( 'hoot_attr_header',   'unos_attr_header',   7, 2 );
add_filter( 'hoot_attr_menu',     'unos_attr_menu',     7, 2 );
add_filter( 'hoot_attr_content',  'unos_attr_content',  7    );
add_filter( 'hoot_attr_sidebar',  'unos_attr_sidebar',  7, 2 );
add_filter( 'hoot_attr_branding', 'unos_attr_branding', 7    );

/* New Theme Filters */
add_filter( 'hoot_attr_page-wrapper',      'unos_attr_page_wrapper',      7    );
add_filter( 'hoot_attr_topbar',            'unos_attr_topbar',            7    );
add_filter( 'hoot_attr_header-part',       'unos_attr_header_part',       7, 2 );
add_filter( 'hoot_attr_header-aside',      'unos_attr_header_aside',      7    );
add_filter( 'hoot_attr_below-header',      'unos_attr_below_header',      7    );
add_filter( 'hoot_attr_main',              'unos_attr_main',              7    );
add_filter( 'hoot_attr_frontpage-grid',    'unos_attr_frontpage_grid',    7    );
add_filter( 'hoot_attr_frontpage-content', 'unos_attr_frontpage_content', 7    );
add_filter( 'hoot_attr_frontpage-area',    'unos_attr_frontpage_area',    7, 2 );
add_filter( 'hoot_attr_loop-meta-wrap',    'unos_attr_loop_meta_wrap',    7, 2 );
add_filter( 'hoot_attr_loop-meta',         'unos_attr_loop_meta',         7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_loop-title',        'unos_attr_loop_title',        7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_loop-description',  'unos_attr_loop_description',  7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_content-wrap',      'unos_attr_content_wrap',      7, 2 );
add_filter( 'hoot_attr_sidebar-wrap',      'unos_attr_sidebar_wrap',      7, 2 );
add_filter( 'hoot_attr_sub-footer',        'unos_attr_sub_footer',        7    );
add_filter( 'hoot_attr_post-footer',       'unos_attr_post_footer',       7    );

/**
 * Modify <header> element attributes
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_header( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$location = hoot_get_mod( 'menu_location' );
	$side = hoot_get_mod( 'logo_side' );
	if ( $location == 'side' ) { $location = 'none'; $side = 'menu'; }

	$attr['class'] .= ' header-layout-primary-' . $side;
	$attr['class'] .= ' header-layout-secondary-' . $location;
	$attr['class'] .= ( hoot_get_mod( 'disable_table_menu' ) ) ? '' : ' tablemenu';
	return $attr;
}

/**
 * Nav menu attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_menu( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$mobile_menu = hoot_get_mod( 'mobile_menu' );
	$attr['class'] .= " mobilemenu-{$mobile_menu}";
	$mobile_submenu_click = hoot_get_mod( 'mobile_submenu_click' );
	$attr['class'] .= ( $mobile_submenu_click ) ? ' mobilesubmenu-click' : ' mobilesubmenu-open';
	return $attr;
}

/**
 * Modify Main content container of the page attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_content( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	$layout_class = unos_layout_class( 'content' );
	if ( !empty( $layout_class ) )
		$attr['class'] .= ' ' . $layout_class;

	return $attr;
}

/**
 * Modify Sidebar attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_sidebar( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	if ( !empty( $context ) && ( $context == 'primary' || $context == 'secondary' ) ) {
		$layout_class = unos_layout_class( "sidebar" );
		if ( !empty( $layout_class ) )
			$attr['class'] .= $layout_class;
	}

	return $attr;
}

/**
 * Branding attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_branding( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' table-cell-mid';
	return $attr;
}

/**
 * Page wrapper attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_page_wrapper( $attr ) {
	$attr['id'] = 'page-wrapper';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	// Set site layout class
	$site_layout = hoot_get_mod( 'site_layout' );
	$attr['class'] .= ( $site_layout == 'boxed' ) ? ' hgrid site-boxed' : ' site-stretch';
	$attr['class'] .= ' page-wrapper';

	// Set layout if not already set
	$layout = hoot_data( 'currentlayout' );
	if ( empty( $layout ) )
		unos_layout('');

	// Set sidebar layout class
	$currentlayout = hoot_data( 'currentlayout', 'layout' );
	if ( !empty( $currentlayout ) ) :
		$attr['class'] .= ' sitewrap-'. $currentlayout;
		switch( $currentlayout ) {
			case 'none' :
			case 'full' :
			case 'full-width' :
				$attr['class'] .= ' sidebars0';
				break;
			case 'narrow-right' :
			case 'wide-right' :
			case 'narrow-left' :
			case 'wide-left' :
				$attr['class'] .= ' sidebarsN sidebars1';
				break;
			case 'narrow-left-left' :
			case 'narrow-left-right' :
			case 'narrow-right-left' :
			case 'narrow-right-right' :
				$attr['class'] .= ' sidebarsN sidebars2';
				break;
		}
	endif;

	// Set plugin style classes
	$classes = apply_filters( 'unos_attr_page_wrapper_plugins', array( 'hoot-cf7-style', 'hoot-mapp-style', 'hoot-jetpack-style' ) );
	$attr['class'] .= ' ' . hoot_sanitize_html_classes( $classes );

	// Set sticky sidebar class
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' hoot-sticky-sidebar';

	return $attr;
}

/**
 * Topbar attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_topbar( $attr ) {
	$attr['id'] = 'topbar';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' topbar';
	return $attr;
}

/**
 * Modify header part attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_header_part( $attr, $context ) {
	$attr['id'] = 'header-' . $context;
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$location = hoot_get_mod( 'menu_location' );
	$side = hoot_get_mod( 'logo_side' );
	if ( $location == 'side' ) { $location = 'none'; $side = 'menu'; }

	$attr['class'] .= ' header-part header-' . $context;
	if ( $context == 'primary' ) {
		$attr['class'] .= ' header-primary-' . $side;
	} elseif ( $context == 'supplementary' ) {
		$attr['class'] .= ' header-supplementary-' . $location;
		$attr['class'] .= ' header-supplementary-' . hoot_get_mod( 'fullwidth_menu_align' );
		$attr['class'] .= ' header-supplementary-mobilemenu-' . hoot_get_mod( 'mobile_menu' );
		if ( function_exists( 'hoot_lib_premium_core' ) && hoot_get_mod( 'menu_background_type' ) == 'background' )
			$attr['class'] .= ' with-menubg';
	}

	return $attr;
}

/**
 * Header Aside attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_header_aside( $attr ) {
	$attr['id'] = 'header-aside';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' header-aside table-cell-mid';
	$side = hoot_get_mod( 'logo_side' );
	if ( $side == 'menu' && function_exists( 'hoot_lib_premium_core' ) && hoot_get_mod( 'menu_background_type' ) == 'background' )
		$attr['class'] .= ' with-menubg';
	return $attr;
}

/**
 * Below Header attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_below_header( $attr ) {
	$attr['id'] = 'below-header';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' below-header';
	return $attr;
}

/**
 * Main attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_main( $attr ) {
	$attr['id'] = 'main';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' main';
	return $attr;
}

/**
 * Main content container of the frontpage
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_frontpage_grid( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' hgrid-stretch frontpage-grid';

	return $attr;
}

/**
 * Main content container of the frontpage
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_frontpage_content( $attr ) {
	$attr['id'] = 'content-frontpage';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' content-frontpage';

	return $attr;
}

/**
 * Frontpage Area
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_frontpage_area( $attr, $context ) {

	$key = $context;
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$module_bg = hoot_get_mod( "frontpage_sectionbg_{$key}-type" );

	if ( $module_bg == 'image' ) {
		$module_bg_img = hoot_get_mod( "frontpage_sectionbg_{$key}-image" );
		if ( !empty( $module_bg_img ) ) {
			$module_bg_parallax = hoot_get_mod( "frontpage_sectionbg_{$key}-parallax" );
			$attr['class'] .= ( $module_bg_parallax ) ? ' bg-fixed' : ' bg-scroll';
			if ( $module_bg_parallax ) {
				$attr['data-parallax'] = 'scroll';
				// $attr['data-speed'] = '0.4'; // Default is 0.2 :: range [0-1]
				$attr['data-image-src'] = esc_url( $module_bg_img );
			} else {
				$attr['style'] = 'background-image:url(' . esc_attr( $module_bg_img ) . ');';
			}
		}
	} elseif ( $module_bg == 'color' ) {
		$module_bg_color = hoot_get_mod( "frontpage_sectionbg_{$key}-color" );
		if ( !empty( $module_bg_color ) ) {
			$attr['class'] .= ' area-bgcolor';
			$attr['style'] = 'background-color:' . sanitize_hex_color( $module_bg_color ) . ';';
		}
	}
	return $attr;
}

/**
 * Loop meta attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_loop_meta_wrap( $attr, $context ) {

	$attr['id'] = 'loop-meta';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-meta-wrap pageheader-bg-default';

	return $attr;
}

/**
 * Loop meta attributes.
 * hoot_attr_archive_header in v3.0.0 ; we use it for generic loop (archive / singular etc )
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_loop_meta( $attr, $context ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-meta';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-header';
	$attr['itemscope'] = 'itemscope';
	$attr['itemtype']  = 'https://schema.org/WebPageElement';
	return $attr;

}

/**
 * Loop title attributes.
 * hoot_attr_archive_title in v3.0.0 ; we use it for generic loop (archive / singular etc )
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_loop_title( $attr, $context ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-title entry-title';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-title';
	$attr['itemprop']  = 'headline';

	return $attr;
}

/**
 * Loop description attributes.
 * hoot_attr_archive_description in v3.0.0 ; we use it for generic loop (archive / singular etc
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_loop_description( $attr, $context ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-description';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-description';
	$attr['itemprop']  = 'text';

	return $attr;
}

/**
 * Content Wrap attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_content_wrap( $attr, $context ) {
	$attr['id'] = 'content-wrap';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' content-wrap';
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' theiaStickySidebar';
	return $attr;
}

/**
 * Sidebar Wrap attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function unos_attr_sidebar_wrap( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sidebar-wrap';
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' theiaStickySidebar';
	return $attr;
}

/**
 * Subfooter attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_sub_footer( $attr ) {
	$attr['id'] = 'sub-footer';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sub-footer';
	return $attr;
}

/**
 * Postfooter attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function unos_attr_post_footer( $attr ) {
	$attr['id'] = 'post-footer';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' post-footer';
	return $attr;
}