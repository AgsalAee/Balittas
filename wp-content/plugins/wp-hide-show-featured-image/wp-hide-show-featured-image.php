<?php
/*
  Plugin Name: WP Hide Show Featured Image
  Plugin URI: https://wordpress.org/plugins/wp-hide-show-featured-image/
  Description: To hide/show featured images on posts and pages. Hide Admin Toolbar from the user end, Remove the "Howdy" text in the upper right corner of your admin dashboard, Remove the WordPress logo from the upper left corner of the admin bar.
  Version: 1.0
  Author: Galaxy Weblinks
  Author URI: https://www.galaxyweblinks.com
  License: GPLv2 or later
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}  

// Register essential hooks 
add_action( 'wp_head', 'whsfi_featured_image');
add_action( 'admin_menu', 'whsfi_settings_menu' );
add_action( 'admin_init', 'whsfi_hide_register_settings' );

//admin notice when activate plugin
register_activation_hook(__FILE__, 'whsfi_hide_show_featured_image_activation');

/* Added hook for plugin Activated */
function whsfi_hide_show_featured_image_activation(){
	update_option('whsfi_hide_show_featured_image_notice','enabled');
}

/* Show Message when plugin Activated and for to setting page */
function whsfi_hide_show_featured_image_notice__success() {
	if(get_option('whsfi_hide_show_featured_image_notice') == 'enabled'){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'To view <strong>WP Hide Show Featured Image</strong> setting please', 'wp-hide-show-featured-image' ); ?><a class="button button-primary" href="<?php echo admin_url('admin.php?page=whsfi_hide_options'); ?>"> <?php _e( 'click here', 'wp-hide-show-featured-image' ); ?></a></p>
		</div>
		<?php 
		delete_option('whsfi_hide_show_featured_image_notice');
	}
}
add_action( 'admin_notices', 'whsfi_hide_show_featured_image_notice__success' );

/**
 *  Create function used for wp hide show featured image from on the single post and page
*/
function whsfi_featured_image() {

	if( is_single() || is_page() ){

		$whsfi_hide = false;

		$whsfi_hide_all = get_option('whsfi_hide_all_image');/* This function used for WP Hide Show all post or image on the frontend */

		$whsfi_hide_image = get_post_meta( get_the_ID(), '_whsfi_hide_featured', true );/* This function used for WP Hide Show single post */

		$whsfi_hide = ( is_page() && isset( $whsfi_hide_all['page_image'] ) && $whsfi_hide_all['page_image'] && $whsfi_hide_image != 2 ) ? true : $whsfi_hide ; 
		$whsfi_hide = ( is_singular( 'post' ) && isset( $whsfi_hide_all['post_image'] ) && $whsfi_hide_all['post_image'] && $whsfi_hide_image != 2 ) ? true : $whsfi_hide ; 
		$whsfi_hide = ( isset( $whsfi_hide_image ) && $whsfi_hide_image && $whsfi_hide_image != 2 )? true : $whsfi_hide;/* Used for WP Hide Show single post image */

		if( $whsfi_hide ){ 
			function whsfi_post_image_html( $html, $post_id, $post_image_id ) {
				if(is_single() || is_page()) {
					return '';
				} else

				return $html;
			}
			add_filter( 'post_thumbnail_html', 'whsfi_post_image_html', 10, 3 );
			
		}
	}
}

/**
 *  Create admin menu option in dashboard 
 */
function whsfi_settings_menu() {
	add_submenu_page(
      'options-general.php',          // adds admin page slug
      __( 'WP Hide Show Featured Image', 'wp-hide-show-featured-image' ), // show page title
      __( 'WP Hide Show Featured Image', 'wp-hide-show-featured-image' ), // show setting menu title
      'manage_options',               // capability required to see the page
      'whsfi_hide_options',                // admin page slug, e.g. options-general.php?page=whsfi_hide_options
      'whsfi_settings_page'            // used callback function to display the setting options page
  );
}

/**
 *  Register our settings
 */
function whsfi_hide_register_settings() {
	register_setting( 'whsfi_hide_options', 'whsfi_hide_all_image' );
	register_setting( 'whsfi_hide_options', 'whsfi_hide_admin_bar' );
	register_setting( 'whsfi_hide_options', 'whsfi_hide_howdy_text' );
	register_setting( 'whsfi_hide_options', 'whsfi_hide_admin_bar_logo' ); 
}

