<?php
/**
 * Defines customizer options
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 *
 * @package    Nevark
 * @subpackage Theme
 */

/**
 * Theme default colors and fonts
 *
 * @since 1.0
 * @access public
 * @param string $key return a specific key value, else the entire defaults array
 * @return array|string
 */
if ( !function_exists( 'nevark_default_style' ) ) :
function nevark_default_style( $key = false ){

	// Do not use static cache variable as any reference to 'nevark_default_style()'
	// (example: get default value during declaring add_theme_support for WP custom background which
	// is also loaded at 'after_setup_theme' hook with 10 priority) will prevent further applying
	// of filter hook (by child-theme/plugin/premium). Ideally, this function should be called only
	// after 'after_setup_theme' hook with 11 priority
	$defaults = apply_filters( 'nevark_default_style', array(
		'accent_color'         => '#000000', // a.c.
		'accent_font'          => '#ffffff', // a.f.
		'module_bg_default'    => '#f5f5f5',
		'box_background'       => '#ffffff',
		'site_background'      => '#ffffff', // Used by WP custom-background
		'widgetmargin'         => 45,
		'logo_fontface'        => 'heading',
		'headings_fontface'    => 'alternate',
	) );

	if ( $key )
		return ( isset( $defaults[ $key ] ) ) ? $defaults[ $key ] : false;
	else
		return $defaults;
}
endif;

