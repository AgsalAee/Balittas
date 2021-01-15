<?php
/**
 * Register custom theme menus
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 *
 * @package    Unos
 * @subpackage Theme
 */

/**
 * Registers nav menu locations.
 *
 * @since 1.0
 * @access public
 * @return void
 */
add_action( 'init', 'unos_base_register_menus', 5 );
function unos_base_register_menus() {
	register_nav_menu( 'hoot-primary-menu', _x( 'Primary Menu', 'nav menu location', 'unos' ) );
}

/**
 * Create custom menu admin fields
 *
 * @since 2.9
 */
function unos_nav_menu_item_custom_fields( $item_id, $item, $depth, $args ){
	$defaults = wp_parse_args( apply_filters( 'unos_nav_menu_defaults', array() ), array(
					'tagbg' => '#000000',
					'tagfont' => '#ffffff',
					'tagbg_label' => __( 'Tag Background', 'unos' ),
					'tagfont_label' => __( 'Tag Font', 'unos' ),
				) );
	?>
	<p class="field-hoot_tag description description-wide hoot_top_level_only" <?php if ( 0 !== $depth ) echo ' style="display:none;" ' ?>>
		<label for="edit-menu-item-hoot_tag-<?php echo $item_id; ?>">
			<?php _e( 'Tag Text', 'unos' ) ?><br />
			<input type="text" id="edit-menu-item-hoot_tag-<?php echo $item_id ?>" class="widefat code edit-menu-item-hoot_tag" name="menu-item-hoot_tag[<?php echo $item_id ?>]" value="<?php echo esc_attr( $item->hootmenu[ 'hoot_tag' ] ); ?>" />
		</label>
	</p>
	<div class="seperator" style="clear:both"></div>
	<p class="field-hoot_tagbg description description-thin hoot_top_level_only hoot-color-wrap" <?php if ( 0 !== $depth ) echo ' style="display:none;" ' ?>>
		<label for="edit-menu-item-hoot_tagbg-<?php echo $item_id; ?>" style="display:block"><?php echo esc_html( $defaults['tagbg_label'] ); ?></label>
		<input type="input" id="edit-menu-item-hoot_tagbg-<?php echo $item_id ?>" class="widefat code edit-menu-item-hoot_tagbg hoot-color" name="menu-item-hoot_tagbg[<?php echo $item_id ?>]" value="<?php echo esc_attr( $item->hootmenu[ 'hoot_tagbg' ] ); ?>" data-default-color="<?php echo sanitize_hex_color( $defaults['tagbg'] ); ?>" />
	</p>
	<p class="field-hoot_tagfont description description-thin hoot_top_level_only hoot-color-wrap" <?php if ( 0 !== $depth ) echo ' style="display:none;" ' ?>>
		<label for="edit-menu-item-hoot_tagfont-<?php echo $item_id; ?>" style="display:block"><?php echo esc_html( $defaults['tagfont_label'] ); ?></label>
		<input type="input" id="edit-menu-item-hoot_tagfont-<?php echo $item_id ?>" class="widefat code edit-menu-item-hoot_tagfont hoot-color" name="menu-item-hoot_tagfont[<?php echo $item_id ?>]" value="<?php echo esc_attr( $item->hootmenu[ 'hoot_tagfont' ] ); ?>" data-default-color="<?php echo sanitize_hex_color( $defaults['tagfont'] ); ?>" />
	</p>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'unos_nav_menu_item_custom_fields', 10, 4 );

/**
 * Add value of our custom fields to $item object that will be passed to our walker for Edit Menu
 *
 * @since 2.9
 */
function unos_setup_nav_item( $menu_item ){
	$values = get_post_meta( $menu_item->ID, '_menu-item-hootmenu', true );
	if ( !is_array( $values ) ) $values = array(); // Needed to prevent illegal offstring in certain xampp for $values[ $key ] = '';
	if ( !isset( $values[ 'hoot_tag' ] ) ) $values[ 'hoot_tag' ] = '';
	if ( !isset( $values[ 'hoot_tagfont' ] ) ) $values[ 'hoot_tagfont' ] = '';
	if ( !isset( $values[ 'hoot_tagbg' ] ) ) $values[ 'hoot_tagbg' ] = '';
	$menu_item->hootmenu = $values;
	return $menu_item;
}
add_filter( 'wp_setup_nav_menu_item', 'unos_setup_nav_item' , 100 );

