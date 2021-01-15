=== Unos ===
Contributors: wphoot
Tags: grid-layout, one-column, two-columns, three-columns, left-sidebar, right-sidebar, custom-background, custom-colors, custom-header, custom-menu, custom-logo, featured-images, footer-widgets, full-width-template, microformats, sticky-post, theme-options, threaded-comments, translation-ready, blog, photography, portfolio
Requires at least: 4.7
Tested up to: 5.4
Requires PHP: 5.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Unos is a responsive WordPress theme with a clean modern design.

== Description ==

Unos is a responsive WordPress theme with a clean modern design. For more information about Unos please go to https://wphoot.com/themes/unos/ Theme support is available at https://wphoot.com/support/ You can also check out the theme instructions at https://wphoot.com/support/unos/ and demo at https://demo.wphoot.com/unos/ for a closer look.

== Frequently Asked Questions ==

= How to install Unos =

1. In your admin panel, go to Appearance -> Themes and click the 'Add New' button.
2. Type in 'Unos' in the search form and press the 'Enter' key on your keyboard.
3. Click on the 'Activate' button to use your new theme right away.

= How to get theme support =

You can look at the theme instructions at https://wphoot.com/support/unos/ To get support beyond the scope of documentation provided, please open a support ticket via https://wphoot.com/support/

== Changelog ==

= 2.9.11 =
* Fix below header css for mobile
* Fix edge case: ios mobile add superfish js delay
* Fix menu 'jump' due to dropdown menu arrow and fix megamenu icon width

= 2.9.10 =
* Woocommerce heading styles (related, upsell, cross-sells)
* Add theme-name to body class
* Fixed HootKit top banner mark css
* Added HootKit Cover Image slider css

= 2.9.9 =
* Fix container class for jetpack infinite-scroll (when frontpage set to display blog)
* Fix compatibility with wp-megamenu plugin (plugin css hiding logo area)
* Minor hootkit WC widgets css fix

