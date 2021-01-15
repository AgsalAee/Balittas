<?php
/**
 * Functions for sending list of fonts available
 * 
 * Also add them to sanitization array (list of allowed options)
 *
 * @package    Nevark
 * @subpackage Theme
 */

/**
 * Build URL for loading Google Fonts
 *
 * @since 1.0
 * @updated 2.9
 * @access public
 * @return void
 */
function nevark_google_fonts_enqueue_url() {
	$fonts_url = '';
	$fonts = apply_filters( 'nevark_google_fonts_preparearray', array() );
	$args = array();

	if ( empty( $fonts ) ) {
		$modsfont = array( hoot_get_mod( 'logo_fontface' ), hoot_get_mod( 'headings_fontface' ) );

		$fonts ['Roboto'] = array(
				'normal' => array( '300','400','500','600','700','800' ),
				'italic' => array( '400','700' ),
			);
		if ( in_array( 'alternate', $modsfont ) ) {
			$fonts[ 'Comfortaa' ] = array(
				'normal' => array( '400','700' ),
			);
		}
		if ( in_array( 'display', $modsfont ) ) {
			$fonts[ 'Oswald' ] = array(
				'normal' => array( '400' ),
			);
		}
		if ( in_array( 'heading', $modsfont ) ) {
			$fonts[ 'Oranienbaum' ] = array(
				'normal' => array( '400' ),
			);
		}
		if ( in_array( 'heading2', $modsfont ) ) {
			$fonts[ 'Slabo 27px' ] = array(
				'normal' => array( '400' ),
			);
		}
	}
	$fonts = apply_filters( 'nevark_google_fonts_array', $fonts );

	// Cant use 'add_query_arg()' directly as new google font api url will have multiple key 'family' when adding multiple fonts
	// Hence use 'add_query_arg' on each argument separately and then combine them.
	foreach ( $fonts as $key => $value ) {
		if ( is_array( $value ) && ( !empty( $value['normal'] ) || !empty( $value['italic'] ) ) && ( is_array( $value['normal'] ) || is_array( $value['italic'] ) ) ) {
			$arg = array( 'family' => $key . ':ital,wght@' );
			if ( !empty( $value['normal'] ) && is_array( $value['normal'] ) ) foreach ( $value['normal'] as $wght ) $arg['family'] .= "0,{$wght};";
			if ( !empty( $value['italic'] ) && is_array( $value['italic'] ) ) foreach ( $value['italic'] as $wght ) $arg['family'] .= "1,{$wght};";
			$arg['family'] = substr( $arg['family'], 0, -1 );
			$args[] = substr( add_query_arg( $arg, '' ), 1 );
		}
	}

	if ( !empty( $args ) ) {
		$fonts_url = '//fonts.googleapis.com/css2?' . implode( '&', $args );
		$fonts_url .= ( apply_filters( 'nevark_google_fonts_displayswap', false ) ) ? '&display=swap' : '';
	}

	return $fonts_url;
}

/**
 * Modify the font (websafe) list
 * Font list should always have the form:
 * {css style} => {font name}
 * 
 * Even though this list isn't currently used in customizer options (no typography options)
 * this is still needed so that sanitization functions recognize the font.
 *
 * @since 1.0
 * @access public
 * @return array
 */
function nevark_fonts_list( $fonts ) {
	$fonts['"Roboto", sans-serif']    = 'Roboto';
	$fonts['"Comfortaa", sans-serif'] = 'Comfortaa';
	$fonts['"Oswald", sans-serif']    = 'Oswald';
	$fonts['"Oranienbaum", serif']    = 'Oranienbaum';
	$fonts['"Slabo 27px", serif']     = 'Slabo 27px';
	return $fonts;
}
add_filter( 'hoot_fonts_list', 'nevark_fonts_list' );