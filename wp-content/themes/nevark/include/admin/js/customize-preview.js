/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	if( 'undefined' == typeof hootInlineStyles )
		window.hootInlineStyles = [ 'hoot-style', [], '' ];

	/*** Create placeholder style tags for each setting via postMessage ***/

	if ( $.isArray( hootInlineStyles ) && hootInlineStyles[1] && $.isArray( hootInlineStyles[1] ) ) {
		var csshandle = hootInlineStyles[0] + '-inline-css';
		for ( var hi = 0; hi < hootInlineStyles[1].length; hi++ ) {
			$( '#' + csshandle ).after( '<style id="hoot-customize-' + hootInlineStyles[1][hi] + '" type="text/css"></style>' );
			csshandle = 'hoot-customize-' + hootInlineStyles[1][hi];
		}
	}

	/*** Utility ***/

	function hootUpdateCss( setting, value, append = false ) {
		var $target = $( '#hoot-customize-' + setting );
		if ( $target.length )
			if ( append ) $target.append( value ); else $target.html( value );
	}
	function hootcolor(col, amt) { // @credit: https://css-tricks.com/snippets/javascript/lighten-darken-color/
		var usePound = false;
		if (col[0] == "#") { col = col.slice(1); usePound = true; }
		var num = parseInt(col,16);
		var r = (num >> 16) + amt; if (r > 255) r = 255; else if  (r < 0) r = 0;
		var b = ((num >> 8) & 0x00FF) + amt; if (b > 255) b = 255; else if  (b < 0) b = 0;
		var g = (num & 0x0000FF) + amt; if (g > 255) g = 255; else if (g < 0) g = 0;
		return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
	}
	var hootpload = hootInlineStyles[2];

	/*** Site title and description. ***/

	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo-text #site-title a, #site-logo-mixed #site-title a' ).html( newval );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '#site-description' ).html( newval );
		} );
	} );

	/** Theme Settings **/

} )( jQuery );