<?php
/**
 * Defines customizer options
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 *
 * @package    Unos
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
if ( !function_exists( 'unos_default_style' ) ) :
function unos_default_style( $key = false ){

	// Do not use static cache variable as any reference to 'unos_default_style()'
	// (example: get default value during declaring add_theme_support for WP custom background which
	// is also loaded at 'after_setup_theme' hook with 10 priority) will prevent further applying
	// of filter hook (by child-theme/plugin/premium). Ideally, this function should be called only
	// after 'after_setup_theme' hook with 11 priority
	$defaults = apply_filters( 'unos_default_style', array(
		'accent_color'         => '#000000',
		'accent_font'          => '#ffffff',
		'module_bg_default'    => '#f5f5f5',
		'box_background'       => '#ffffff',
		'site_background'      => '#ffffff', // Used by WP custom-background
		'widgetmargin'         => 45,
		'logo_fontface'        => 'fontlo',
		'headings_fontface'    => 'fontlo',
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
if ( !function_exists( 'unos_customizer_options' ) ) :
function unos_customizer_options() {

	// Stores all the settings to be added
	$settings = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Theme default colors and fonts
	extract( unos_default_style() );

	// Directory path for radioimage buttons
	$imagepath =  hoot_data()->incuri . 'admin/images/';

	// Logo Sizes (different range than standard typography range)
	$logosizes = array();
	$logosizerange = range( 14, 110 );
	foreach ( $logosizerange as $isr )
		$logosizes[ $isr . 'px' ] = $isr . 'px';
	$logosizes = apply_filters( 'unos_options_logosizes', $logosizes);

	// Logo Font Options for Lite version
	$logofont = apply_filters( 'unos_options_logofont', array(
					'heading'  => esc_html__( "Logo Font (set in 'Typography' section)", 'unos' ),
					'heading2' => esc_html__( "Heading Font (set in 'Typography' section)", 'unos' ),
					'standard' => esc_html__( "Standard Body Font", 'unos' ),
					) );

	/*** Add Options (Panels, Sections, Settings) ***/

	/** Section **/

	$section = 'links';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Demo Install / Support', 'unos' ),
		'priority'    => '2',
	);

	$lcontent = array();
	$lcontent['demo'] = '<a class="hoot-cust-link" href="' .
				 'https://demo.wphoot.com/unos/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-eye"></i> ' .
				 esc_html__( "Demo", 'unos') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Demo the theme features and options with sample content.", 'unos') .
				 '</span></a>';
	$ocdilink = ( function_exists( 'hoot_lib_premium_core' ) ) ? ( ( class_exists( 'OCDI_Plugin' ) ) ? admin_url( 'themes.php?page=pt-one-click-demo-import' ) : 'https://wphoot.com/support/unos/#docs-section-demo-content' ) : 'https://wphoot.com/support/unos/#docs-section-demo-content-free';
	$lcontent['install'] = '<a class="hoot-cust-link" href="' .
				 esc_url( $ocdilink ) .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-upload"></i> ' .
				 esc_html__( "1 Click Installation", 'unos') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Install demo content to make your site look exactly like the Demo Site. Use it as a starting point instead of starting from scratch.", 'unos') .
				 '</span></a>';
	$lcontent['support'] = '<a class="hoot-cust-link" href="' .
				 'https://wphoot.com/support/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="far fa-life-ring"></i> ' .
				 esc_html__( "Documentation / Support", 'unos') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Get theme related support for both free and premium users.", 'unos') .
				 '</span></a>';
	$lcontent['rateus'] = '<a class="hoot-cust-link" href="' .
				 'https://wordpress.org/support/theme/unos/reviews/#new-post' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-star"></i> ' .
				 esc_html__( "Rate Us", 'unos') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 /* translators: five stars */
				 sprintf( esc_html__( 'If you are happy with the theme, please give us a %1$s rating on wordpress.org. Thanks in advance!', 'unos'), '<span style="color:#0073aa;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ) .
				 '</span></a>';

	$settings['linksection'] = array(
		// 'label'       => esc_html__( 'Misc Links', 'unos' ),
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '8', // Non static options must have a priority
		'content'     => implode( ' ', apply_filters( 'unos_customizer_option_linksection', $lcontent ) ),
	);

	/** Section **/

	$section = 'title_tagline';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Setup &amp; Layout', 'unos' ),
		'priority'    => '5',
	);

	$settings['site_layout'] = array(
		'label'       => esc_html__( 'Site Layout - Boxed vs Stretched', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'boxed'   => esc_html__( 'Boxed layout', 'unos' ),
			'stretch' => esc_html__( 'Stretched layout (full width)', 'unos' ),
		),
		'default'     => 'stretch',
		'priority'    => '10',
		'transport' => 'postMessage',
	);

	$settings['load_minified'] = array(
		'label'       => esc_html__( 'Load Minified Styles and Scripts (when available)', 'unos' ),
		'sublabel'    => esc_html__( 'Checking this option reduces data size, hence increasing page load speed.', 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
		'priority'    => '20',
	);

	$settings['sidebar'] = array(
		'label'       => esc_html__( 'Sidebar Layout (Site-wide)', 'unos' ),
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
		'description' => esc_html__( 'Set the default sidebar width and position for your site.', 'unos' ),
		'priority'    => '30',
	);

	$settings['sidebar_fp'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Front Page)', 'unos' ),
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
		'description' => sprintf( esc_html__( 'This is sidebar for the Frontpage Content Module in %1$sFrontpage Modules Settings%2$s', 'unos' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=frontpage_content_desc' ) ) . '" rel="focuslink" data-focustype="control" data-href="frontpage_content_desc">', '</a>' ),
		'priority'    => '35',
	);

	$settings['sidebar_archives'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Blog/Archives)', 'unos' ),
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
		'priority'    => '35',
	);

	$settings['sidebar_pages'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for Pages)', 'unos' ),
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
		'priority'    => '40',
	);

	$settings['sidebar_posts'] = array(
		'label'       => esc_html__( 'Sidebar Layout (for single Posts)', 'unos' ),
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
		'priority'    => '50',
	);

	if ( current_theme_supports( 'woocommerce' ) ) :

		$settings['sidebar_wooshop'] = array(
			'label'       => esc_html__( 'Sidebar Layout (Woocommerce Shop/Archives)', 'unos' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '53', // Non static options must have a priority
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
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce Shop and Archives pages like product categories etc.', 'unos' ),
		);

		$settings['sidebar_wooproduct'] = array(
			'label'       => esc_html__( 'Sidebar Layout (Woocommerce Single Product Page)', 'unos' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '53', // Non static options must have a priority
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
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce product page', 'unos' ),
		);

	endif;

	$settings['disable_sticky_sidebar'] = array(
		'label'       => esc_html__( 'Disable Sticky Sidebar', 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'description' => esc_html__( 'Check this if you do not want to display a fixed Sidebar the user scrolls down the page.', 'unos' ),
		'priority'    => '60',
	);

	$settings['widgetmargin'] = array(
		'label'       => esc_html__( 'Widget Margin', 'unos' ),
		'section'     => $section,
		'type'        => 'text',
		'default'     => $widgetmargin,
		'description' => esc_html__( '(in pixels) Margin space above and below widgets. Leave empty if you dont want to change the default.', 'unos' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: 45', 'unos' ),
		),
		'priority'    => '70',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'header';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Header', 'unos' ),
		'priority'    => '10',
	);

	$settings['menu_location'] = array(
		'label'       => esc_html__( 'Menu Location', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'        => esc_html__( 'Above Logo', 'unos' ),
			'side'       => esc_html__( 'Header Side (Right of Logo)', 'unos' ),
			'bottom'     => esc_html__( 'Below Logo', 'unos' ),
			'none'       => esc_html__( 'Do not display menu', 'unos' ),
		),
		'default'     => 'side',
		'priority'    => '80',
	);

	$settings['logo_side'] = array(
		'label'       => esc_html__( 'Header Side (right of logo)', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'search'      => esc_html__( 'Display Search', 'unos' ),
			'widget-area' => esc_html__( "'Header Side' widget area", 'unos' ),
			'none'        => esc_html__( 'None (Logo will get centre aligned)', 'unos' ),
		),
		'default'     => 'widget-area',
		'priority'    => '90',
		'active_callback' => 'unos_callback_logo_side', /*** Use JS API (in customize.js) for conditional controls using 'menu_location' setting in their active_callback - for quicker response ***/
		'selective_refresh' => array( 'logo_side_partial', array(
			'selector'            => '#header-aside',
			'settings'            => array( 'logo_side' ),
			'render_callback'     => 'unos_header_aside',
			'container_inclusive' => true,
			) ),
	);

	$settings['fullwidth_menu_align'] = array(
		'label'       => esc_html__( 'Menu Area (alignment)', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'left'      => esc_html__( 'Left', 'unos' ),
			'right'     => esc_html__( 'Right', 'unos' ),
			'center'    => esc_html__( 'Center', 'unos' ),
		),
		'default'     => 'center',
		'priority'    => '100',
		'active_callback' => 'unos_callback_logo_side', /*** Use JS API (in customize.js) for conditional controls using 'menu_location' setting in their active_callback - for quicker response ***/
		'transport' => 'postMessage',
	);

	$settings['disable_table_menu'] = array(
		'label'       => esc_html__( 'Disable Table Menu', 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( '%1$s%2$sDisable Table Menu if you have a lot of Top Level menu items, %3$sand dont have menu item descriptions.%4$s', 'unos' ), "<img src='{$imagepath}menu-table.png'>", '<br />', '<strong>', '</strong>' ),
		'priority'    => '110',
		'transport' => 'postMessage',
	);

	$settings['mobile_menu'] = array(
		'label'       => esc_html__( 'Mobile Menu', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'inline' => esc_html__( 'Inline - Menu Slide Downs to open', 'unos' ),
			'fixed'  => esc_html__( 'Fixed - Menu opens on the left', 'unos' ),
		),
		'default'     => 'fixed',
		'priority'    => '120',
		'transport' => 'postMessage',
	);

	$settings['mobile_submenu_click'] = array(
		'label'       => esc_html__( "[Mobile Menu] Submenu opens on 'Click'", 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
		'description' => esc_html__( "Uncheck this option to make all Submenus appear in 'Open' state. By default, submenus open on clicking (i.e. single tap on mobile).", 'unos' ),
		'priority'    => '130',
		'transport' => 'postMessage',
	);

	$settings['below_header_grid'] = array(
		'label'       => esc_html__( "'Below Header' widget area layout", 'unos' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'boxed'   => $imagepath . 'fp-widgetarea-boxed.png',
			'stretch' => $imagepath . 'fp-widgetarea-stretch.png',
		),
		'default'     => 'boxed',
		'priority'    => '133',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'logo';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Logo', 'unos' ),
		'priority'    => '15',
	);

	$settings['logo_background_type'] = array(
		'label'       => esc_html__( 'Logo Background', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'priority'    => '135', // Non static options must have a priority
		'choices'     => array(
			'transparent'   => esc_html__( 'None', 'unos' ),
			'accent'        => esc_html__( 'Accent Background', 'unos' ),
			'invert-accent' => esc_html__( 'Invert Accent Background', 'unos' ), // Implemented for possible child themes;
		),
		'default'     => 'transparent',
		'transport' => 'postMessage',
	); // Overridden in premium
	if ( !apply_filters( 'logo_background_type_invert_accent', false ) ) unset( $settings['logo_background_type']['choices']['invert-accent'] );

	$settings['logo_border'] = array(
		'label'       => esc_html__( 'Logo Border', 'unos' ),
		'sublabel'    => esc_html__( 'Display a border around logo.', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'default'     => 'none',
		'priority'    => '135',
		'choices'     => array(
			'none'        => esc_html__( 'None', 'unos' ),
			'border'      => esc_html__( 'Border (With padding)', 'unos' ),
			'bordernopad' => esc_html__( 'Border (No padding)', 'unos' ),
		),
		'transport' => 'postMessage',
	);

	$settings['logo'] = array(
		'label'       => esc_html__( 'Site Logo', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'text'        => esc_html__( 'Default Text (Site Title)', 'unos' ),
			'custom'      => esc_html__( 'Custom Text', 'unos' ),
			'image'       => esc_html__( 'Image Logo', 'unos' ),
			'mixed'       => esc_html__( 'Image &amp; Default Text (Site Title)', 'unos' ),
			'mixedcustom' => esc_html__( 'Image &amp; Custom Text', 'unos' ),
		),
		'default'     => 'text',
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'description' => sprintf( esc_html__( 'Use %1$sSite Title%2$s as default text logo', 'unos' ), '<a href="' . esc_url( admin_url('options-general.php') ) . '" target="_blank">', '</a>' ),
		'priority'    => '140',

		/*** Use JS API (in customize.js) for conditional controls using 'logo' setting in their active_callback ***/
		'selective_refresh' => array( 'logo_partial', array(
			'selector'            => '#branding',
			'settings'            => array( 'logo', 'logo_custom', 'custom_logo' ),	// Do not add 'logo_size' to 'settings' array
																					// since it is removed in premium, and hence this
																					// selective_refresh wont work
			'primary_setting'     => 'logo', // Redundant as 'logo' is first ID in settings array
			'render_callback'     => 'unos_branding',
			'container_inclusive' => true,
			) ),

	);

	$settings['logo_size'] = array(
		'label'       => esc_html__( 'Logo Text Size', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '145', // Non static options must have a priority
		'choices'     => array(
			'tiny'   => esc_html__( 'Tiny', 'unos'),
			'small'  => esc_html__( 'Small', 'unos'),
			'medium' => esc_html__( 'Medium', 'unos'),
			'large'  => esc_html__( 'Large', 'unos'),
			'huge'   => esc_html__( 'Huge', 'unos'),
		),
		'default'     => 'small',
		'active_callback' => 'unos_callback_logo_size',
		'transport' => 'postMessage',
	); // Removed in premium

	$settings['site_title_icon'] = array(
		'label'           => esc_html__( 'Site Title Icon (Optional)', 'unos' ),
		'section'         => $section,
		'type'            => 'icon',
		// 'default'         => 'fa-anchor fas',
		'description'     => esc_html__( 'Leave empty to hide icon.', 'unos' ),
		'priority'    => '150',
		'active_callback' => 'unos_callback_site_title_icon',
		'transport' => 'postMessage',
	);

	$settings['site_title_icon_size'] = array(
		'label'           => esc_html__( 'Site Title Icon Size', 'unos' ),
		'section'         => $section,
		'type'            => 'select',
		'choices'         => $logosizes,
		'default'         => '50px',
		'priority'    => '160',
		'active_callback' => 'unos_callback_site_title_icon',
		'transport' => 'postMessage',
	);

	$settings['logo_image_width'] = array(
		'label'           => esc_html__( 'Maximum Logo Width', 'unos' ),
		'section'         => $section,
		'type'            => 'text',
		'priority'        => '166', // Keep it with logo image ( 'custom_logo' )->priority logo
		'default'         => 200,
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description'     => sprintf( esc_html__( '(in pixels)%1$sThe logo width may be automatically adjusted by the browser depending on title length and space available.', 'unos' ), '<hr>' ),
		'input_attrs'     => array(
			'placeholder' => esc_html__( '(in pixels)', 'unos' ),
		),
		'active_callback' => 'unos_callback_logo_image_width',
		'transport' => 'postMessage',
	);

	$logo_custom_line_options = array(
		'text' => array(
			'label'       => esc_html__( 'Line Text', 'unos' ),
			'type'        => 'text',
		),
		'size' => array(
			'label'       => esc_html__( 'Line Size', 'unos' ),
			'type'        => 'select',
			'choices'     => $logosizes,
			'default'     => '24px',
		),
		'font' => array(
			'label'       => esc_html__( 'Line Font', 'unos' ),
			'type'        => 'select',
			'choices'     => $logofont,
			'default'     => 'heading',
		),
	);

	$settings['logo_custom'] = array(
		'label'           => esc_html__( 'Custom Logo Text', 'unos' ),
		'section'         => $section,
		'type'            => 'sortlist',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description'     => sprintf( esc_html__( 'Use &lt;b&gt; and &lt;em&gt; tags in "Line Text" fields below to emphasize different words. Example:%1$s%2$s&lt;b&gt;Hoot&lt;/b&gt; &lt;em&gt;Unos&lt;/em&gt;%3$s', 'unos' ), '<br />', '<code>', '</code>' ),
		'choices'         => array(
			'line1' => esc_html__( 'Line 1', 'unos' ),
			'line2' => esc_html__( 'Line 2', 'unos' ),
			'line3' => esc_html__( 'Line 3', 'unos' ),
			'line4' => esc_html__( 'Line 4', 'unos' ),
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
			// 'open-state' => 'line1',
		),
		'priority'    => '170',
		'active_callback' => 'unos_callback_logo_custom',
		'transport' => 'postMessage', // to work with 'selective_refresh' added via 'logo'
	);

	$settings['show_tagline'] = array(
		'label'           => esc_html__( 'Show Tagline', 'unos' ),
		'sublabel'        => esc_html__( 'Display site description as tagline below logo.', 'unos' ),
		'section'         => $section,
		'type'            => 'checkbox',
		'default'         => 1,
		'priority'    => '180',
		// 'active_callback' => 'unos_callback_show_tagline',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'colors';

	// Redundant as 'colors' section is added by WP. But we still add it for brevity
	$sections[ $section ] = array(
		'title'       => esc_html__( 'Colors / Backgrounds', 'unos' ),
		// 'description' => __( 'The premium version comes with color and background options for different sections of your site like header, menu dropdown, content area, logo background, footer etc.', 'unos' ),
		'priority'    => '20',
	);

	$settings['box_background_color'] = array(
		'label'       => esc_html__( 'Site Content Background', 'unos' ),
		'section'     => $section,
		'type'        => 'color',
		'priority'    => '185', // Non static options must have a priority
		'default'     => $box_background,
		'transport' => 'postMessage',
	); // Overridden in premium

	$settings['accent_color'] = array(
		'label'       => esc_html__( 'Accent Color', 'unos' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_color,
		'priority'    => '190',
		'transport' => 'postMessage',
	);

	$settings['accent_font'] = array(
		'label'       => esc_html__( 'Font Color on Accent Color', 'unos' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_font,
		'priority'    => '200',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'typography';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Typography', 'unos' ),
		// 'description' => esc_html__( 'The premium version offers complete typography control (color, style, size) for various headings, header, menu, footer, widgets, content sections etc (over 600 Google Fonts to chose from)', 'unos' ),
		'priority'    => '25',
	);

	$settings['logo_fontface'] = array(
		'label'       => esc_html__( 'Logo Font (Free Version)', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'fontos' => esc_html__( 'Standard Font (Open Sans)', 'unos'),
			'fontcf' => esc_html__( 'Alternate Font (Comfortaa)', 'unos'),
			'fontow' => esc_html__( 'Display Font (Oswald)', 'unos'),
			'fontlo' => esc_html__( 'Heading Font 1 (Lora)', 'unos'),
			'fontsl' => esc_html__( 'Heading Font 2 (Slabo)', 'unos'),
			'fontgr' => esc_html__( 'Heading Font 3 (Georgia)', 'unos'),
		),
		'default'     => $logo_fontface,
	); // Removed in premium

	$settings['logo_fontface_style'] = array(
		'label'       => esc_html__( 'Logo Font Style', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard', 'unos'),
			'uppercase' => esc_html__( 'Uppercase', 'unos'),
		),
		'default'     => 'uppercase',
		'transport' => 'postMessage',
	); // Removed in premium

	$settings['headings_fontface'] = array(
		'label'       => esc_html__( 'Headings Font (Free Version)', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'fontos' => esc_html__( 'Standard Font (Open Sans)', 'unos'),
			'fontcf' => esc_html__( 'Alternate Font (Comfortaa)', 'unos'),
			'fontow' => esc_html__( 'Display Font (Oswald)', 'unos'),
			'fontlo' => esc_html__( 'Heading Font 1 (Lora)', 'unos'),
			'fontsl' => esc_html__( 'Heading Font 2 (Slabo)', 'unos'),
			'fontgr' => esc_html__( 'Heading Font 3 (Georgia)', 'unos'),
		),
		'default'     => $headings_fontface,
	); // Removed in premium

	$settings['headings_fontface_style'] = array(
		'label'       => esc_html__( 'Heading Font Style', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'standard'  => esc_html__( 'Standard', 'unos'),
			'uppercase' => esc_html__( 'Uppercase', 'unos'),
		),
		'default'     => 'standard',
		'transport' => 'postMessage',
	); // Removed in premium

	/** Section **/

	$section = 'frontpage';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Frontpage - Modules', 'unos' ),
		'priority'    => '30',
	);

	$widget_area_options = array(
		'columns' => array(
			'label'   => esc_html__( 'Columns', 'unos' ),
			'type'    => 'select',
			'choices' => array(
				'100'         => esc_html__( 'One Column [100]', 'unos' ),
				'50-50'       => esc_html__( 'Two Columns [50 50]', 'unos' ),
				'33-66'       => esc_html__( 'Two Columns [33 66]', 'unos' ),
				'66-33'       => esc_html__( 'Two Columns [66 33]', 'unos' ),
				'25-75'       => esc_html__( 'Two Columns [25 75]', 'unos' ),
				'75-25'       => esc_html__( 'Two Columns [75 25]', 'unos' ),
				'33-33-33'    => esc_html__( 'Three Columns [33 33 33]', 'unos' ),
				'25-25-50'    => esc_html__( 'Three Columns [25 25 50]', 'unos' ),
				'25-50-25'    => esc_html__( 'Three Columns [25 50 25]', 'unos' ),
				'50-25-25'    => esc_html__( 'Three Columns [50 25 25]', 'unos' ),
				'25-25-25-25' => esc_html__( 'Four Columns [25 25 25 25]', 'unos' ),
			),
		),
		'grid' => array(
			'label'    => esc_html__( 'Layout', 'unos' ),
			'sublabel' => esc_html__( 'The fully stretched grid layout is especially useful for displaying full width slider widgets.', 'unos' ),
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
			'content'     => '<div class="button">' . esc_html__( 'Module Background', 'unos' ) . '</div>',
		),
	);

	$settings['frontpage_sections'] = array(
		'label'       => esc_html__( 'Frontpage Widget Areas', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'sublabel'    => sprintf( esc_html__( '%1$s%3$sSort different sections of the Frontpage in the order you want them to appear.%4$s%3$sYou can add content to widget areas from the %5$sWidgets Management screen%6$s.%4$s%3$sYou can disable areas by clicking the "eye" icon. (This will hide them on the Widgets screen as well)%4$s%2$s', 'unos' ), '<ul>', '</ul>', '<li>', '</li>', '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'area_a'      => esc_html__( 'Widget Area A', 'unos' ),
			'area_b'      => esc_html__( 'Widget Area B', 'unos' ),
			'area_c'      => esc_html__( 'Widget Area C', 'unos' ),
			'area_d'      => esc_html__( 'Widget Area D', 'unos' ),
			'content'     => esc_html__( 'Frontpage Content', 'unos' ),
			'area_e'      => esc_html__( 'Widget Area E', 'unos' ),
			'area_f'      => esc_html__( 'Widget Area F', 'unos' ),
			'area_g'      => esc_html__( 'Widget Area G', 'unos' ),
			'area_h'      => esc_html__( 'Widget Area H', 'unos' ),
			'area_i'      => esc_html__( 'Widget Area I', 'unos' ),
			'area_j'      => esc_html__( 'Widget Area J', 'unos' ),
			'area_k'      => esc_html__( 'Widget Area K', 'unos' ),
			'area_l'      => esc_html__( 'Widget Area L', 'unos' ),
		),
		'default'     => array(
			// 'content' => array( 'sortitem_hide' => 1, ),
			'area_b'  => array( 'columns' => '50-50' ),
			'area_f'  => array( 'sortitem_hide' => 1, ),
			'area_g'  => array( 'sortitem_hide' => 1, ),
			'area_h'  => array( 'sortitem_hide' => 1, ),
			'area_i'  => array( 'sortitem_hide' => 1, ),
			'area_j'  => array( 'sortitem_hide' => 1, ),
			'area_k'  => array( 'sortitem_hide' => 1, ),
			'area_l'  => array( 'sortitem_hide' => 1, ),
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
			'area_k'      => $widget_area_options,
			'area_l'      => $widget_area_options,
			'content'     => array(
							'title' => array(
								'label'       => esc_html__( 'Title (optional)', 'unos' ),
								'type'        => 'text',
							),
							'modulebg' => array(
								'label'       => '',
								'type'        => 'content',
								'content'     => '<div class="button">' . esc_html__( 'Module Background', 'unos' ) . '</div>',
							), ),
		),
		'attributes'  => array(
			'hideable'      => true,
			'sortable'      => true,
			'open-state'    => 'area_a',
		),
		// /* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		// 'description' => sprintf( esc_html__( 'You must first save the changes you make here and refresh this screen for widget areas to appear in the Widgets panel (in customizer). Once you save the settings, you can add content to these widget areas using the %1$sWidgets Management screen%2$s.', 'unos' ), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
		'priority'    => '210',
	);

	$settings['frontpage_content_desc'] = array(
		'label'       => esc_html__( "Frontpage Content", 'unos' ),
		'section'     => $section,
		'type'        => 'content',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'The "Frontpage Content" module in above list will show %1$s%3$sthe %5$s"Blog"%6$s if you have %5$sYour Latest Posts%6$s selectd in %7$sReading Settings%8$s %4$s%3$sthe %5$s"Page Content"%6$s of the page set as Front page if you have %5$sA static page%6$s selected in %7$sReading Settings%8$s %4$s%2$s',
				'unos' ), "<ul style='list-style:disc;margin:1em 0 0 2em;'>", '</ul>', '<li>', '</li>', '<strong>', '</strong>',
									 '<a href="' . esc_url( admin_url('options-reading.php') ) . '" target="_blank">', '</a>' ),
		'priority'    => '220',
	);

	$frontpagemodule_bg = apply_filters( 'unos_frontpage_widgetarea_sectionbg_index', array(
		'area_a'      => esc_html__( 'Widget Area A', 'unos' ),
		'area_b'      => esc_html__( 'Widget Area B', 'unos' ),
		'area_c'      => esc_html__( 'Widget Area C', 'unos' ),
		'area_d'      => esc_html__( 'Widget Area D', 'unos' ),
		'area_e'      => esc_html__( 'Widget Area E', 'unos' ),
		'area_f'      => esc_html__( 'Widget Area F', 'unos' ),
		'area_g'      => esc_html__( 'Widget Area G', 'unos' ),
		'area_h'      => esc_html__( 'Widget Area H', 'unos' ),
		'area_i'      => esc_html__( 'Widget Area I', 'unos' ),
		'area_j'      => esc_html__( 'Widget Area J', 'unos' ),
		'area_k'      => esc_html__( 'Widget Area K', 'unos' ),
		'area_l'      => esc_html__( 'Widget Area L', 'unos' ),
		'content'     => esc_html__( 'Frontpage Content', 'unos' ),
		) );

	foreach ( $frontpagemodule_bg as $fpgmodid => $fpgmodname ) {

		$settings["frontpage_sectionbg_{$fpgmodid}"] = array(
			'label'       => '',
			'section'     => $section,
			'type'        => 'group',
			'startwrap'   => 'fp-section-bg-button',
			'button'      => esc_html__( 'Module Background', 'unos' ),
			'options'     => array(
				'description' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . $fpgmodname . '</span>',
				),
				'type' => array(
					'label'   => esc_html__( 'Background Type', 'unos' ),
					'type'    => 'radio',
					'choices' => array(
						'none'        => esc_html__( 'None', 'unos' ),
						// 'highlight'   => esc_html__( 'Highlight', 'unos' ),
						'color'       => esc_html__( 'Color', 'unos' ),
						'image'       => esc_html__( 'Image', 'unos' ),
					),
					'default' => 'none',
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' :
					// 											( ( $fpgmodid == 'area_d' ) ? 'highlight' : 'none' )
					// 			 ),
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' : 'none' ),
					'transport' => 'postMessage',
				),
				'color' => array(
					'label'       => esc_html__( "Background Color (Select 'Color' above)", 'unos' ),
					'type'        => 'color',
					'default'     => $module_bg_default,
					'transport' => 'postMessage',
				),
				'image' => array(
					'label'       => esc_html__( "Background Image (Select 'Image' above)", 'unos' ),
					'type'        => 'image',
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? hoot_data()->template_uri . 'images/modulebg.jpg' : '' ),
					'transport' => 'postMessage',
				),
				'parallax' => array(
					'label'   => esc_html__( 'Apply Parallax Effect to Background Image', 'unos' ),
					'type'    => 'checkbox',
					// 'default' => 1,
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 1 : 0 ),
				),
				'font' => array(
					'label'   => esc_html__( 'Font Color', 'unos' ),
					'type'    => 'radio',
					'choices' => array(
						'theme'       => esc_html__( 'Default Theme Color', 'unos' ),
						'color'       => esc_html__( 'Custom Font Color', 'unos' ),
						'force'       => esc_html__( 'Force Custom Font Color', 'unos' ),
					),
					'default' => 'theme',
					'transport' => 'postMessage',
				),
				'fontcolor' => array(
					'label'       => esc_html__( "Custom Font Color (select 'Custom Font Color' above)", 'unos' ),
					'type'        => 'color',
					'default'     => '#aaaaaa',
					'transport' => 'postMessage',
				),
			),
			'priority'    => '230',
		);

	} // end for

	/** Section **/

	$section = 'archives';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Archives (Blog, Cats, Tags)', 'unos' ),
		'priority'    => '35',
	);

	$settings['archive_type'] = array(
		'label'       => esc_html__( 'Archive (Blog) Layout', 'unos' ),
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
		'priority'    => '240',
	);

	$settings['archive_post_content'] = array(
		'label'       => esc_html__( 'Post Items Content', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'none' => esc_html__( 'None', 'unos' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'unos' ),
			'full-content' => esc_html__( 'Full Post Content', 'unos' ),
		),
		'default'     => 'excerpt',
		'description' => esc_html__( 'Content to display for each post in the list', 'unos' ),
		'priority'    => '250',
	);

	$settings['archive_post_meta'] = array(
		'label'       => esc_html__( 'Meta Information for Post List Items', 'unos' ),
		'sublabel'    => esc_html__( 'Check which meta information to display for each post item in the archive list.', 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'unos' ),
			'date'     => esc_html__( 'Date', 'unos' ),
			'cats'     => esc_html__( 'Categories', 'unos' ),
			'tags'     => esc_html__( 'Tags', 'unos' ),
			'comments' => esc_html__( 'No. of comments', 'unos' ),
		),
		'default'     => 'author, date, cats',
		'selective_refresh' => array( 'archive_post_meta_partial', array(
			'selector'            => '.blog .entry-byline, .home .entry-byline, .plural .entry-byline',
			'settings'            => array( 'archive_post_meta' ),
			'render_callback'     => 'unos_callback_archive_post_meta',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
		'priority'    => '260',
	);

	$settings['excerpt_length'] = array(
		'label'       => esc_html__( 'Excerpt Length', 'unos' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( 'Number of words in excerpt. Default is 50. Leave empty if you dont want to change it.', 'unos' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: 50', 'unos' ),
		),
		'priority'    => '270',
	);

	$settings['read_more'] = array(
		'label'       => esc_html__( "'Continue Reading' Text", 'unos' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( "Replace the default 'Continue Reading' text. Leave empty if you dont want to change it.", 'unos' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: Continue Reading', 'unos' ),
		),
		'default'     => esc_html__( 'Continue Reading', 'unos' ),
		// 'transport' => 'postMessage', // Interferes with defaults of hootkit widgets, custom user input readmore text in hootkit widgets
		'priority'    => '280',
	);

	/** Section **/

	$section = 'singular';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Single (Posts, Pages)', 'unos' ),
		'priority'    => '40',
	);

	$settings['page_header_full'] = array(
		'label'       => esc_html__( 'Stretch Page Title Area to Full Width', 'unos' ),
		'sublabel'    => '<img src="' . $imagepath . 'page-header.png">',
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'default'    => esc_html__( 'Default (Archives, Blog, Woocommerce etc.)', 'unos' ),
			'posts'      => esc_html__( 'For All Posts', 'unos' ),
			'pages'      => esc_html__( 'For All Pages', 'unos' ),
			'no-sidebar' => esc_html__( 'Always override for full width pages (any page which has no sidebar)', 'unos' ),
		),
		'default'     => 'default, pages, no-sidebar',
		'description' => esc_html__( 'This is the Page Header area containing Page/Post Title and Meta details like author, categories etc.', 'unos' ),
		'priority'    => '290',
	);

	$settings['post_featured_image'] = array(
		'label'       => esc_html__( 'Display Featured Image (Post)', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'none'                => esc_html__( 'Do not display', 'unos'),
			'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'unos'),
			'staticheader'        => esc_html__( 'Header Background (Cropped)', 'unos'),
			'header'              => esc_html__( 'Header Background (Parallax Effect)', 'unos'),
			'content'             => esc_html__( 'Beginning of content', 'unos'),
		),
		'default'     => 'content',
		'description' => esc_html__( 'Display featured image on a Post page.', 'unos' ),
		'priority'    => '300',
	);

	$settings['post_featured_image_page'] = array(
		'label'       => esc_html__( 'Display Featured Image (Page)', 'unos' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'none'                => esc_html__( 'Do not display', 'unos'),
			'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'unos'),
			'staticheader'        => esc_html__( 'Header Background (Cropped)', 'unos'),
			'header'              => esc_html__( 'Header Background (Parallax Effect)', 'unos'),
			'content'             => esc_html__( 'Beginning of content', 'unos'),
		),
		'default'     => 'header',
		'description' => esc_html__( "Display featured image on a 'Page' page.", 'unos' ),
		'priority'    => '310',
	);

	$settings['post_meta'] = array(
		'label'       => esc_html__( 'Meta Information on Posts', 'unos' ),
		'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Post' page", 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'unos' ),
			'date'     => esc_html__( 'Date', 'unos' ),
			'cats'     => esc_html__( 'Categories', 'unos' ),
			'tags'     => esc_html__( 'Tags', 'unos' ),
			'comments' => esc_html__( 'No. of comments', 'unos' )
		),
		'default'     => 'author, date, cats, tags, comments',
		'priority'    => '320',
		'selective_refresh' => array( 'post_meta_partial', array(
			'selector'            => '.singular-post .entry-byline',
			'settings'            => array( 'post_meta' ),
			'render_callback'     => 'unos_callback_post_meta',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
	);

	$settings['page_meta'] = array(
		'label'       => esc_html__( 'Meta Information on Page', 'unos' ),
		'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Page' page", 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'unos' ),
			'date'     => esc_html__( 'Date', 'unos' ),
			'comments' => esc_html__( 'No. of comments', 'unos' ),
		),
		'default'     => 'author, date, comments',
		'priority'    => '330',
		'selective_refresh' => array( 'page_meta_partial', array(
			'selector'            => '.singular-page .entry-byline',
			'settings'            => array( 'page_meta' ),
			'render_callback'     => 'unos_callback_page_meta',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
	);

	$settings['post_meta_location'] = array(
		'label'       => esc_html__( 'Meta Information location', 'unos' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'    => esc_html__( 'Top (below title)', 'unos' ),
			'bottom' => esc_html__( 'Bottom (after content)', 'unos' ),
		),
		'default'     => 'top',
		'priority'    => '340',
	);

	$settings['post_prev_next_links'] = array(
		'label'       => esc_html__( 'Previous/Next Posts', 'unos' ),
		'sublabel'    => esc_html__( 'Display links to Prev/Next Post links at the end of post content.', 'unos' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
		'priority'    => '350',
		'selective_refresh' => array( 'post_prev_next_links_partial', array(
			'selector'            => '#loop-nav-wrap',
			'settings'            => array( 'post_prev_next_links' ),
			'render_callback'     => 'unos_post_prev_next_links',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
	);

	$settings['contact-form'] = array(
		'label'       => esc_html__( 'Contact Form', 'unos' ),
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '355', // Non static options must have a priority
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'content'     => sprintf( esc_html__( 'This theme comes bundled with CSS required to style %1$sContact-Form-7%2$s forms. Simply install and activate the plugin to add Contact Forms to your pages.', 'unos' ), '<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">', '</a>' ), // @todo update link to docs
	);

	/** Section **/

	$section = 'footer';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Footer', 'unos' ),
		'priority'    => '45',
	);

	$settings['footer'] = array(
		'label'       => esc_html__( 'Footer Layout', 'unos' ),
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
		'description' => sprintf( esc_html__( 'You must first save the changes you make here and refresh this screen for footer columns to appear in the Widgets panel (in customizer).%3$s Once you save the settings here, you can add content to footer columns using the %1$sWidgets Management screen%2$s.', 'unos' ), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>', '<hr>' ),
		'priority'    => '360',
		'transport' => 'postMessage',
	);

	$settings['site_info'] = array(
		'label'       => esc_html__( 'Site Info Text (footer)', 'unos' ),
		'section'     => $section,
		'type'        => 'textarea',
		'default'     => esc_html__( '<!--default-->', 'unos'),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'Text shown in footer. Useful for showing copyright info etc.%3$sUse the %4$s&lt;!--default--&gt;%5$s tag to show the default Info Text.%3$sUse the %4$s&lt;!--year--&gt;%5$s tag to insert the current year.%3$sAlways use %1$sHTML codes%2$s for symbols. For example, the HTML for &copy; is %4$s&amp;copy;%5$s', 'unos' ), '<a href="http://ascii.cl/htmlcodes.htm" target="_blank">', '</a>', '<hr>', '<code>', '</code>' ),
		'priority'    => '370',
		'transport' => 'postMessage',
	);


	/*** Return Options Array ***/
	return apply_filters( 'unos_customizer_options', array(
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
if ( !function_exists( 'unos_add_customizer_options' ) ) :
function unos_add_customizer_options() {

	$hoot_customize = Hoot_Customize::get_instance();

	// Add Options
	$options = unos_customizer_options();
	$hoot_customize->add_options( array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) );

}
endif;
add_action( 'init', 'unos_add_customizer_options', 0 ); // cannot hook into 'after_setup_theme' as this hook is already being executed (this file is loaded at after_setup_theme @priority 10) (hooking into same hook from within while hook is being executed leads to undesirable effects as $GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
// Hence, we hook into 'init' @priority 0, so that settings array gets populated before 'widgets_init' action ( which itself is hooked to 'init' at priority 1 ) for creating widget areas ( settings array is needed for creating defaults when user value has not been stored )

/**
 * Enqueue custom scripts to customizer screen
 *
 * @since 1.0
 * @return void
 */
function unos_customizer_enqueue_scripts() {
	// Enqueue Styles
	$style_uri = ( function_exists( 'hoot_locate_style' ) ) ? hoot_locate_style( hoot_data()->incuri . 'admin/css/customize' ) : hoot_data()->incuri . 'admin/css/customize.css';
	wp_enqueue_style( 'unos-customize-styles', $style_uri, array(),  hoot_data()->hoot_version );
	// Enqueue Scripts
	$script_uri = ( function_exists( 'hoot_locate_script' ) ) ? hoot_locate_script( hoot_data()->incuri . 'admin/js/customize-controls' ) : hoot_data()->incuri . 'admin/js/customize-controls.js';
	wp_enqueue_script( 'unos-customize-controls', $script_uri, array( 'jquery', 'wp-color-picker', 'customize-controls', 'hoot-customize' ), hoot_data()->hoot_version, true );
}
// Load scripts at priority 12 so that Hoot Customizer Interface (11) / Custom Controls (10) have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'unos_customizer_enqueue_scripts', 12 );

/**
 * Modify default WordPress Settings Sections and Panels
 *
 * @since 1.0
 * @param object $wp_customize
 * @return void
 */
function unos_modify_default_customizer_options( $wp_customize ) {

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
	$wp_customize->get_control( 'custom_logo' )->priority = 165;
	$wp_customize->get_control( 'custom_logo' )->width = 300;
	$wp_customize->get_control( 'custom_logo' )->height = 180;
	// $wp_customize->get_control( 'custom_logo' )->type = 'image'; // Stored value becomes url instead of image ID (fns like the_custom_logo() dont work)
	$wp_customize->get_control( 'custom_logo' )->active_callback = 'unos_callback_logo_image';

	if ( function_exists( 'get_site_icon_url' ) )
		$wp_customize->get_control( 'site_icon' )->priority = 10;

	$wp_customize->get_section( 'static_front_page' )->priority = 3;
	if ( current_theme_supports( 'custom-header' ) ) {
		$wp_customize->get_section( 'header_image' )->priority = 28;
		$wp_customize->get_section( 'header_image' )->title = esc_html__( 'Frontpage - Header Image', 'unos' );
	}

	// Backgrounds
	if ( current_theme_supports( 'custom-background' ) ) {
		$wp_customize->get_control( 'background_color' )->label =  esc_html__( 'Site Background Color', 'unos' );
		$wp_customize->get_section( 'background_image' )->priority = 23;
		$wp_customize->get_section( 'background_image' )->title = esc_html__( 'Site Background Image', 'unos' );
	}

	// $wp_customize->get_section( 'title_tagline' )->panel = 'general';
	// $wp_customize->get_section( 'title_tagline' )->priority = 1;
	// $wp_customize->get_section( 'colors' )->panel = 'styling';
	// 	$wp_customize->get_panel( 'nav_menus' )->priority = 999;
	// This will set the priority, however will give a 'Creating Default Object from Empty Value' error first.
	// $wp_customize->get_panel( 'widgets' )->priority = 999;

}
add_action( 'customize_register', 'unos_modify_default_customizer_options', 100 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function unos_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport = 'postMessage';
}
add_action( 'customize_register', 'unos_customize_register' );

/**
 * Add style tag to support dynamic css via postMessage script in customizer preview
 *
 * @since 2.7
 * @access public
 */
function unos_customize_dynamic_cssrules() {
	// Add in Customizer Only
	if ( is_customize_preview() ) {
		$handle = apply_filters( 'hoot_style_builder_inline_style_handle', 'hoot-style' );
		$hootpload = ( function_exists( 'hoot_lib_premium_core' ) ) ? 1 : '';
		$settings = array();
		$settings['widgetmargin'] = array(
			'margin-top'	=> array( '.main-content-grid' . ',' . '.widget' . ',' . '.frontpage-area',
									  '.bottomborder-line:after' . ',' . '.bottomborder-shadow:after',
									  '.footer .widget', // brevity : replaced by newvalintsmall in js
									),
			'margin-bottom'	=> array( '.widget' . ',' . '.frontpage-area',
									  '.topborder-line:before' . ',' . '.topborder-shadow:before',
									  '.footer .widget', // brevity : replaced by newvalintsmall in js
									),
			'padding-top'	=> array( '.frontpage-area.module-bg-highlight, .frontpage-area.module-bg-color, .frontpage-area.module-bg-image' ),
			'padding-bottom'=> array( '.frontpage-area.module-bg-highlight, .frontpage-area.module-bg-color, .frontpage-area.module-bg-image' ),
			'media'			=> array(
				'@media only screen and (max-width: 969px)' => array(
					'margin-top'	=> array( '.sidebar' ),
					'margin-bottom'	=> array( '.frontpage-widgetarea > div.hgrid > [class*="hgrid-span-"]' ),
				),
			),
		);
		$settings['site_title_icon_size'] = array(
			'font-size'		=> array( '.site-logo-with-icon #site-title i' ),
		);
		$settings['logo_image_width'] = array(
			'max-width'		=> array( '.site-logo-mixed-image img' ),
		);
		$settings['box_background_color'] = array(
			'color'			=> array( '.invert-typo' ),
			'background'	=> array( '.enforce-typo',
									  '#main.main' . ',' . '.below-header',
									  '.js-search .searchform.expand .searchtext',
									  '.content-block-style3 .content-block-icon',
									),
		);
		if ( !$hootpload ) {
			array_push( $settings['box_background_color']['background'], '.menu-items ul' );
			$settings['box_background_color']['media'] = array(
				'@media only screen and (max-width: 969px)' => array(
					'background'	=> array( '.mobilemenu-fixed .menu-toggle, .mobilemenu-fixed .menu-items' ),
				),
			);
		} else {
			$settings['box_background_color']['border-bottom-color'] = array( '.current-tabhead' );
		}
		$settings['accent_color'] = array(
			'color'			=> array( '.invert-accent-typo',
									  'body.wordpress input[type="submit"]:hover, body.wordpress #submit:hover, body.wordpress .button:hover, body.wordpress input[type="submit"]:focus, body.wordpress #submit:focus, body.wordpress .button:focus',
									  '.header-aside-search.js-search .searchform i.fa-search',
									  '.site-title-line em',
									  '.more-link, .more-link a',
									  '.more-link:hover, .more-link:hover a', // brevity : replaced by newvaldark in js
									  '.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
									  '.topbanner-content mark',
									  '.slider-style2 .lSAction > a:hover',
									  '.widget .viewall a:hover',
									  '.cta-subtitle',
									  '.content-block-icon i',
									),
			'border-color'	=> array( 'body.wordpress input[type="submit"], body.wordpress #submit, body.wordpress .button',
									  '#site-logo.logo-border',
									  '.menu-tag',
									  '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
									  '.lSSlideOuter ul.lSPager.lSpg > li a',
									  '.slider-style2 .lSAction > a',
									  '.icon-style-circle',
									  '.icon-style-square',
									),
			'border-left-color'	=> array( '.widget_breadcrumb_navxt .breadcrumbs > .hoot-bcn-pretext:after' ),
			'background'	=> array( '.accent-typo',
									  'body.wordpress input[type="submit"], body.wordpress #submit, body.wordpress .button',
									  '.site-title-line mark',
									  '.menu-items li.current-menu-item, .menu-items li.current-menu-ancestor, .menu-items li:hover',
									  '#infinite-handle span',
									  '.lrm-form a.button, .lrm-form button, .lrm-form button[type=submit], .lrm-form #buddypress input[type=submit], .lrm-form input[type=submit]',
									  // '.widget_newsletterwidget input.tnp-submit[type=submit], .widget_newsletterwidgetminimal input.tnp-submit[type=submit]',
									  '.widget_breadcrumb_navxt .breadcrumbs > .hoot-bcn-pretext',
									  '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover' . ',' . '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
									  '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
									  '.lSSlideOuter ul.lSPager.lSpg > li:hover a, .lSSlideOuter ul.lSPager.lSpg > li.active a',
									  '.lightSlider .wrap-light-on-dark .hootkitslide-head, .lightSlider .wrap-dark-on-light .hootkitslide-head',
									  '.slider-style2 .lSAction > a',
									  '.social-icons-icon',
									),
		);
		if ( !$hootpload ) {
			array_push( $settings['accent_color']['color'], 'a', '.widget .view-all a:hover' ); // view-all a:hover => // Hootkit <= 1.1.0 support // @todo remove in future version
			array_push( $settings['accent_color']['background'], '#topbar', '#topbar.js-search .searchform.expand .searchtext' );
			array_push( $settings['accent_color']['color'], 'a:hover', '.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover' ); // brevity : replaced by newvaldark in js
		} else {
			array_push( $settings['accent_color']['color'], '.wordpress .button-widget.preset-accent:hover' );
			array_push( $settings['accent_color']['background'], '.wordpress .button-widget.preset-accent', '.notice-widget.preset-accent' );
			array_push( $settings['accent_color']['border-color'], '.wordpress .button-widget.preset-accent' );
		}
		$settings['accent_font'] = array(
			'color'			=> array( '.accent-typo',
									  'body.wordpress input[type="submit"], body.wordpress #submit, body.wordpress .button',
									  '.site-title-line mark',
									  '.menu-items li.current-menu-item > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
									  '#infinite-handle span',
									  '.lrm-form a.button, .lrm-form button, .lrm-form button[type=submit], .lrm-form #buddypress input[type=submit], .lrm-form input[type=submit]',
									  // '.widget_newsletterwidget input.tnp-submit[type=submit], .widget_newsletterwidgetminimal input.tnp-submit[type=submit]',
									  '.widget_breadcrumb_navxt .breadcrumbs > .hoot-bcn-pretext',
									  '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover a, .woocommerce div.product .woocommerce-tabs ul.tabs li:hover a:hover' . ',' . '.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
									  '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
									  '.lightSlider .wrap-light-on-dark .hootkitslide-head, .lightSlider .wrap-dark-on-light .hootkitslide-head',
									  '.slider-style2 .lSAction > a',
									  '.sidebar .view-all-top.view-all-withtitle a, .sub-footer .view-all-top.view-all-withtitle a, .footer .view-all-top.view-all-withtitle a, .sidebar .view-all-top.view-all-withtitle a:hover, .sub-footer .view-all-top.view-all-withtitle a:hover, .footer .view-all-top.view-all-withtitle a:hover', // Hootkit <= 1.1.0 support // @todo remove in future version
									  '#topbar .social-icons-icon, #page-wrapper .social-icons-icon',
									),
			'background'	=> array( '.invert-accent-typo',
									  'body.wordpress input[type="submit"]:hover, body.wordpress #submit:hover, body.wordpress .button:hover, body.wordpress input[type="submit"]:focus, body.wordpress #submit:focus, body.wordpress .button:focus',
									  '.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
									  '.slider-style2 .lSAction > a:hover',
									  '.widget .viewall a:hover',
									),
		);
		if ( apply_filters( 'unos_menutag_inverthover', true ) ) {
			$settings['accent_color']['color'][] =
			$settings['accent_font']['background'][] =
			$settings['accent_font']['border-color'][] =
			'#header .menu-items li.current-menu-item > a .menu-tag, #header .menu-items li.current-menu-ancestor > a .menu-tag, #header .menu-items li:hover > a .menu-tag';
		}
		if ( apply_filters( 'unos_sidebarwidgettitle_accenttypo', true ) ) {
			$settings['accent_font']['color'][] =
			$settings['accent_color']['background'][] =
			$settings['accent_color']['border-color'][] =
				'.sidebar .widget-title' . ',' . '.sub-footer .widget-title, .footer .widget-title';
			$settings['accent_font']['background'][] =
			$settings['accent_color']['color'][] =
				'.sidebar .widget:hover .widget-title' . ',' . '.sub-footer .widget:hover .widget-title, .footer .widget:hover .widget-title';
		}
		if ( !$hootpload ) {
			array_push( $settings['accent_font']['color'], '#topbar', '#topbar.js-search .searchform.expand .searchtext', '#topbar .js-search-placeholder' );
		} else {
			array_push( $settings['accent_font']['color'], '.wordpress .button-widget.preset-accent', '.notice-widget.preset-accent' );
			array_push( $settings['accent_font']['background'], '.wordpress .button-widget.preset-accent:hover' );
		}
		if ( !$hootpload ) {
			$settings['headings_fontface_style'] = array(
				'text-transform'=> array( 'h1, h2, h3, h4, h5, h6, .title, .titlefont',
									  // '.site-title-line.site-title-heading-font' // Done using jQuery directly
									),
			);
		}

		$settings = apply_filters( 'hoot_customize_dynamic_selectors', $settings );
		wp_localize_script( 'hoot-customize-preview', 'hootInlineStyles', array( $handle, $settings, $hootpload ) );
	}
}
add_action( 'wp_enqueue_scripts', 'unos_customize_dynamic_cssrules', 999 );

/**
 * Callback Functions for customizer settings
 */

function unos_callback_logo_side( $control ) {
	$selector = $control->manager->get_setting('menu_location')->value();
	return ( $selector == 'top' || $selector == 'bottom' || $selector == 'none' ) ? true : false;
}
function unos_callback_logo_size( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'mixed' ) ? true : false;
}
function unos_callback_site_title_icon( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' ) ? true : false;
}
function unos_callback_logo_image( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'image' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function unos_callback_logo_image_width( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function unos_callback_logo_custom( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'custom' || $selector == 'mixedcustom' ) ? true : false;
}
// function unos_callback_show_tagline( $control ) {
// 	$selector = $control->manager->get_setting('logo')->value();
// 	return ( $selector == 'text' || $selector == 'custom' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
// }

/**
 * Callback Functions for selective refresh
 */

function unos_callback_archive_post_meta(){
	$metarray = hoot_get_mod('archive_post_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}
function unos_callback_post_meta(){
	$metarray = hoot_get_mod('post_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}
function unos_callback_page_meta(){
	$metarray = hoot_get_mod('page_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}