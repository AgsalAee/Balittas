/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	if( 'undefined' == typeof hootInlineStyles )
		window.hootInlineStyles = [ 'hoot-style', {}, '' ];

	/*** Create placeholder style tags for each setting via postMessage ***/
	if ( $.isArray( hootInlineStyles ) && hootInlineStyles[1] && typeof hootInlineStyles[1] == 'object' ) {
		var csshandle = hootInlineStyles[0] + '-inline-css', rules;
		for(var index in hootInlineStyles[1]){
			if (!hootInlineStyles[1].hasOwnProperty(index)) continue;
			rules = hootInlineStyles[1][index];
			$( '#' + csshandle ).after( '<style id="hoot-customize-' + index + '" type="text/css"></style>' );
			csshandle = 'hoot-customize-' + index;
		};
	}

	/*** Utility ***/

	function hootUpdateCss( setting, value, append ) {
		var $target = $( '#hoot-customize-' + setting );
		if ( $target.length )
			if ( append !== undefined ) $target.append( value ); else $target.html( value );
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
	function hootBuildCss( index, newval ){
		var css = '', csspart = '', selectors, media, mselectors, selector;
		if ( $.isArray( hootInlineStyles ) && hootInlineStyles[1] && typeof hootInlineStyles[1] == 'object' && hootInlineStyles[1][index] && newval ) {
			for(var prop in hootInlineStyles[1][index]){
				if (!hootInlineStyles[1][index].hasOwnProperty(prop)) continue;
				selectors = hootInlineStyles[1][index][prop];
				if( prop == 'media' ){
					for(var query in selectors){
						if (!selectors.hasOwnProperty(query)) continue;
						media = selectors[query];
						css += query+'{';
						for(var mprop in media){
							if (!media.hasOwnProperty(mprop)) continue;
							mselectors = media[mprop];

							csspart = '';
							for(var mskey in mselectors){
								if (!mselectors.hasOwnProperty(mskey)) continue;
								mselector = mselectors[mskey];
								csspart += mselector + ',';
							}
							if(csspart) css += csspart.replace(/(^,)|(,$)/g, "") + '{'+mprop+':'+newval+'}';

						}
						css += '}';
					}
				} else {

					csspart = '';
					for(var skey in selectors){
						if (!selectors.hasOwnProperty(skey)) continue;
						selector = selectors[skey];
						csspart += selector + ',';
					}
					if(csspart) css += csspart.replace(/(^,)|(,$)/g, "") + '{'+prop+':'+newval+'}';

				}
			}
		}
		return css;
	}

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

	wp.customize( 'site_layout', function( value ) {
		value.bind( function( newval ) {
			$( '#page-wrapper' ).removeClass('hgrid site-boxed site-stretch');
			if ( newval == 'boxed' )
				$( '#page-wrapper' ).addClass('hgrid site-boxed');
			else
				$( '#page-wrapper' ).addClass('site-stretch');
		} );
	} );

	wp.customize( 'widgetmargin', function( value ) {
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			if ( !newvalint || isNaN( newvalint ) ) newvalint = '';
			var css = '';
			if( newvalint ) {
				var newvalintsmall = newvalint - 15;
				css = hootBuildCss( 'widgetmargin', newvalint + 'px' );
				css += '.footer .widget {margin: '+newvalintsmall+'px 0;}';
			}
			hootUpdateCss( 'widgetmargin', css );
		} );
	} );

	wp.customize( 'logo_side', function( value ) {
		this.selectiveRefresh.bind("render-partials-response", function(response) {
			var location = '', side = ''; // var side = wp.customize( 'logo_side' ).get();
			wp.customize( 'menu_location', function( setting ) { location = setting.get(); });
			wp.customize( 'logo_side', function( setting ) { side = setting.get(); });
			if ( location == 'side' ) { location = 'none'; side = 'menu'; }
			$("#header").removeClass("header-layout-primary-menu header-layout-primary-search header-layout-primary-widget-area header-layout-primary-none header-layout-secondary-top header-layout-secondary-bottom header-layout-secondary-none").addClass("header-layout-primary-"+side+" header-layout-secondary-"+location);
			$("#header-primary").removeClass("header-primary-menu header-primary-search header-primary-widget-area header-primary-none").addClass("header-primary-"+side);
			$("#header-supplementary").removeClass("header-supplementary-top header-supplementary-bottom header-supplementary-none").addClass("header-supplementary-"+location);
		});
	} );

	wp.customize( 'fullwidth_menu_align', function( value ) {
		value.bind( function( newval ) {
			$( '#header-supplementary' ).removeClass('header-supplementary-left header-supplementary-right header-supplementary-center').addClass('header-supplementary-'+newval);
		} );
	} );

	wp.customize( 'disable_table_menu', function( value ) {
		value.bind( function( newval ) {
			if (newval) $( '#header' ).removeClass('tablemenu');
			else $( '#header' ).addClass('tablemenu');
		} );
	} );

	wp.customize( 'mobile_menu', function( value ) {
		value.bind( function( newval ) {
			$( '#menu-primary, #menu-secondary' ).removeClass('mobilemenu-inline mobilemenu-fixed').addClass('mobilemenu-'+newval);
			$( '#header-supplementary' ).removeClass('header-supplementary-mobilemenu-inline header-supplementary-mobilemenu-fixed').addClass('header-supplementary-mobilemenu-'+newval);
			if ( $('#header-aside > .menu-area-wrap').length )
				$( '#header-aside' ).removeClass('header-aside-menu-inline header-aside-menu-fixed').addClass('header-aside-menu-'+newval);
		} );
	} );

	wp.customize( 'mobile_submenu_click', function( value ) {
		value.bind( function( newval ) {
			var mobilesubmenuclass = (newval) ? 'mobilesubmenu-click' : 'mobilesubmenu-open';
			$( '#menu-primary, #menu-secondary' ).removeClass('mobilesubmenu-click mobilesubmenu-open').addClass(mobilesubmenuclass);
		} );
	} );

	wp.customize( 'below_header_grid', function( value ) {
		value.bind( function( newval ) {
			var mobilesubmenuclass = (newval == 'stretch') ? 'below-header-stretch' : 'below-header-boxed';
			$( '#below-header' ).removeClass('below-header-stretch below-header-boxed').addClass(mobilesubmenuclass);
		} );
	} );

	if(!hootpload){ wp.customize( 'logo_background_type', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo' ).removeClass('accent-typo invert-accent-typo with-background');
			if ( newval == 'accent' )
				$( '#site-logo' ).addClass('accent-typo with-background');
			else if ( newval == 'invert-accent' )
				$( '#site-logo' ).addClass('invert-accent-typo with-background');
			// Redundant as 'logo_background_type' is not 'postMessage' in premium
			// Also, adding class/inlinestyle is not sufficient. Will also need to explicitly add inline for accent/transparent as custom background may be there in css.php if it was set when customizer loads.
			// else if ( newval == 'background' )
			// 	$( '#site-logo' ).addClass('with-background');
		} );
	} ); }

	wp.customize( 'logo_border', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo' ).removeClass('logo-border nopadding');
			if (newval == 'border' || newval == 'bordernopad')
				$( '#site-logo' ).addClass('logo-border');
			if (newval == 'bordernopad')
				$( '#site-logo' ).addClass('nopadding');
		} );
	} );

	if(!hootpload){ wp.customize( 'logo_size', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo-text, #site-logo-mixed' ).removeClass('site-logo-text-tiny site-logo-text-small site-logo-text-medium site-logo-text-large site-logo-text-huge').addClass( 'site-logo-text-' + newval );
		} );
	} ); }

	wp.customize( 'site_title_icon', function( value ) {
		value.bind( function( newval ) {
			if ( newval )
				$( '#site-logo-text, #site-logo-custom' ).addClass('site-logo-with-icon').find('i').remove().end().find('a').prepend('<i class="' + newval + '"></i>');
			else
				$( '#site-logo-text, #site-logo-custom' ).removeClass('site-logo-with-icon').find('i').remove();
		} );
	} );

	wp.customize( 'site_title_icon_size', function( value ) {
		value.bind( function( newval ) {
			// Doesnt include when icon is removed, or when size is changed and then icon added.
			// var $site_title_icon = $('#site-title i');
			// if ( $site_title_icon.length ) {
			// 	$site_title_icon.css('font-size',newval);
			// 	$site_title_icon.closest('#site-title').css('margin-left',newval);
			// }
			var css = hootBuildCss( 'site_title_icon_size', newval );
			hootUpdateCss( 'site_title_icon_size', css );
		} );
	} );

	wp.customize( 'logo_image_width', function( value ) {
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			if ( !newvalint || isNaN( newvalint ) ) newvalint = '150'; // default set in dynamic css.php
			var css = hootBuildCss( 'logo_image_width', newvalint + 'px' );
			hootUpdateCss( 'logo_image_width', css );
		} );
	} );

	wp.customize( 'show_tagline', function( value ) {
		value.bind( function( newval ) {
			if (newval)
				$( '#site-description' ).removeClass('noshow');
			else
				$( '#site-description' ).addClass('noshow');
		} );
	} );

	wp.customize( 'box_background_color', function( value ) {
		value.bind( function( newval ) {
			var css = hootBuildCss( 'box_background_color', newval );
			hootUpdateCss( 'box_background_color', css );
		} );
	} );

	wp.customize( 'accent_color', function( value ) {
		value.bind( function( newval ) {
			var css = hootBuildCss( 'accent_color', newval ),
					  newvaldark = hootcolor( newval, -25 );
			css += '.more-link:hover, .more-link:hover a{color:'+newvaldark+';}';
			if(!hootpload)
				css += 'a:hover,.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover{color:'+newvaldark+';}';
			hootUpdateCss( 'accent_color', css );
		} );
	} );

	wp.customize( 'accent_font', function( value ) {
		value.bind( function( newval ) {
			var css = hootBuildCss( 'accent_font', newval );
			hootUpdateCss( 'accent_font', css );
		} );
	} );

	if(!hootpload){ wp.customize( 'logo_fontface_style', function( value ) {
		value.bind( function( newval ) {
			var cssvalue = (newval=='uppercase') ? 'uppercase' : 'none';
			$( '#site-title, .site-title-line:not(.site-title-body-font,.site-title-heading-font)' ).css('text-transform',cssvalue); // #site-description
		} );
	} ); }

	if(!hootpload){ wp.customize( 'headings_fontface_style', function( value ) {
		value.bind( function( newval ) {
			var cssvalue = (newval=='uppercase') ? 'uppercase' : 'none';
			var css = hootBuildCss( 'headings_fontface_style', cssvalue );
			hootUpdateCss( 'headings_fontface_style', css );
			$( '.site-title-line.site-title-heading-font' ).css('text-transform',cssvalue);
		} );
	} ); }

	// wp.customize( 'read_more', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$( '.more-link a, a.more-link' ).html( newval );
	// 	} );
	// } );

	wp.customize( 'footer', function( value ) {
		value.bind( function( newval ) {
			var col = parseInt(newval.substr(0,1)),
				sty = parseInt(newval.substr(-1));
			if ( col && !isNaN( col ) && sty && !isNaN( sty ) ) {
				var fclasses = [12,12,12,12],
					fstyles = ['none','none','none','none'];
				switch (col) {
					case 1: fstyles[0] = 'block';
							break;
					case 2: if ( sty == 1 ) {      fclasses[0] = 6; fclasses[1] = 6; }
							else if ( sty == 2 ) { fclasses[0] = 4; fclasses[1] = 8; }
							else if ( sty == 3 ) { fclasses[0] = 8; fclasses[1] = 4; }
							fstyles[0] = fstyles[1] = 'block';
							break;
					case 3: if ( sty == 1 ) {      fclasses[0] = 4; fclasses[1] = 4; fclasses[2] = 4; }
							else if ( sty == 2 ) { fclasses[0] = 6; fclasses[1] = 3; fclasses[2] = 3; }
							else if ( sty == 3 ) { fclasses[0] = 3; fclasses[1] = 6; fclasses[2] = 3; }
							else if ( sty == 4 ) { fclasses[0] = 3; fclasses[1] = 3; fclasses[2] = 6; }
							fstyles[0] = fstyles[1] = fstyles[2] = 'block';
							break;
					case 4: fclasses[0] = fclasses[1] = fclasses[2] = fclasses[3] = 3;
							fstyles[0] = fstyles[1] = fstyles[2] = fstyles[3] = 'block';
							break;
				}
				$('.footer-column').removeClass('hgrid-span-12 hgrid-span-8 hgrid-span-6 hgrid-span-4 hgrid-span-3').removeAttr("style").each(function(index){
					$(this).addClass('hgrid-span-'+fclasses[index]).css('display',fstyles[index]);
				});
			}
		} );
	} );

	wp.customize( 'site_info', function( value ) {
		value.bind( function( newval ) {
			if ( newval == '<!--default-->' ) { wp.customize.selectiveRefresh.requestFullRefresh(); }
			else { newval = newval.replace("<!--year-->", new Date().getFullYear()); $( '#post-footer .credit' ).html( newval ); }
		} );
	} );

	// https://wordpress.stackexchange.com/questions/277594/customizer-selective-refresh-doesnt-refresh-properly-with-saved-value
	// https://wordpress.stackexchange.com/questions/247251/how-to-mix-partial-and-full-page-refresh-in-the-same-section-of-the-customizer
	// Syntax - transport postmessage
	// wp.customize( 'site_info', function( value ) {
	// 	value.bind( function( newval ) {
	// 		wp.customize.selectiveRefresh.requestFullRefresh(); // same as wp.customize.preview.send( 'refresh' ) @ref:wp-includes/js/customize-selective-refresh.js L#702
	// 		// Get changed value: // https://wordpress.stackexchange.com/questions/270554/accessing-customizer-values-in-javascript
	// 		console.log(wp.customize( 'logo_image_width' ).get());
	// 		wp.customize( 'logo_image_width', function( setting ) { var value = setting.get(); });
	// 		// Get Original Load Value (this still stays same even when published, hence not the 'saved' value)
	// 		console.log(wp.customize.settings.values.logo_image_width);
	// 	} );
	// } );
	// Syntax - addpartial selective_refresh
	// wp.customize( 'show_tagline', function( value ) {
	// 	this.selectiveRefresh.bind("render-partials-response", function(response) {
	// 		console.log('partial complete'); console.log(response);
	// 	});
	// } );


	var areapageid = '', areaids = [ 'area_a', 'area_b', 'area_c', 'area_d', 'area_e', 'area_f', 'area_g', 'area_h', 'area_i', 'area_j', 'area_k', 'area_l', 'content' ];
	// areaid must reach wp.customize and value.bind using $.each :: any derivative variable also needs to be calculated once areaid has reached value.bind
	$.each( areaids, function( index, areaid ) {
		wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var $area = $('#frontpage-'+areapageid), color = '', image = '', parallax = 0;
				wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( setting ) { parallax = setting.get(); });
				if ( $area.is('.bg-fixed.module-bg-image') || ( newval=='image' && parallax ) ) { // was parallax image or new type is image with parallax
					wp.customize.selectiveRefresh.requestFullRefresh();
				} else if ( newval == 'none' ) { // was color or image-without-parallax
					$area.removeClass('bg-fixed bg-scroll area-bgcolor').removeClass('module-bg-image module-bg-color').addClass('module-bg-none').removeAttr("style");
				} else if ( newval == 'color' ) { // was none or image-without-parallax
					wp.customize( 'frontpage_sectionbg_'+areaid+'-color', function( setting ) { color = setting.get(); });
					$area.removeClass('bg-fixed bg-scroll').removeClass('module-bg-image module-bg-none').addClass('module-bg-color area-bgcolor').removeAttr("style");
					if ( color ) $area.css('background-color',color);
				} else if ( newval == 'image' ) { // image-without-parallax: was color or none
					wp.customize( 'frontpage_sectionbg_'+areaid+'-image', function( setting ) { image = setting.get(); });
					$area.removeClass('area-bgcolor').removeClass('module-bg-color module-bg-none').addClass('module-bg-image bg-scroll').removeAttr("style");
					if ( image ) $area.css('background-image','url('+image+')');
				}
			} );
		} );
		wp.customize( 'frontpage_sectionbg_'+areaid+'-color', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var type = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if ( type=='color' ) $('#frontpage-'+areapageid).css('background-color',newval);
			} );
		} );
		wp.customize( 'frontpage_sectionbg_'+areaid+'-image', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var type = '', parallax = 0;
				wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( setting ) { parallax = setting.get(); });
				if ( parallax ) wp.customize.selectiveRefresh.requestFullRefresh();
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if ( type=='image' ) {
					if (newval) $('#frontpage-'+areapageid).css('background-image','url('+newval+')');
					else $('#frontpage-'+areapageid).css('background-image','none');
				}
			} );
		} );
		wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var type = 0;
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if ( type == 'image' ) { // refresh only if bg set to image type
					wp.customize.selectiveRefresh.requestFullRefresh();
				}
			} );
		} );
		wp.customize( 'frontpage_sectionbg_'+areaid+'-font', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var css = '', fontcolor = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-fontcolor', function( setting ) { fontcolor = setting.get(); });
				if ( fontcolor ) { switch (newval) {
					case 'color': css = '.frontpage-'+areapageid+' *, .frontpage-'+areapageid+' .more-link, .frontpage-'+areapageid+' .more-link a {color:'+fontcolor+'}'; break;
					case 'force': css = '#frontpage-'+areapageid+' *, #frontpage-'+areapageid+' .more-link, #frontpage-'+areapageid+' .more-link a {color:'+fontcolor+'}'; break;
				} }
				hootUpdateCss( 'frontpage-'+areapageid, css );
			} );
		} );
		wp.customize( 'frontpage_sectionbg_'+areaid+'-fontcolor', function( value ) {
			value.bind( function( newval ) {
				areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
				var css = '', font = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-font', function( setting ) { font = setting.get(); });
				switch (font) {
					case 'color': css = '.frontpage-'+areapageid+' *, .frontpage-'+areapageid+' .more-link, .frontpage-'+areapageid+' .more-link a {color:'+newval+'}'; break;
					case 'force': css = '#frontpage-'+areapageid+' *, #frontpage-'+areapageid+' .more-link, #frontpage-'+areapageid+' .more-link a {color:'+newval+'}'; break;
				}
				hootUpdateCss( 'frontpage-'+areapageid, css );
			} );
		} );
	} );

} )( jQuery );