/**
 * Build the Customizer options (panels, sections, settings)
 *
 * Always remember to mention specific priority for non-static options like:
 *     - options being added based on a condition (eg: if woocommerce is active)
 *     - options which may get removed (eg: logo_size, headings_fontface)
 *     - options which may get rearranged (eg: logo_background_type)
 *     This will allow other options inserted with priority to be inserted at
 *     their intended place.
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'nevark_customizer_options' ) ) :
function nevark_customizer_options() {

	// Stores all the settings to be added
	$settings = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Theme default colors and fonts
	extract( nevark_default_style() );

	// Directory path for radioimage buttons
	$imagepath =  hoot_data()->incuri . 'admin/images/';

	// Logo Sizes (different range than standard typography range)
	$logosizes = array();
	$logosizerange = range( 14, 110 );
	foreach ( $logosizerange as $isr )
		$logosizes[ $isr . 'px' ] = $isr . 'px';
	$logosizes = apply_filters( 'nevark_options_logosizes', $logosizes);

	// Logo Font Options for Lite version
	$logofont = apply_filters( 'nevark_options_logofont', array(
					'heading'  => esc_html__( "Logo Font (set in 'Typography' section)", 'nevark' ),
					'heading2' => esc_html__( "Heading Font (set in 'Typography' section)", 'nevark' ),
					'standard' => esc_html__( "Standard Body Font", 'nevark' ),
					) );

	/*** Add Options (Panels, Sections, Settings) ***/

	/** Section **/

	$section = 'links';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Demo Install / Support', 'nevark' ),
		'priority'    => '2',
	);

	$lcontent = '';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://demo.wphoot.com/nevark/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-eye"></i> ' .
				 esc_html__( "Demo", 'nevark') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Demo the theme features and options with sample content.", 'nevark') .
				 '</span></a>';
	$ocdilink = ( function_exists( 'hoot_lib_premium_core' ) ) ? ( ( class_exists( 'OCDI_Plugin' ) ) ? admin_url( 'themes.php?page=pt-one-click-demo-import' ) : 'https://wphoot.com/support/nevark/#docs-section-demo-content' ) : 'https://wphoot.com/support/nevark/#docs-section-demo-content-free';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 esc_url( $ocdilink ) .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-upload"></i> ' .
				 esc_html__( "1 Click Installation", 'nevark') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Install demo content to make your site look exactly like the Demo Site. Use it as a starting point instead of starting from scratch.", 'nevark') .
				 '</span></a>';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://wphoot.com/support/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="far fa-life-ring"></i> ' .
				 esc_html__( "Documentation / Support", 'nevark') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Get theme related support for both free and premium users.", 'nevark') .
				 '</span></a>';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://wordpress.org/support/theme/nevark/reviews/#new-post' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-star"></i> ' .
				 esc_html__( "Rate Us", 'nevark') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 /* translators: five stars */
				 sprintf( esc_html__( 'If you are happy with the theme, please give us a %1$s rating on wordpress.org. Thanks in advance!', 'nevark'), '<span style="color:#0073aa;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ) .
				 '</span></a>';

	$settings['linksection'] = array(
		// 'label'       => esc_html__( 'Misc Links', 'nevark' ),
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '8', // Non static options must have a priority
		'content'     => $lcontent,
	);

	/** Section **/

	$section = 'title_tagline';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Setup &amp; Layout', 'nevark' ),
	);

	$settings['site_layout'] = array(
		'label'       => esc_html__( 'Site Layout - Boxed vs Stretched', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'boxed'   => esc_html__( 'Boxed layout', 'nevark' ),
			'stretch' => esc_html__( 'Stretched layout (full width)', 'nevark' ),
		),
		'default'     => 'stretch',
	);

	$settings['site_width'] = array(
		'label'       => esc_html__( 'Max. Site Width (pixels)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'1380' => esc_html__( '1380px (wide)', 'nevark' ),
			'1260' => esc_html__( '1260px (normal)', 'nevark' ),
			'1080' => esc_html__( '1080px (compact)', 'nevark' ),
		),
		'default'     => '1380',
	);

	$settings['load_minified'] = array(
		'label'       => esc_html__( 'Load Minified Styles and Scripts (when available)', 'nevark' ),
		'sublabel'    => esc_html__( 'Checking this option reduces data size, hence increasing page load speed.', 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
	);

	$settings['sidebar'] = array(
		'label'       => esc_html__( 'Sidebar Layout (Site-wide)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
			'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
			'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
		'description' => esc_html__( 'Set the default sidebar width and position for your site.', 'nevark' ),
	);

	$settings['sidebar_fp'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Front Page)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
			'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
			'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => ( ( 'page' == get_option('show_on_front' ) ) ? 'full-width' : 'wide-right' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'This is sidebar for the Frontpage Content Module in %1$sFrontpage Modules Settings%2$s', 'nevark' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=frontpage_content_desc' ) ) . '" rel="focuslink" data-focustype="control" data-href="frontpage_content_desc">', '</a>' ),
	);

	$settings['sidebar_archives'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Blog/Archives)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
			'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
			'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	$settings['sidebar_pages'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Pages)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
			'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
			'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	$settings['sidebar_posts'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for single Posts)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
			'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
			'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	if ( current_theme_supports( 'woocommerce' ) ) :

		$settings['sidebar_wooshop'] = array(
			'label'       => esc_html__( 'Sidebar Layout (Woocommerce Shop/Archives)', 'nevark' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '83', // Non static options must have a priority
			'choices'     => array(
				'wide-right'         => $imagepath . 'sidebar-wide-right.png',
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'wide-left'          => $imagepath . 'sidebar-wide-left.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
				'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
				'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'wide-right',
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce Shop and Archives pages like product categories etc.', 'nevark' ),
		);

		$settings['sidebar_wooproduct'] = array(
			'label'       => esc_html__( 'Sidebar Layout (Woocommerce Single Product Page)', 'nevark' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '83', // Non static options must have a priority
			'choices'     => array(
				'wide-right'         => $imagepath . 'sidebar-wide-right.png',
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'wide-left'          => $imagepath . 'sidebar-wide-left.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
				'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
				'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'wide-right',
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce product page', 'nevark' ),
		);

	endif;

	$settings['disable_sticky_sidebar'] = array(
		'label'       => esc_html__( 'Disable Sticky Sidebar', 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'description' => esc_html__( 'Check this if you do not want to display a fixed Sidebar the user scrolls down the page.', 'nevark' ),
	);

	$settings['widgetmargin'] = array(
		'label'       => esc_html__( 'Widget Margin', 'nevark' ),
		'section'     => $section,
		'type'        => 'text',
		'default'     => $widgetmargin,
		'description' => esc_html__( '(in pixels) Margin space above and below widgets. Leave empty if you dont want to change the default.', 'nevark' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: 45', 'nevark' ),
		),
	);

	/** Section **/

	$section = 'header';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Header', 'nevark' ),
	);

	$settings['primary_menuarea'] = array(
		'label'       => esc_html__( 'Header Area (right of logo)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'search'      => esc_html__( 'Display Search', 'nevark' ),
			'widget-area' => esc_html__( "'Header Side' widget area", 'nevark' ),
			'none'        => esc_html__( 'None (Logo will get centre aligned)', 'nevark' ),
		),
		'default'     => 'none',
	);

	$settings['secondary_menu_location'] = array(
		'label'       => esc_html__( 'Full Width Menu Area (location)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'        => esc_html__( 'Top (above logo)', 'nevark' ),
			'bottom'     => esc_html__( 'Bottom (below logo)', 'nevark' ),
			// 'none'       => esc_html__( "Do not display full width menu (useful if you already have 'menu' selected in 'Header Area' above')", 'nevark' ),
		),
		'default'     => 'bottom',
	);

	$settings['secondary_menu_align'] = array(
		'label'       => esc_html__( 'Full Width Menu Area (alignment)', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'left'      => esc_html__( 'Left', 'nevark' ),
			'right'     => esc_html__( 'Right', 'nevark' ),
			'center'    => esc_html__( 'Center', 'nevark' ),
		),
		'default'     => 'left',
	);

	$settings['disable_table_menu'] = array(
		'label'       => esc_html__( 'Disable Table Menu', 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( '%1$s%2$sDisable Table Menu if you have a lot of Top Level menu items, %3$sand dont have menu item descriptions.%4$s', 'nevark' ), "<img src='{$imagepath}menu-table.png'>", '<br />', '<strong>', '</strong>' ),
	);

	$settings['mobile_menu'] = array(
		'label'       => esc_html__( 'Mobile Menu', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'inline' => esc_html__( 'Inline - Menu Slide Downs to open', 'nevark' ),
			'fixed'  => esc_html__( 'Fixed - Menu opens on the left', 'nevark' ),
		),
		'default'     => 'fixed',
	);

	$settings['mobile_submenu_click'] = array(
		'label'       => esc_html__( "[Mobile Menu] Submenu opens on 'Click'", 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
		'description' => esc_html__( "Uncheck this option to make all Submenus appear in 'Open' state. By default, submenus open on clicking (i.e. single tap on mobile).", 'nevark' ),
	);

	$settings['below_header_grid'] = array(
		'label'       => esc_html__( "'Below Header' widget area layout", 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'boxed'   => $imagepath . 'fp-widgetarea-boxed.png',
			'stretch' => $imagepath . 'fp-widgetarea-stretch.png',
		),
		'default'     => 'boxed',
		'priority'    => '165',
	);

	/** Section **/

	$section = 'logo';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Logo', 'nevark' ),
	);

	$settings['logo_background_type'] = array(
		'label'       => esc_html__( 'Logo Background', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'priority'    => '165', // Non static options must have a priority
		'choices'     => array(
			'transparent' => esc_html__( 'None', 'nevark' ),
			'accent'      => esc_html__( 'Accent Color', 'nevark' ),
			'invert'      => esc_html__( 'Accent Font Color', 'nevark' ),
		),
		'default'     => 'accent',
	); // Overridden in premium

	$settings['logo'] = array(
		'label'       => esc_html__( 'Site Logo', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'text'        => esc_html__( 'Default Text (Site Title)', 'nevark' ),
			'custom'      => esc_html__( 'Custom Text', 'nevark' ),
			'image'       => esc_html__( 'Image Logo', 'nevark' ),
			'mixed'       => esc_html__( 'Image &amp; Default Text (Site Title)', 'nevark' ),
			'mixedcustom' => esc_html__( 'Image &amp; Custom Text', 'nevark' ),
		),
		'default'     => 'text',
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'description' => sprintf( esc_html__( 'Use %1$sSite Title%2$s as default text logo', 'nevark' ), '<a href="' . esc_url( admin_url('options-general.php') ) . '" target="_blank">', '</a>' ),

		/*** Use JS API (in customize.js) for conditional controls using 'logo' setting in their active_callback ***/
		// 'selective_refresh' => array( 'logo_partial', array(
		// 	'selector'            => '#branding',
		// 	'settings'            => array( 'logo', 'show_tagline', 'logo_custom' ), // Do not add 'logo_size' to 'settings' array
		// 																			 // since it is removed in premium, and hence this
		// 																			 // selective_refresh wont work
		// 	'primary_setting'     => 'logo', // Redundant as 'logo' is first ID in settings array
		// 	'render_callback'     => 'nevark_branding',
		// 	'container_inclusive' => true,
		// 	) ),

	);

	$settings['logo_size'] = array(
		'label'       => esc_html__( 'Logo Text Size', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '175', // Non static options must have a priority
		'choices'     => array(
			'tiny'   => esc_html__( 'Tiny', 'nevark'),
			'small'  => esc_html__( 'Small', 'nevark'),
			'medium' => esc_html__( 'Medium', 'nevark'),
			'large'  => esc_html__( 'Large', 'nevark'),
			'huge'   => esc_html__( 'Huge', 'nevark'),
		),
		'default'     => 'huge',
		'active_callback' => 'nevark_callback_logo_size',
	); // Removed in premium

	$settings['site_title_icon'] = array(
		'label'           => esc_html__( 'Site Title Icon (Optional)', 'nevark' ),
		'section'         => $section,
		'type'            => 'icon',
		// 'default'         => 'fa-anchor fas',
		'description'     => esc_html__( 'Leave empty to hide icon.', 'nevark' ),
		'active_callback' => 'nevark_callback_site_title_icon',
	);

	$settings['site_title_icon_size'] = array(
		'label'           => esc_html__( 'Site Title Icon Size', 'nevark' ),
		'section'         => $section,
		'type'            => 'select',
		'choices'         => $logosizes,
		'default'         => '50px',
		'active_callback' => 'nevark_callback_site_title_icon',
	);

	$settings['logo_image_width'] = array(
		'label'           => esc_html__( 'Maximum Logo Width', 'nevark' ),
		'section'         => $section,
		'type'            => 'text',
		'priority'        => '196', // Keep it with logo image ( 'custom_logo' )->priority logo
		'default'         => 200,
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description'     => sprintf( esc_html__( '(in pixels)%1$sThe logo width may be automatically adjusted by the browser depending on title length and space available.', 'nevark' ), '<hr>' ),
		'input_attrs'     => array(
			'placeholder' => esc_html__( '(in pixels)', 'nevark' ),
		),
		'active_callback' => 'nevark_callback_logo_image_width',
	);

	$logo_custom_line_options = array(
		'text' => array(
			'label'       => esc_html__( 'Line Text', 'nevark' ),
			'type'        => 'text',
		),
		'size' => array(
			'label'       => esc_html__( 'Line Size', 'nevark' ),
			'type'        => 'select',
			'choices'     => $logosizes,
			'default'     => '24px',
		),
		'font' => array(
			'label'       => esc_html__( 'Line Font', 'nevark' ),
			'type'        => 'select',
			'choices'     => $logofont,
			'default'     => 'heading',
		),
	);

	$settings['logo_custom'] = array(
		'label'           => esc_html__( 'Custom Logo Text', 'nevark' ),
		'section'         => $section,
		'type'            => 'sortlist',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description'     => sprintf( esc_html__( 'Use &lt;b&gt; and &lt;em&gt; tags in "Line Text" fields below to emphasize different words. Example:%1$s%2$s&lt;b&gt;Hoot&lt;/b&gt; &lt;em&gt;Nevark&lt;/em&gt;%3$s', 'nevark' ), '<br />', '<code>', '</code>' ),
		'choices'         => array(
			'line1' => esc_html__( 'Line 1', 'nevark' ),
			'line2' => esc_html__( 'Line 2', 'nevark' ),
			'line3' => esc_html__( 'Line 3', 'nevark' ),
			'line4' => esc_html__( 'Line 4', 'nevark' ),
		),
		'default'     => array(
			'line3'  => array( 'sortitem_hide' => 1, ),
			'line4'  => array( 'sortitem_hide' => 1, ),
		),
		'options'         => array(
			'line1' => $logo_custom_line_options,
			'line2' => $logo_custom_line_options,
			'line3' => $logo_custom_line_options,
			'line4' => $logo_custom_line_options,
		),
		'attributes'      => array(
			'hideable'   => true,
			'sortable'   => false,
			// 'open-state' => 'first',
		),
		'active_callback' => 'nevark_callback_logo_custom',
	);

	$settings['show_tagline'] = array(
		'label'           => esc_html__( 'Show Tagline', 'nevark' ),
		'sublabel'        => esc_html__( 'Display site description as tagline below logo.', 'nevark' ),
		'section'         => $section,
		'type'            => 'checkbox',
		'default'         => 0,
		// 'active_callback' => 'nevark_callback_show_tagline',
	);

	/** Section **/

	$section = 'colors';

	// Redundant as 'colors' section is added by WP. But we still add it for brevity
	$sections[ $section ] = array(
		'title'       => esc_html__( 'Colors', 'nevark' ),
		// 'description' => __( 'The premium version comes with color and background options for different sections of your site like header, menu dropdown, content area, logo background, footer etc.', 'nevark' ),
	);

	$settings['box_background_color'] = array(
		'label'       => esc_html__( 'Site Content Background', 'nevark' ),
		'section'     => $section,
		'type'        => 'color',
		'priority'    => '215', // Non static options must have a priority
		'default'     => $box_background,
	); // Overridden in premium

	$settings['accent_color'] = array(
		'label'       => esc_html__( 'Accent Color', 'nevark' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_color,
		// 'transport' => 'postMessage',
	);

	$settings['accent_font'] = array(
		'label'       => esc_html__( 'Font Color on Accent Color', 'nevark' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_font,
		// 'transport' => 'postMessage',
	);

	if ( current_theme_supports( 'woocommerce' ) ) :
		$settings['woocommerce-colors-plugin'] = array(
			'label'       => esc_html__( 'Woocommerce Styling', 'nevark' ),
			'section'     => $section,
			'type'        => 'content',
			'priority'    => '235', // Non static options must have a priority
			/* Translators: 1 is the link start markup, 2 is link markup end */
			'content'     => sprintf( esc_html__( 'Looks like you are using Woocommerce. Install %1$sthis plugin%2$s to change colors and styles for WooCommerce elements like buttons etc.', 'nevark' ), '<a href="https://wordpress.org/plugins/woocommerce-colors/" target="_blank">', '</a>' ),
		);
	endif;

	/** Section **/

	$section = 'typography';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Typography', 'nevark' ),
		// 'description' => esc_html__( 'The premium version offers complete typography control (color, style, size) for various headings, header, menu, footer, widgets, content sections etc (over 600 Google Fonts to chose from)', 'nevark' ),
	);

	$settings['logo_fontface'] = array(
		'label'       => esc_html__( 'Logo Font (Free Version)', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 237, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard Font (Roboto)', 'nevark'),
			'alternate' => esc_html__( 'Alternate Font (Comfortaa)', 'nevark'),
			'display'   => esc_html__( 'Display Font (Oswald)', 'nevark'),
			'heading'   => esc_html__( 'Heading Font 1 (Oranienbaum)', 'nevark'),
			'heading2'  => esc_html__( 'Heading Font 2 (Slabo)', 'nevark'),
		),
		'default'     => $logo_fontface,
	); // Removed in premium

	$settings['logo_fontface_style'] = array(
		'label'       => esc_html__( 'Logo Font Style', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 237, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard', 'nevark'),
			'uppercase' => esc_html__( 'Uppercase', 'nevark'),
		),
		'default'     => 'standard',
	); // Removed in premium

	$settings['headings_fontface'] = array(
		'label'       => esc_html__( 'Headings Font (Free Version)', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 237, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard Font (Roboto)', 'nevark'),
			'alternate' => esc_html__( 'Alternate Font (Comfortaa)', 'nevark'),
			'display'   => esc_html__( 'Display Font (Oswald)', 'nevark'),
			'heading'   => esc_html__( 'Heading Font 1 (Oranienbaum)', 'nevark'),
			'heading2'  => esc_html__( 'Heading Font 2 (Slabo)', 'nevark'),
		),
		'default'     => $headings_fontface,
	); // Removed in premium

	$settings['headings_fontface_style'] = array(
		'label'       => esc_html__( 'Heading Font Style', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 237, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard', 'nevark'),
			'uppercase' => esc_html__( 'Uppercase', 'nevark'),
		),
		'default'     => 'standard',
	); // Removed in premium

	/** Section **/

	$section = 'frontpage';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Frontpage - Modules', 'nevark' ),
	);

	$widget_area_options = array(
		'columns' => array(
			'label'   => esc_html__( 'Columns', 'nevark' ),
			'type'    => 'select',
			'choices' => array(
				'100'         => esc_html__( 'One Column [100]', 'nevark' ),
				'50-50'       => esc_html__( 'Two Columns [50 50]', 'nevark' ),
				'33-66'       => esc_html__( 'Two Columns [33 66]', 'nevark' ),
				'66-33'       => esc_html__( 'Two Columns [66 33]', 'nevark' ),
				'25-75'       => esc_html__( 'Two Columns [25 75]', 'nevark' ),
				'75-25'       => esc_html__( 'Two Columns [75 25]', 'nevark' ),
				'33-33-33'    => esc_html__( 'Three Columns [33 33 33]', 'nevark' ),
				'25-25-50'    => esc_html__( 'Three Columns [25 25 50]', 'nevark' ),
				'25-50-25'    => esc_html__( 'Three Columns [25 50 25]', 'nevark' ),
				'50-25-25'    => esc_html__( 'Three Columns [50 25 25]', 'nevark' ),
				'25-25-25-25' => esc_html__( 'Four Columns [25 25 25 25]', 'nevark' ),
			),
		),
		'grid' => array(
			'label'    => esc_html__( 'Layout', 'nevark' ),
			'sublabel' => esc_html__( 'The fully stretched grid layout is especially useful for displaying full width slider widgets.', 'nevark' ),
			'type'     => 'radioimage',
			'choices'     => array(
				'boxed'   => $imagepath . 'fp-widgetarea-boxed.png',
				'stretch' => $imagepath . 'fp-widgetarea-stretch.png',
			),
			'default'  => 'boxed',
		),
		'modulebg' => array(
			'label'       => '',
			'type'        => 'content',
			'content'     => '<div class="button">' . esc_html__( 'Module Background', 'nevark' ) . '</div>',
		),
	);

	$settings['frontpage_sections'] = array(
		'label'       => esc_html__( 'Frontpage Widget Areas', 'nevark' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'sublabel'    => sprintf( esc_html__( '%1$s%3$sSort different sections of the Frontpage in the order you want them to appear.%4$s%3$sYou can add content to widget areas from the %5$sWidgets Management screen%6$s.%4$s%3$sYou can disable areas by clicking the "eye" icon. (This will hide them on the Widgets screen as well)%4$s%2$s', 'nevark' ), '<ul>', '</ul>', '<li>', '</li>', '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'area_a'      => esc_html__( 'Widget Area A', 'nevark' ),
			'area_b'      => esc_html__( 'Widget Area B', 'nevark' ),
			'area_c'      => esc_html__( 'Widget Area C', 'nevark' ),
			'area_d'      => esc_html__( 'Widget Area D', 'nevark' ),
			'content'     => esc_html__( 'Frontpage Content', 'nevark' ),
			'area_e'      => esc_html__( 'Widget Area E', 'nevark' ),
			'area_f'      => esc_html__( 'Widget Area F', 'nevark' ),
			'area_g'      => esc_html__( 'Widget Area G', 'nevark' ),
			'area_h'      => esc_html__( 'Widget Area H', 'nevark' ),
			'area_i'      => esc_html__( 'Widget Area I', 'nevark' ),
			'area_j'      => esc_html__( 'Widget Area J', 'nevark' ),
		),
		'default'     => array(
			// 'content' => array( 'sortitem_hide' => 1, ),
			// 'area_b'  => array( 'columns' => '50-50' ),
			'area_c'  => array( 'columns' => '50-50' ),
			'area_f'  => array( 'sortitem_hide' => 1, ),
			'area_g'  => array( 'sortitem_hide' => 1, ),
			'area_h'  => array( 'sortitem_hide' => 1, ),
			'area_i'  => array( 'sortitem_hide' => 1, ),
			'area_j'  => array( 'sortitem_hide' => 1, ),
		),
		'options'     => array(
			'area_a'      => $widget_area_options,
			'area_b'      => $widget_area_options,
			'area_c'      => $widget_area_options,
			'area_d'      => $widget_area_options,
			'area_e'      => $widget_area_options,
			'area_f'      => $widget_area_options,
			'area_g'      => $widget_area_options,
			'area_h'      => $widget_area_options,
			'area_i'      => $widget_area_options,
			'area_j'      => $widget_area_options,
			'content'     => array(
							'title' => array(
								'label'       => esc_html__( 'Title (optional)', 'nevark' ),
								'type'        => 'text',
							),
							'modulebg' => array(
								'label'       => '',
								'type'        => 'content',
								'content'     => '<div class="button">' . esc_html__( 'Module Background', 'nevark' ) . '</div>',
							), ),
		),
		'attributes'  => array(
			'hideable'      => true,
			'sortable'      => true,
			'open-state'    => 'area_a',
		),
		// /* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		// 'description' => sprintf( esc_html__( 'You must first save the changes you make here and refresh this screen for widget areas to appear in the Widgets panel (in customizer). Once you save the settings, you can add content to these widget areas using the %1$sWidgets Management screen%2$s.', 'nevark' ), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['frontpage_content_desc'] = array(
		'label'       => esc_html__( "Frontpage Content", 'nevark' ),
		'section'     => $section,
		'type'        => 'content',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'The "Frontpage Content" module in above list will show %1$s%3$sthe %5$s"Blog"%6$s if you have %5$sYour Latest Posts%6$s selectd in %7$sReading Settings%8$s %4$s%3$sthe %5$s"Page Content"%6$s of the page set as Front page if you have %5$sA static page%6$s selected in %7$sReading Settings%8$s %4$s%2$s',
				'nevark' ), "<ul style='list-style:disc;margin:1em 0 0 2em;'>", '</ul>', '<li>', '</li>', '<strong>', '</strong>',
									 '<a href="' . esc_url( admin_url('options-reading.php') ) . '" target="_blank">', '</a>' ),
	);

	$frontpagemodule_bg = apply_filters( 'nevark_frontpage_widgetarea_sectionbg_index', array(
		'area_a'      => esc_html__( 'Widget Area A', 'nevark' ),
		'area_b'      => esc_html__( 'Widget Area B', 'nevark' ),
		'area_c'      => esc_html__( 'Widget Area C', 'nevark' ),
		'area_d'      => esc_html__( 'Widget Area D', 'nevark' ),
		'area_e'      => esc_html__( 'Widget Area E', 'nevark' ),
		'area_f'      => esc_html__( 'Widget Area F', 'nevark' ),
		'area_g'      => esc_html__( 'Widget Area G', 'nevark' ),
		'area_h'      => esc_html__( 'Widget Area H', 'nevark' ),
		'area_i'      => esc_html__( 'Widget Area I', 'nevark' ),
		'area_j'      => esc_html__( 'Widget Area J', 'nevark' ),
		'content'     => esc_html__( 'Frontpage Content', 'nevark' ),
		) );

	foreach ( $frontpagemodule_bg as $fpgmodid => $fpgmodname ) {

		$settings["frontpage_sectionbg_{$fpgmodid}"] = array(
			'label'       => '',
			'section'     => $section,
			'type'        => 'group',
			'priority'    => 255,
			'startwrap'   => 'fp-section-bg-button',
			'button'      => esc_html__( 'Module Background', 'nevark' ),
			'options'     => array(
				'description' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . $fpgmodname . '</span>',
				),
				'type' => array(
					'label'   => esc_html__( 'Background Type', 'nevark' ),
					'type'    => 'radio',
					'choices' => array(
						'none'        => esc_html__( 'None', 'nevark' ),
						// 'highlight'   => esc_html__( 'Highlight', 'nevark' ),
						'color'       => esc_html__( 'Color', 'nevark' ),
						'image'       => esc_html__( 'Image', 'nevark' ),
					),
					'default' => 'none',
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' :
					// 											( ( $fpgmodid == 'area_d' ) ? 'highlight' : 'none' )
					// 			 ),
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' : 'none' ),
				),
				'color' => array(
					'label'       => esc_html__( "Background Color (Select 'Color' above)", 'nevark' ),
					'type'        => 'color',
					'default'     => $module_bg_default,
				),
				'image' => array(
					'label'       => esc_html__( "Background Image (Select 'Image' above)", 'nevark' ),
					'type'        => 'image',
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? hoot_data()->template_uri . 'images/modulebg.jpg' : '' ),
				),
				'parallax' => array(
					'label'   => esc_html__( 'Apply Parallax Effect to Background Image', 'nevark' ),
					'type'    => 'checkbox',
					'default' => 1,
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 1 : 0 ),
				),
				'font' => array(
					'label'   => esc_html__( 'Font Color', 'nevark' ),
					'type'    => 'radio',
					'choices' => array(
						'theme'       => esc_html__( 'Default Theme Color', 'nevark' ),
						'color'       => esc_html__( 'Custom Font Color', 'nevark' ),
						'force'       => esc_html__( 'Force Custom Font Color', 'nevark' ),
					),
					'default' => 'theme',
				),
				'fontcolor' => array(
					'label'       => esc_html__( "Custom Font Color (select 'Custom Font Color' above)", 'nevark' ),
					'type'        => 'color',
					'default'     => '#aaaaaa',
				),
			),
		);

	} // end for

	/** Section **/

	$section = 'archives';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Archives (Blog, Cats, Tags)', 'nevark' ),
	);

	$settings['archive_type'] = array(
		'label'       => esc_html__( 'Archive (Blog) Layout', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'big'          => $imagepath . 'archive-big.png',
			'block2'       => $imagepath . 'archive-block2.png',
			'block3'       => $imagepath . 'archive-block3.png',
			'mixed-block2' => $imagepath . 'archive-mixed-block2.png',
			'mixed-block3' => $imagepath . 'archive-mixed-block3.png',
		),
		'default'     => 'mixed-block2',
	);

	$settings['archive_post_content'] = array(
		'label'       => esc_html__( 'Post Items Content', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'none' => esc_html__( 'None', 'nevark' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'nevark' ),
			'full-content' => esc_html__( 'Full Post Content', 'nevark' ),
		),
		'default'     => 'excerpt',
		'description' => esc_html__( 'Content to display for each post in the list', 'nevark' ),
	);

	$settings['archive_post_meta'] = array(
		'label'       => esc_html__( 'Meta Information for Post List Items', 'nevark' ),
		'sublabel'    => esc_html__( 'Check which meta information to display for each post item in the archive list.', 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'nevark' ),
			'date'     => esc_html__( 'Date', 'nevark' ),
			'cats'     => esc_html__( 'Categories', 'nevark' ),
			'tags'     => esc_html__( 'Tags', 'nevark' ),
			'comments' => esc_html__( 'No. of comments', 'nevark' ),
		),
		'default'     => 'author, date, cats, comments',
	);

	$settings['excerpt_length'] = array(
		'label'       => esc_html__( 'Excerpt Length', 'nevark' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( 'Number of words in excerpt. Default is 50. Leave empty if you dont want to change it.', 'nevark' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: 50', 'nevark' ),
		),
	);

	$settings['read_more'] = array(
		'label'       => esc_html__( "'Continue Reading' Text", 'nevark' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( "Replace the default 'Continue Reading' text. Leave empty if you dont want to change it.", 'nevark' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: Continue Reading &rarr;', 'nevark' ),
		),
		'default'     => esc_html__( 'Continue Reading', 'nevark' ),
	);

	/** Section **/

	$section = 'singular';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Single (Posts, Pages)', 'nevark' ),
	);

	$settings['page_header_full'] = array(
		'label'       => esc_html__( 'Stretch Page Title Area to Full Width', 'nevark' ),
		'sublabel'    => '<img src="' . $imagepath . 'page-header.png">',
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'default'    => esc_html__( 'Default (Archives, Blog, Woocommerce etc.)', 'nevark' ),
			'posts'      => esc_html__( 'For All Posts', 'nevark' ),
			'pages'      => esc_html__( 'For All Pages', 'nevark' ),
			'no-sidebar' => esc_html__( 'Always override for full width pages (any page which has no sidebar)', 'nevark' ),
		),
		'default'     => 'default, pages, no-sidebar',
		'description' => esc_html__( 'This is the Page Header area containing Page/Post Title and Meta details like author, categories etc.', 'nevark' ),
	);

	$settings['post_featured_image'] = array(
		'label'       => esc_html__( 'Display Featured Image (Post)', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'none'                => esc_html__( 'Do not display', 'nevark'),
			'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'nevark'),
			'staticheader'        => esc_html__( 'Header Background (Cropped)', 'nevark'),
			'header'              => esc_html__( 'Header Background (Parallax Effect)', 'nevark'),
			'content'             => esc_html__( 'Beginning of content', 'nevark'),
		),
		'default'     => 'content',
		'description' => esc_html__( 'Display featured image on a Post page.', 'nevark' ),
	);

	$settings['post_featured_image_page'] = array(
		'label'       => esc_html__( 'Display Featured Image (Page)', 'nevark' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'none'                => esc_html__( 'Do not display', 'nevark'),
			'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'nevark'),
			'staticheader'        => esc_html__( 'Header Background (Cropped)', 'nevark'),
			'header'              => esc_html__( 'Header Background (Parallax Effect)', 'nevark'),
			'content'             => esc_html__( 'Beginning of content', 'nevark'),
		),
		'default'     => 'header',
		'description' => esc_html__( "Display featured image on a 'Page' page.", 'nevark' ),
	);

	$settings['post_meta'] = array(
		'label'       => esc_html__( 'Meta Information on Posts', 'nevark' ),
		'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Post' page", 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'nevark' ),
			'date'     => esc_html__( 'Date', 'nevark' ),
			'cats'     => esc_html__( 'Categories', 'nevark' ),
			'tags'     => esc_html__( 'Tags', 'nevark' ),
			'comments' => esc_html__( 'No. of comments', 'nevark' )
		),
		'default'     => 'author, date, cats, tags, comments',
	);

	$settings['page_meta'] = array(
		'label'       => esc_html__( 'Meta Information on Page', 'nevark' ),
		'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Page' page", 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'nevark' ),
			'date'     => esc_html__( 'Date', 'nevark' ),
			'comments' => esc_html__( 'No. of comments', 'nevark' ),
		),
		'default'     => 'author, date, comments',
	);

	$settings['post_meta_location'] = array(
		'label'       => esc_html__( 'Meta Information location', 'nevark' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'    => esc_html__( 'Top (below title)', 'nevark' ),
			'bottom' => esc_html__( 'Bottom (after content)', 'nevark' ),
		),
		'default'     => 'top',
	);

	$settings['post_prev_next_links'] = array(
		'label'       => esc_html__( 'Previous/Next Posts', 'nevark' ),
		'sublabel'    => esc_html__( 'Display links to Prev/Next Post links at the end of post content.', 'nevark' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
	);

	$settings['contact-form'] = array(
		'label'       => esc_html__( 'Contact Form', 'nevark' ),
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '375', // Non static options must have a priority
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'content'     => sprintf( esc_html__( 'This theme comes bundled with CSS required to style %1$sContact-Form-7%2$s forms. Simply install and activate the plugin to add Contact Forms to your pages.', 'nevark' ), '<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">', '</a>' ), // @todo update link to docs
	);

	/** Section **/

	$section = 'footer';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Footer', 'nevark' ),
	);

	$settings['footer'] = array(
		'label'       => esc_html__( 'Footer Layout', 'nevark' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'1-1' => $imagepath . '1-1.png',
			'2-1' => $imagepath . '2-1.png',
			'2-2' => $imagepath . '2-2.png',
			'2-3' => $imagepath . '2-3.png',
			'3-1' => $imagepath . '3-1.png',
			'3-2' => $imagepath . '3-2.png',
			'3-3' => $imagepath . '3-3.png',
			'3-4' => $imagepath . '3-4.png',
			'4-1' => $imagepath . '4-1.png',
		),
		'default'     => '4-1',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'You must first save the changes you make here and refresh this screen for footer columns to appear in the Widgets panel (in customizer).%3$s Once you save the settings here, you can add content to footer columns using the %1$sWidgets Management screen%2$s.', 'nevark' ), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>', '<hr>' ),
	);

	$settings['site_info'] = array(
		'label'       => esc_html__( 'Site Info Text (footer)', 'nevark' ),
		'section'     => $section,
		'type'        => 'textarea',
		'default'     => esc_html__( '<!--default-->', 'nevark'),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'Text shown in footer. Useful for showing copyright info etc.%3$sUse the %4$s&lt;!--default--&gt;%5$s tag to show the default Info Text.%3$sUse the %4$s&lt;!--year--&gt;%5$s tag to insert the current year.%3$sAlways use %1$sHTML codes%2$s for symbols. For example, the HTML for &copy; is %4$s&amp;copy;%5$s', 'nevark' ), '<a href="http://ascii.cl/htmlcodes.htm" target="_blank">', '</a>', '<hr>', '<code>', '</code>' ),
	);


	/*** Return Options Array ***/
	return apply_filters( 'nevark_customizer_options', array(
		'settings' => $settings,
		'sections' => $sections,
		'panels'   => $panels,
	) );

}
endif;

