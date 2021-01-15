<?php
/**
 * Hoot Theme files
 *
 * @package    Unos
 * @subpackage Theme
 */

/* Load theme includes. Must keep priority 10 for theme constants to be available. */
add_action( 'after_setup_theme', 'unos_includes', 10 );

/**
 * Loads the theme files supported by themes and template-related functions/classes. Functionality 
 * in these files should not be expected within the theme setup function.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_includes() {

	/* Load the Theme Specific HTML attributes */
	require_once( hoot_data()->incdir . 'attr.php' );
	/* Load enqueue functions */
	require_once( hoot_data()->incdir . 'enqueue.php' );
	/* Load the dynamic css functions. */
	require_once( hoot_data()->incdir . 'css.php' );
	/* Load template tags. */
	require_once( hoot_data()->incdir . 'template-helpers.php' );
	/* Set the fonts. */
	require_once( hoot_data()->incdir . 'admin/fonts.php' );
	/* Set image sizes. */
	require_once( hoot_data()->incdir . 'admin/media.php' );
	/* Set menus */
	require_once( hoot_data()->incdir . 'admin/menus.php' );
	/* Set sidebars */
	require_once( hoot_data()->incdir . 'admin/sidebars.php' );
	/* Load TGMPA Class */
	if ( apply_filters( 'unos_load_tgmpa', file_exists( hoot_data()->incdir . 'admin/class-tgm-plugin-activation.php' ) ) )
		require_once( hoot_data()->incdir . 'admin/class-tgm-plugin-activation.php' );
	/* Load Customizer Options */
	if ( apply_filters( 'unos_customize_load_trt', file_exists( hoot_data()->incdir . 'admin/trt-customize-pro/class-customize.php' ) ) )
		require_once( hoot_data()->incdir . 'admin/trt-customize-pro/class-customize.php' );
	require_once( hoot_data()->incdir . 'admin/customizer-options.php' );
	/* Load the about page. */
	if ( apply_filters( 'unos_load_about', file_exists( hoot_data()->incdir . 'admin/about.php' ) ) )
		require_once( hoot_data()->incdir . 'admin/about.php' );
	/* Load the theme setup file */
	require_once( hoot_data()->incdir . 'theme-setup.php' );

	/* Load deprecated functions */
	require_once( hoot_data()->incdir . 'deprecated.php' );

}

/* Transition filter for version 2.7.9 : Doesnt resolve customizer but hopefully user will visit atleast one admin screen before customizer */
add_filter( 'hoot_get_mods', 'unos_transition_get_mods', 2 );

/**
 * Function for seamless transition for changed option/values in version 2.7.9
 * Updated 2.9.0 for frontpage sidebar option
 *
 * @since 2.7.9
 * @access public
 * @return void
 */
function unos_transition_get_mods( $mods ) {
	if ( isset( $mods['primary_menuarea'] ) || isset( $mods['secondary_menu_location'] ) || isset( $mods['secondary_menu_align'] ) ) {
		$primary_menuarea = isset( $mods['primary_menuarea'] ) ? $mods['primary_menuarea'] : 'menu'; // default value
		$secondary_menu_location = isset( $mods['secondary_menu_location'] ) ? $mods['secondary_menu_location'] : 'none'; // default value
		$secondary_menu_align = isset( $mods['secondary_menu_align'] ) ? $mods['secondary_menu_align'] : 'center'; // default value

		if ( $primary_menuarea == 'menu' ) { // secondary_menu_location set to top/bottom => :( cant have 2 menus
			$mods['menu_location'] = 'side';
			$mods['logo_side'] = 'none';
		} else { // secondary_menu_location set to top/bottom => :( need to allocate again to primary area in manage locations : resolved below
			$mods['menu_location'] = $secondary_menu_location;
			$mods['logo_side'] = $primary_menuarea;
		}
		$mods['fullwidth_menu_align'] = $mods['secondary_menu_align'];

		set_theme_mod( 'menu_location', $mods['menu_location'] );
		set_theme_mod( 'logo_side', $mods['logo_side'] );
		set_theme_mod( 'fullwidth_menu_align', $mods['fullwidth_menu_align'] );
		remove_theme_mod( 'secondary_menu_location' );
		remove_theme_mod( 'primary_menuarea' );
		remove_theme_mod( 'secondary_menu_align' );
	}

	if ( isset( $mods['nav_menu_locations'] ) && empty( $mods['nav_menu_locations']['hoot-primary-menu'] ) && isset( $mods['nav_menu_locations']['hoot-secondary-menu'] ) ) {
		$mods['nav_menu_locations']['hoot-primary-menu'] = $mods['nav_menu_locations']['hoot-secondary-menu'];
		set_theme_mod( 'nav_menu_locations', array( 'hoot-primary-menu' => intval( $mods['nav_menu_locations']['hoot-secondary-menu'] ), ) );
	}

	if ( isset( $mods['logo_fontface'] ) && in_array( $mods['logo_fontface'], array( 'standard', 'alternate', 'display', 'heading', 'heading2' ) ) ) {
		$new = $mods['logo_fontface'];
		switch ( $mods['logo_fontface'] ) {
			case 'standard':  $new = 'fontos'; break;
			case 'alternate': $new = 'fontcf'; break;
			case 'display':   $new = 'fontow'; break;
			case 'heading':   $new = 'fontlo'; break;
			case 'heading2':  $new = 'fontsl'; break;
		}
		$mods['logo_fontface'] = $new;
		set_theme_mod( 'logo_fontface', $mods['logo_fontface'] );
	}
	if ( isset( $mods['headings_fontface'] ) && in_array( $mods['headings_fontface'], array( 'standard', 'alternate', 'display', 'heading', 'heading2' ) ) ) {
		$new = $mods['headings_fontface'];
		switch ( $mods['headings_fontface'] ) {
			case 'standard':  $new = 'fontos'; break;
			case 'alternate': $new = 'fontcf'; break;
			case 'display':   $new = 'fontow'; break;
			case 'heading':   $new = 'fontlo'; break;
			case 'heading2':  $new = 'fontsl'; break;
		}
		$mods['headings_fontface'] = $new;
		set_theme_mod( 'headings_fontface', $mods['headings_fontface'] );
	}

	if ( !isset( $mods['sidebar_fp'] ) ) {
		if ( 'page' == get_option('show_on_front' ) ) {
			if ( function_exists( 'hoot_get_metaoption' ) && hoot_get_metaoption( 'sidebar_type', get_option( 'page_on_front' ) ) == 'custom' ) {
				$mods['sidebar_fp'] = hoot_get_metaoption( 'sidebar', get_option( 'page_on_front' ) );
			} else {
				$mods['sidebar_fp'] = 'full-width';
			}
		} else {
			$mods['sidebar_fp'] = ( isset( $mods['sidebar_archives'] ) ) ? $mods['sidebar_archives'] : ( isset( $mods['sidebar'] ) ? $mods['sidebar'] : 'wide-right' );
		}
		set_theme_mod( 'sidebar_fp', $mods['sidebar_fp'] );
	}

	// var_dump(get_theme_mods());exit;
	return $mods;
}

/* Theme Setup complete */
do_action( 'unos_loaded' );