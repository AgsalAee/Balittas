<?php
/**
 * Add admin notice
 *
 * @package           Hootkit
 * @subpackage        Hootkit/admin
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Add notice if not already dismissed
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hootkit_notices_init( $current_screen ) {
	if ( !empty( $current_screen->id ) && ( $current_screen->id == 'widgets' || $current_screen->id == 'plugins' ) ) {
		if ( empty( get_option( 'hootkit-dismiss-review' ) ) ) {
			$activated = get_option( 'hootkit-activate' );
			// Add notice if empty for previously activated plugin, or time greater than set time
			if ( empty( $activated ) || ( is_numeric( $activated ) && $activated + ( 2 * 24 * 60 * 60 ) < time() ) ) {
				add_action( 'admin_notices', 'hootkit_add_notice', 9 );
				add_action( 'admin_print_footer_scripts', 'hootkit_dismiss_noticescript' );
			}
		}
	}
}
add_action( 'current_screen', 'hootkit_notices_init' );

/**
 * Display admin notice
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hootkit_add_notice() {
	?>
	<div id="hootkit-review-msg" class="notice notice-info is-dismissible">
		<?php
		/* Translators: 1 and 2 are placeholders for HTML p tag, 3 and 4 are placeholders for HTML strong tag, 5 and 6 are placeholders for link markup */
		printf( esc_html__( '%1$sIf you have enjoyed using %3$sHootKit%4$s, can you do us a BIG favor and %3$s%5$srate it on WordPress here%6$s%4$s. %2$s%1$sIt helps us spread the word, and really boosts our team motivation.%2$s', 'hoot-theme-text' ), '<p>', '</p>', '<strong>', '</strong>', '<a href="https://wordpress.org/support/plugin/hootkit/reviews/?filter=5#new-post" target="_blank">', '</a>' );
		?>
	</div>
	<?php
}

/**
 * Add dismiss script
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hootkit_dismiss_noticescript(){
	?><script> jQuery(document).ready(function($) { "use strict"; $('#hootkit-review-msg .notice-dismiss').on('click',function(e){ jQuery.ajax({ url : ajaxurl, type : 'post', data : { 'action': 'hootkit_dismiss_notice', 'nonce': '<?php echo wp_create_nonce( 'dismiss-hootkit-review' ); ?>' } }); }); }); </script><?php
}

/**
 * Ajax callback to set dismissable notice
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hootkit_dismiss_notice() {
	check_ajax_referer( 'dismiss-hootkit-review', 'nonce' );
	update_option( 'hootkit-dismiss-review', 1 );
	wp_die();
}
add_action( 'wp_ajax_hootkit_dismiss_notice', 'hootkit_dismiss_notice' );