/*
 * Save and Update the Custom Fields in Navigation Menu Items
 * 
 * @since 2.9
 * @param int $menu_id
 * @param int $menu_item_db
 */
function unos_update_nav_menu( $menu_id, $menu_item_db_id, $args ){
	$values = array();
	$options = array(
		'hoot_tag' => true,
		'hoot_tagfont' => true,
		'hoot_tagbg' => true,
	);
	foreach ( $options as $key => $toplevel ) {
		$values[ $key ] = isset( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] ) ? 
						  $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] :
						  '';
		if ( $toplevel == true ) $values[ $key . '_top_level' ] = '1';
	}
	update_post_meta( $menu_item_db_id, '_menu-item-hootmenu', $values );
}
add_action( 'wp_update_nav_menu_item', 'unos_update_nav_menu', 100, 3 );

/*
 * Enqueue Menu screen script
 * 
 * @since 2.9
 */
add_action( 'admin_enqueue_scripts', 'unos_enqueue_admin_styles_scripts' );
function unos_enqueue_admin_styles_scripts( $hook ) {
	if ( 'nav-menus.php' == $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
		if ( file_exists( hoot_data()->incdir . 'admin/js/menuedit.js' ) )
			wp_enqueue_script( 'unos-menuedit', hoot_data()->incuri . 'admin/js/menuedit.js', array( 'wp-color-picker' ), hoot_data()->hoot_version, true );
	}
}

/**
 * Remove deprecated hooks from older versions of child themes in favor of parent theme code
 * @since 2.9
 */

remove_action( 'wp_nav_menu_item_custom_fields', 'unospub_nav_menu_item_custom_fields', 10 );
remove_filter( 'wp_setup_nav_menu_item', 'unospub_setup_nav_item' , 100 );
remove_action( 'wp_update_nav_menu_item', 'unospub_update_nav_menu', 100 );
remove_filter( 'nav_menu_item_title', 'unospub_nav_menu_start_el', 4 );
remove_filter( 'nav_menu_item_title', 'unospub_nav_menu_start_el', 7 );
remove_action( 'admin_enqueue_scripts', 'unospub_enqueue_admin_styles_scripts' );

remove_action( 'wp_nav_menu_item_custom_fields', 'unosmvu_nav_menu_item_custom_fields', 10 );
remove_filter( 'wp_setup_nav_menu_item', 'unosmvu_setup_nav_item' , 100 );
remove_action( 'wp_update_nav_menu_item', 'unosmvu_update_nav_menu', 100 );
remove_filter( 'nav_menu_item_title', 'unosmvu_nav_menu_start_el', 4 );
remove_filter( 'nav_menu_item_title', 'unosmvu_nav_menu_start_el', 7 );
remove_action( 'admin_enqueue_scripts', 'unosmvu_enqueue_admin_styles_scripts' );

remove_action( 'wp_nav_menu_item_custom_fields', 'unosbiz_nav_menu_item_custom_fields', 10 );
remove_filter( 'wp_setup_nav_menu_item', 'unosbiz_setup_nav_item' , 100 );
remove_action( 'wp_update_nav_menu_item', 'unosbiz_update_nav_menu', 100 );
remove_filter( 'nav_menu_item_title', 'unosbiz_nav_menu_start_el', 4 );
remove_filter( 'nav_menu_item_title', 'unosbiz_nav_menu_start_el', 7 );
remove_action( 'admin_enqueue_scripts', 'unosbiz_enqueue_admin_styles_scripts' );

remove_action( 'wp_nav_menu_item_custom_fields', 'unosglow_nav_menu_item_custom_fields', 10 );
remove_filter( 'wp_setup_nav_menu_item', 'unosglow_setup_nav_item' , 100 );
remove_action( 'wp_update_nav_menu_item', 'unosglow_update_nav_menu', 100 );
remove_filter( 'nav_menu_item_title', 'unosglow_nav_menu_start_el', 4 );
remove_filter( 'nav_menu_item_title', 'unosglow_nav_menu_start_el', 7 );
remove_action( 'admin_enqueue_scripts', 'unosglow_enqueue_admin_styles_scripts' );