/**
 * Add Options (settings, sections and panels) to Hoot_Customize class options object
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'nevark_add_customizer_options' ) ) :
function nevark_add_customizer_options() {

	$hoot_customize = Hoot_Customize::get_instance();

	// Add Options
	$options = nevark_customizer_options();
	$hoot_customize->add_options( array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) );

}
endif;
add_action( 'init', 'nevark_add_customizer_options', 0 ); // cannot hook into 'after_setup_theme' as this hook is already being executed (this file is loaded at after_setup_theme @priority 10) (hooking into same hook from within while hook is being executed leads to undesirable effects as $GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
// Hence, we hook into 'init' @priority 0, so that settings array gets populated before 'widgets_init' action ( which itself is hooked to 'init' at priority 1 ) for creating widget areas ( settings array is needed for creating defaults when user value has not been stored )

/**
 * Enqueue custom scripts to customizer screen
 *
 * @since 1.0
 * @return void
 */
function nevark_customizer_enqueue_scripts() {
	// Enqueue Styles
	$style_uri = ( function_exists( 'hoot_locate_style' ) ) ? hoot_locate_style( hoot_data()->incuri . 'admin/css/customize' ) : hoot_data()->incuri . 'admin/css/customize.css';
	wp_enqueue_style( 'nevark-customize-styles', $style_uri, array(),  hoot_data()->hoot_version );
	// Enqueue Scripts
	$script_uri = ( function_exists( 'hoot_locate_script' ) ) ? hoot_locate_script( hoot_data()->incuri . 'admin/js/customize-controls' ) : hoot_data()->incuri . 'admin/js/customize-controls.js';
	wp_enqueue_script( 'nevark-customize-controls', $script_uri, array( 'jquery', 'wp-color-picker', 'customize-controls', 'hoot-customize' ), hoot_data()->hoot_version, true );
}
// Load scripts at priority 12 so that Hoot Customizer Interface (11) / Custom Controls (10) have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'nevark_customizer_enqueue_scripts', 12 );