= 2.9.8 =
* Added Options builder helper product functions
* Fix block button when user adds the button class manually (to display theme's button style)
* Updated deprecated syntax (jQuery 3.0) in parallax script from .on('ready',handler) to $(handler) https://api.jquery.com/ready/
* Added HootKit widgets - Cover Image and Content Grid
* Updated css for hootkit post-grid and post-list widgets to generic grid and list
* Fix php error in loop-meta when 'loop-meta-wrap' is null
* Added HootKit widgets for WooCommerce products
* Added HootKit Top Banner feature and timer shortcode

= 2.9.7 =
* Update sanitization for text in sortlist to wp_kses_post
* Misc Woocommerce stylings (tabs on single product page, pagination, buttons, table radius)
* Improved pagination styling - archive, post links, woocommerce
* Migrate below-header area from table layout to flex layout
* Fix for AMP menu with fixed menu javascript
* CSS fix for Image block (caption overflow image in default settings) and Cover block (image overflow due to padding)
* Refactored About Theme page code
* Added static featured image (non cropped) option in header on single post/page
* Minor CSS Fixes (geared towards improving accessibility)
* Added menu tag feature

= 2.9.6 =
* Skip Version

= 2.9.5 =
* Mobile Fixed Menu - Fix display for logged in users with admin bar

= 2.9.4 =
* Remove column/total item options in favor of WC default options
* Remove top/bottom margin for main content area added in last update (fixes no space issue in certain displays theme)
* Minor CSS adjustment for HootKit Announce widget with link
* Fix for WP 5.5 without jquery-migrate - Localize hootData using hoverIntent instead of jquery
* Improve accessibility - fix keyboard navigation issues (menu, mobile menu, search widget etc)

= 2.9.3 =
* AMP support for search (remove JS for input text field)
* Return empty read more text for feed
* Fix: color rgba sanitization in style builder for dynamic css
* Fix Safari bug for sidebars when percentage width gets rounds up (in flex model)
* Minor css fix for ajax login popup
* Minor CSS class fixes, site title tagline enclosed within span

= 2.9.2 =
* Fix site title with icon (flex to inline flex) when branding is center of header
* Removed orphan sourceMappingURL from third party library files
* AMP plugin support
* Fix mobile safari bug when telephone numbers are automatically converted to links with hidden font color
* Minor fix: Removed for attribute for label in search form widget
* Fix mosaic layout for blog when displayed on frontpage - add archive-wrap div wrap
* Fixed number argument in HootKit::get_terms()
* Added boxed/stretch option for below header widget area
* Added static background image option in Header for Pages/Posts
* Added Telegram to social sites list enum for HootKit
* Jetpack infinite scroll - remove explicit type setting to 'click' to allow 'scroll' option as well

= 2.9.1 =
* Added single/multiple line option to ticker and post ticker
* Fix read more filters to force read more link for posts with custom excerpts and small excerpts
* Add filter for locate template functions for widget, content, archive templates
* Update google fonts url to API2 with display swap
* Bug Fix frontpage page content background option
* Remove deprecated array offset using {} for PHP 7.4 #8681
* css update for hootkit ticker widgets

= 2.9.0 =
* CSS for block elements in WP5.4 (social icons)
* CSS for block editor dropcap, generic font sizes used in blocks, other minor adjustments
* Update 'get_the_archive_title' hooked function to remove prefixes from translated WordPress strings as well

* Updated Requires at least, Tested up to and Requires PHP tags in style.css and readme.txt
* Add sidebar layout option for archives/blog to lite
* CSS updates for Block gallery (margins for gallery grid, Gallery Captions, image captions)
* Various CSS fixes and other minor adjustments
* Add script for interlinking customizer control/section/panel
* Improved hoot_get_attr function to accept classes when other custom attributes also present
* Add separate sidebar layout option for frontpage content block (blog / static page)
* Updated Hootkit viewall CSS for new structure
* Updated deprecated get_woocommerce_term_meta function with get_term_meta
* Added invert accent typo
* Fixed live customize preview for frontpage area k and l background options

= 2.8.7 =
* Hide Menu Toggle when Max Mega Menu active
* Add font-display:swap to font icons (fixes Google Pagespeed error: Ensure text remains visible during webfont load)
* Check for parent theme object before assigning parent theme details to framework variables (fixes edge case scenario on certain server configurations)
* Add 'entry-title' class to h1.loop-title (to make title compatibile with Elementor hide-title option)
* Remove filter=5 attribute from wordpress.org review url

= 2.8.6 =
* Polylang menu flag image alignment
* Fix bbPress Forums view (archive view was being displayed instead of forums list)
* Fix title/descriptions for bbPress User view, Single Forum view, Forums view
* Fix Header Aside Search display on mobile
* Fix wp-SmushIt lazyload compatibility issue caused due to class="no-js" in <html> (remnant from modernizr.js)
* Support 'announce-headline' for HootKit Announce widget

= 2.8.5 =
* Add gravatar to loop meta for authors
* Increase frontpage modules to 12

= 2.8.4 =
* Added CSS for HootKit Slider Post List Carousel
* Added background for Hootkit Post List widget
* Stretch images for HootKit Post List widget

= 2.8.3 =
* Update ul.wp-block-gallery css for WP 5.3 compatibility
* Added HTML5 Supports Argument for Script and Style Tags for WP 5.3
* Added semibold option for typography settings
* Fix custom logo bug (fix warning when default value does not have all options defined for sortitem line)
* Apply filter to frontpage id index for background options
* Fix content block row bottom margin
* Better display css for breadcrumb plugin title
* Fix padding for frontpage area (with background) in customize live preview
* Add support and accompanying css for Ticker Posts widget
* Add support and accompanying css for Post Grid widget - first post slider
* CSS fix for sortitem checkbox input in customizer
* CSS fix for woocommerce message button on small screen (ticket#4621)
* Upgrade logo-with-icon from Table to Flexbox

= 2.8.2 =
* Logo and Site Description css fixes
* Fix social icons widget color for footer nand invert/non-invert topbar
* Added Logo Border option
* Add Georgia option for heading font
* Add sidebar title hover state

= 2.8.1 =
* Accessibility Improvements: Skip to Content link fixed
* Accessibility Improvements: Keyboard Navigation improved (link and form field outlines, improved button focus visual)

= 2.8.0 =
* Removed min-width for grid (mobile view)
* Fix iframe and embed margins in wp embed blocks
* Fix label max-width for contact forms to display properly on mobile
* Fix for woocommerce pagination when inifinite scroll is active
* Fix required fonticon for Contact Form 7 plugin
* Bug Fix: Add space for loop-meta-wrap class (for mods)
* Improve right align for full width menu
* Remove shim for the_custom_logo
* Fix: Inline menu display css fix with mega menu plugin
* Apply filter on arguments array for the_posts_pagination
* Add help link to One Click Demo documentation
* Replace support for HootKit with OCDI plugin
* Add support for hootkit's 'content-blocks-style5', 'content-blocks-style6', 'slider-styles'
* Add support for Breadcrumb NavXT plugin
* Remove secondary menu and adjust primary menu display options accordingly
* Customizer Live / Selective Refresh support
* Remove site width option
* Default site width increased from 1380 to 1440
* Multiple CSS Updates / Update dynamic css

= 2.7.7 =
* Removed One Click Demo for compatibility with TRT guidelines
* HootKit CSS Fixes
* CSS fix: last menu item dropdown
* Added missing argument for 'the_title' filter to prevent error with certain plugins
* Added Featured image for Categories and Tags
* Added CSS to style woocommerce buttons based on theme colors
* Updated location functions to optionally return templates array - updated hoot_comments_callback accordingly

= 2.7.6 =
* Added option for frontpage module font color
* Improved template logic for displaying Previous/Next post (using a wrapping function)
* Removed 'Custom Text' option from Primary Menu area in header

= 2.7.5 =
* Added support for 'inherit' value in css sanitization for dynamic css build
* Bug fix for 'box-shadow' property in css sanitization for dynamic css build
* Bug fix by unsetting selective refresh from passing into $settings array in customizer interface builder

= 2.7.4 =
* Added the new 'wp_body_open' function
* Improved dynamic css for ajax login form

= 2.7.3 =
* Added Tribe 'The Events Calendar' plugin support - template fixes
* Improved logic for hoot_get_mod function

= 2.7.2 =
* Add css support for Gutenberg gallery block
* Fix parallax image misalignment on load when lightslider is present
* Sanitize debug data for logged in admin users
* Added 'background-size' option to dynamic css style-builder
* Minor CSS adjustments (static and dynamic) for text logo color
* Updated hootkit config array for HootKit v1.0.6 and above

= 2.7.1 =
* HootKit About widget fix for square image option: removed border radius

= 2.7.0 =
* Initial release

== Upgrade Notice ==

= 2.9 =
* This is the officially supported stable release version. Please update to this version before opening a support ticket.

== Resources ==

= This Theme has code derived/modified from the following resources all of which, like WordPress, are distributed under the terms of the GNU GPL =

* Underscores WordPress Theme, Copyright 2012 Automattic http://underscores.me/
* Hybrid Core Framework v3.0.0, Copyright 2008 - 2015, Justin Tadlock  http://themehybrid.com/
* Hybrid Base WordPress Theme v1.0.0, Copyright 2013 - 2015, Justin Tadlock  http://themehybrid.com/
* Customizer Library v1.3.0, Copyright 2010 WP Theming http://wptheming.com
* HootKit WordPress Plugin, Copyright 2018 wpHoot https://wordpress.org/plugins/hootkit/

= This theme bundles the following third-party resources =

* FitVids http://fitvidsjs.com/ Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com : WTFPL license http://sam.zoy.org/wtfpl/
* lightSlider http://sachinchoolur.github.io/lightslider/ Copyright sachi77n@gmail.com : MIT License
* Superfish https://github.com/joeldbirch/superfish/ Copyright Joel Birch : MIT License
* Font Awesome http://fontawesome.io/ Copyright (c) 2015, Dave Gandy : SIL OFL 1.1 (Font) MIT License (Code)
* TRT Customizer Pro https://github.com/justintadlock/trt-customizer-pro Copyright 2016 Justin Tadlock : GNU GPL Version 2
* TGM-Plugin-Activation https://github.com/TGMPA/TGM-Plugin-Activation Copyright (c) 2016 TGM : GNU GPL Version 2
* Parallax http://pixelcog.com/parallax.js/ Copyright 2016 PixelCog Inc. : MIT License
* Theia Sticky Sidebar https://github.com/WeCodePixels/theia-sticky-sidebar/ Copyright 2013-2016 WeCodePixels : MIT License
* Resize Sensor from CSS Element Queries https://github.com/marcj/css-element-queries/ Copyright (c) 2013 Marc J. Schmidt : MIT License

= This theme screenshot contains the following images =

* Image: Outdoor Snow https://pxhere.com/en/photo/665561 : CC0
* Image: Girl White https://pxhere.com/en/photo/459247 : CC0
* Image: Tree Nature https://pxhere.com/en/photo/1113153 : CC0
* Image: Branch Snow https://pxhere.com/en/photo/918677 : CC0

= Bundled Images: The theme bundles these images =

* Image: /images/header.jpg https://pxhere.com/en/photo/665561 : CC0
* Image: /images/modulebg.jpg https://pxhere.com/en/photo/812311 : CC0

= Bundled Images: The theme bundles patterns in /images/patterns =

* Background Patterns, Copyright 2015, wpHoot : CC0

= Bundled Images: The theme bundles composite images in /include/admin/images using the following resources =

* Misc UI Grpahics, Copyright 2015, wpHoot : CC0
* Image: Wild Hair https://pxhere.com/en/photo/606493 : CC0
* Image: Wood Spice Cooking https://pxhere.com/en/photo/444 : CC0
* Image: Desk https://pxhere.com/en/photo/1434235 : CC0
* Image: Analysis Background https://pxhere.com/en/photo/1445331 : CC0
* Image: Aerial Background https://pxhere.com/en/photo/1430841 : CC0
* Image: Article Assortment https://pxhere.com/en/photo/1452883 : CC0
* Image: Avatar Network https://pxhere.com/en/photo/1444327 : CC0
* Image: Mans Avatar https://publicdomainvectors.org/en/free-clipart/Mans-avatar/49761.html : CC0
* Image: Man with beard https://publicdomainvectors.org/en/free-clipart/Man-with-beard-profile-picture-vector-clip-art/16285.html : CC0
* Image: Faceless Female Avatar https://publicdomainvectors.org/en/free-clipart/Faceless-female-avatar/71113.html : CC0