/**
 *  Create option add settings page
*/
function whsfi_settings_page() {
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false; ?>

	<div class="wrap whsfi_hide_featured">
		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
		<div id="poststuff">
			<div id="post-body">
				<div class="postbox-container column-primary">
					<div id="hide_featured_setting" class="postbox">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text"><?php _e('Toggle panel: General Settings');?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
						<h2 class="hndle"><span><?php _e('General Settings');?></span></h2>
						<div class="inside">
							<form method="post" action="options.php">
								<?php settings_fields( 'whsfi_hide_options' ); ?>
								<?php $whsfi_hide_image = get_option( 'whsfi_hide_all_image' ); 
								$whsfi_hide_admin_bar = get_option( 'whsfi_hide_admin_bar' );
								$whsfi_hide_howdy_text     = get_option( 'whsfi_hide_howdy_text');
								$whsfi_hide_admin_bar_logo = get_option( 'whsfi_hide_admin_bar_logo'); 
								?>
								<table class="form-table">
									<tr valign="top"><th scope="row"><?php _e( 'Hide Image from all posts(not Custom Post Type)?', 'wp-hide-show-featured-image' ); ?></th>
										<td>
											<?php $selected = ( isset( $whsfi_hide_image['post_image'] ) ) ? $whsfi_hide_image['post_image'] : 0; ?>                                       
											<input type="radio" name="whsfi_hide_all_image[post_image]" value="1" <?php echo esc_attr(checked( $selected, 1 ) ); ?>><?php _e( 'Yes', 'wp-hide-show-featured-image' ); ?>&nbsp;&nbsp;
											<input type="radio" name="whsfi_hide_all_image[post_image]" value="0" <?php echo esc_attr( checked( $selected, 0 ) ); ?>><?php _e( 'No', 'wp-hide-show-featured-image' ); ?>
										</td>
									</tr>
									<tr valign="top"><th scope="row"><?php _e( 'Hide Image from all Pages?', 'wp-hide-show-featured-image' ); ?></th>
										<td>
											<?php $selected = ( isset( $whsfi_hide_image['page_image'] ) ) ? $whsfi_hide_image['page_image'] : 0; ?>                                                  
											<input type="radio" name="whsfi_hide_all_image[page_image]" value="1" <?php echo esc_attr( checked( $selected, 1 ) ); ?>><?php _e( 'Yes', 'wp-hide-show-featured-image' ); ?>&nbsp;&nbsp;
											<input type="radio" name="whsfi_hide_all_image[page_image]" value="0" <?php echo esc_attr( checked( $selected, 0 ) ); ?>><?php _e( 'No', 'wp-hide-show-featured-image' ); ?>
										</td>
									</tr>
									<tr valign="top"><th scope="row"><?php _e( 'Hide Admin Toolbar from the user end' ); ?></th>
										<td>
											<?php $selected = ( isset( $whsfi_hide_admin_bar['admin_bar'] ) ) ? $whsfi_hide_admin_bar['admin_bar'] : 0; ?>
											<input type="radio" name="whsfi_hide_admin_bar[admin_bar]" value="1" <?php echo esc_attr(checked(1, $selected, true) ); ?> /><?php _e( 'Yes'); ?>&nbsp;&nbsp;
											<input type="radio" name="whsfi_hide_admin_bar[admin_bar]" value="0" <?php echo esc_attr(checked(0, $selected, true) ); ?> /><?php _e( 'No'); ?>
										</td>
									</tr>
									<tr valign="top"><th scope="row"><?php _e( 'Remove the "Howdy" text in the upper right corner of your admin dashboard.' ); ?></th>
										<td>
											<?php $selected = ( isset( $whsfi_hide_howdy_text['howdy_text'] ) ) ? $whsfi_hide_howdy_text['howdy_text'] : 0; ?>
											<input type="radio" name="whsfi_hide_howdy_text[howdy_text]" value="1" <?php echo esc_attr(checked(1, $selected, true) ); ?> /><?php _e( 'Yes'); ?>&nbsp;&nbsp;
											<input type="radio" name="whsfi_hide_howdy_text[howdy_text]" value="0" <?php echo esc_attr(checked(0, $selected, true) ); ?> /><?php _e( 'No'); ?>
										</td>
									</tr>
									<tr valign="top"><th scope="row"><?php _e( 'Remove the WordPress logo from the upper left corner of the admin bar.' ); ?></th>
										<td>
											<?php $selected = ( isset( $whsfi_hide_admin_bar_logo['admin_bar_logo'] ) ) ? $whsfi_hide_admin_bar_logo['admin_bar_logo'] : 0; ?>
											<input type="radio" name="whsfi_hide_admin_bar_logo[admin_bar_logo]" value="1" <?php echo esc_attr(checked(1, $selected, true) ); ?> /><?php _e( 'Yes'); ?>&nbsp;&nbsp;
											<input type="radio" name="whsfi_hide_admin_bar_logo[admin_bar_logo]" value="0" <?php echo esc_attr( checked(0, $selected, true) ); ?> /><?php _e( 'No'); ?>
										</td>
									</tr>
								</table><br>
								<input type="submit" class="button button-primary" >
							</form><br>
						</div>
					</div>
				</div> <!-- end post-body-content-section -->
			</div> <!-- end post-body-section -->
		</div> <!-- end poststuff-section -->
	</div>
	<?php
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'wp-hide-forms',  $plugin_url . "includes/css/wp-hide-show-featureimg-admin.css");
}

/**
 * Hide Admin Toolbar from the user end
 */
$whsfi_hide_admin_bar = get_option( 'whsfi_hide_admin_bar'); 
if ($whsfi_hide_admin_bar['admin_bar'] ) { 
	function whsfi_hide_admin_bar_from_front_end(){
		if (is_blog_admin()) {
			return true;
		}
		return false;
	}
	add_filter( 'show_admin_bar', 'whsfi_hide_admin_bar_from_front_end' );
}

/**
 * Remove the "Howdy" text in the upper right corner of your admin dashboard
 */
$whsfi_hide_howdy_text = get_option( 'whsfi_hide_howdy_text'); 
if ($whsfi_hide_howdy_text['howdy_text'] ) { 
	function whsfi_remove_howdy( $wp_admin_bar ) {
		$wp_account=$wp_admin_bar->get_node('my-account');
		$newtitle = str_replace( 'Howdy,', '', $wp_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}
	add_filter( 'admin_bar_menu', 'whsfi_remove_howdy',25 );
}

/**
 * Remove the WordPress logo from the admin-bar
 */
$whsfi_hide_admin_bar_logo = get_option( 'whsfi_hide_admin_bar_logo'); 
if ($whsfi_hide_admin_bar_logo['admin_bar_logo'] ) { 
	function whsfi_admin_bar_remove_logo() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}
	add_action('wp_before_admin_bar_render','whsfi_admin_bar_remove_logo', 0 );
} 