/**
 * Modify default WordPress Settings Sections and Panels
 *
 * @since 1.0
 * @param object $wp_customize
 * @return void
 */
function nevark_modify_default_customizer_options( $wp_customize ) {

	/**
	 * Defaults: [type] => cropped_image
	 *           [width] => 150
	 *           [height] => 150
	 *           [flex_width] => 1
	 *           [flex_height] => 1
	 *           [button_labels] => array(...)
	 *           [label] => Logo
	 */
	$wp_customize->get_control( 'custom_logo' )->section = 'logo';
	$wp_customize->get_control( 'custom_logo' )->priority = 195;
	$wp_customize->get_control( 'custom_logo' )->width = 300;
	$wp_customize->get_control( 'custom_logo' )->height = 180;
	// $wp_customize->get_control( 'custom_logo' )->type = 'image'; // Stored value becomes url instead of image ID (fns like the_custom_logo() dont work)
	$wp_customize->get_control( 'custom_logo' )->active_callback = 'nevark_callback_logo_image';

	if ( function_exists( 'get_site_icon_url' ) )
		$wp_customize->get_control( 'site_icon' )->priority = 10;

	$wp_customize->get_section( 'static_front_page' )->priority = 3;
	if ( current_theme_supports( 'custom-header' ) ) {
		$wp_customize->get_section( 'header_image' )->priority = 28;
		$wp_customize->get_section( 'header_image' )->title = esc_html__( 'Frontpage - Header Image', 'nevark' );
	}

	// Backgrounds
	if ( current_theme_supports( 'custom-background' ) ) {
		$wp_customize->get_control( 'background_color' )->label =  esc_html__( 'Site Background Color', 'nevark' );
		$wp_customize->get_section( 'background_image' )->priority = 23;
		$wp_customize->get_section( 'background_image' )->title = esc_html__( 'Site Background Image', 'nevark' );
	}

	// $wp_customize->get_section( 'title_tagline' )->panel = 'general';
	// $wp_customize->get_section( 'title_tagline' )->priority = 1;
	// $wp_customize->get_section( 'colors' )->panel = 'styling';
	// 	$wp_customize->get_panel( 'nav_menus' )->priority = 999;
	// This will set the priority, however will give a 'Creating Default Object from Empty Value' error first.
	// $wp_customize->get_panel( 'widgets' )->priority = 999;

}
add_action( 'customize_register', 'nevark_modify_default_customizer_options', 100 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function nevark_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'nevark_customize_register' );

/**
 * Add style tag to support dynamic css via postMessage script in customizer preview
 *
 * @since 2.7
 * @access public
 */
function nevark_customize_dynamic_cssrules() {
	// Add in Customizer Only
	if ( is_customize_preview() ) {
		$handle = apply_filters( 'hoot_style_builder_inline_style_handle', 'hoot-style' );
		$settings = apply_filters( 'hoot_customize_dynamic_selectors', array() ); // Array of settings available for preview script to create dynamic css style tags
		$hootpload = ( function_exists( 'hoot_lib_premium_core' ) ) ? 1 : '';
		wp_localize_script( 'hoot-customize-preview', 'hootInlineStyles', array( $handle, $settings, $hootpload ) );
	}
}
add_action( 'wp_enqueue_scripts', 'nevark_customize_dynamic_cssrules', 999 );

/**
 * Callback Functions for customizer settings
 */

function nevark_callback_logo_size( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'mixed' ) ? true : false;
}
function nevark_callback_site_title_icon( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' ) ? true : false;
}
function nevark_callback_logo_image( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'image' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function nevark_callback_logo_image_width( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function nevark_callback_logo_custom( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'custom' || $selector == 'mixedcustom' ) ? true : false;
}
function nevark_callback_show_tagline( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}