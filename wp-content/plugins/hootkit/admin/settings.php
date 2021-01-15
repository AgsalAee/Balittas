<?php
/**
 * Admin Settings class
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
 * HootKit Settings class.
 *
 * @since 1.1.0
 */
class HootKit_Settings {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Setup Admin Settings
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Add action links on Plugin Page
		add_action( 'plugin_action_links_' . hootkit()->plugin_basename, array( $this, 'plugin_action_links' ), 10, 4 );

		// Add settings page
		add_action( 'admin_menu',            array( $this, 'add_page' )           );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_register' ), 0  );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ),  10 );

		// Add ajax callback
		add_action( 'wp_ajax_hootkitsettings', array( $this, 'admin_ajax_settings_handler' ) );

	}

	/**
	 * Add action links
	 * @param string[] $actions     An array of plugin action links. By default this can include 'activate',
	 *                              'deactivate', and 'delete'. With Multisite active this can also include
	 *                              'network_active' and 'network_only' items.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array    $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string   $context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                              'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
		$actions['manage'] = '<a href="' . admin_url('admin.php?page=' . hootkit()->slug ) . '">' . __( 'Settings', 'hootkit' ) . '</a>'; // options-general.php
		return $actions;
	}

	/**
	 * Add Settings Page
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function add_page(){
		add_submenu_page( //# add_menu_page
			'options-general.php', //#
			__( 'HootKit Modules Settings', 'hootkit' ),
			__( 'HootKit', 'hootkit' ),
			'manage_options',
			hootkit()->slug,
			array( $this, 'render_admin' ) );
	}

	/**
	 * Register admin Scripts and Styles
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function admin_register( $hook ) {

		// Register assets
		wp_register_style( hootkit()->slug . '-admin', hootkit()->asset_uri( 'admin/assets/settings', 'css' ) );
		wp_register_script( hootkit()->slug . '-admin', hootkit()->asset_uri( 'admin/assets/settings', 'js' ), array( 'jquery' ) );

		// Localize data
		wp_localize_script( hootkit()->slug . '-admin', 'hootkitData', array(
			'strings' => array(
				'success' => __( 'Settings Saved', 'hootkit' ),
				'error'   => __( 'Some Error Occurred', 'hootkit' )
			),
			'ajaxurl' => wp_nonce_url( admin_url('admin-ajax.php?action=hootkitsettings'), 'hootkitadmin-settings-nonce' )
		) );

	}

	/**
	 * Enqueue admin Scripts and Styles
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue( $hook ) {
		if ( $hook == 'settings_page_' . hootkit()->slug ) { //# toplevel_page_
			wp_enqueue_script( hootkit()->slug . '-admin' );
			wp_enqueue_style( hootkit()->slug . '-admin' );
		}
	}

	/**
	 * Render Page
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function render_admin(){
		?>
		<div class="hootkit-wrap wrap">

			<div class="hootkit-header">
				<div class="hk-gridbox">
					<h1><?php esc_html_e( 'HootKit Settings', 'hootkit' ); ?></h1>
					<h4><?php printf( esc_html__( 'Version: %1$s' ), hootkit()->version ); ?></h4>
				</div>
			</div><!-- .hootkit-header -->

			<div id="hootkit-container" class="hootkit-container">
				<form id="hootkit-settings">

					<div class="hk-gridbox hk-titlebox-wrap"><div class="hk-titlebox">
						<div class="hk-title"><?php esc_html_e( 'Enable/Disable hootkit widgets and modules', 'hootkit' ); ?></div>
						<div class="hk-actions">
							<a href="#" id="hk-submit" class="button button-primary hk-submit"><?php _e( 'Save Changes', 'hootkit' ); ?></a>
							<?php // submit_button( __( 'Save', 'hootkit' ) ); ?>
							<a href="#" id="hk-enableall" class="button"><?php _e( 'Enable All', 'hootkit' ); ?></a>
							<a href="#" id="hk-disableall" class="button"><?php _e( 'Disable All', 'hootkit' ); ?></a>
							<div id="hkfeedback" class="hkfeedback"></div>
						</div>
					</div></div>

					<?php
					$hkmodules = hootkit()->get_hootkitmods( 'modules' );
					$activemodules = hootkit()->get_config( 'activemodules' );
					$modules = hootkit()->get_config( 'modules' );
					$pmodules = hootkit()->get_config( 'premium' );

					$modsets = array();
					foreach ( $modules as $mod ) {
						if ( !empty( $hkmodules[$mod]['sets'] ) ) {
							foreach ( $hkmodules[$mod]['sets'] as $set ) {
								$modsets[$set][] = $mod;
							}
						}
					}
					$columns = count( $modsets );

					$pmodsets = array();
					foreach ( $pmodules as $pmod ) {
						if ( !empty( $hkmodules[$pmod]['sets'] ) ) {
							foreach ( $hkmodules[$pmod]['sets'] as $set ) {
								$pmodsets[$set][] = $pmod;
							}
						}
					}
					?>
					<div class="hk-gridbox hk-settingsbox hkcol<?php echo $columns; ?>">
						<?php
						foreach ( $modsets as $modset => $mods ) :
							if ( empty( $mods ) ) continue; // non hootkit themes
							$titlearray = array();
							$maintogglecheck = '';
							foreach ( $mods as $mod )
								$titlearray[ $mod ] = esc_html( str_ireplace( 'JNESHK > ', '', hootkit()->get_string( $mod ) ) );
							foreach ( $titlearray as $mod => $title )
								if ( in_array( $mod, $activemodules ) ) { $maintogglecheck = 'checked="checked"'; break; }
							// asort( $titlearray );
							?>
							<div class="hk-modset hk-modset-<?php echo esc_attr( $modset ); ?>">
								<h2 class="hk-modset-title">
									<?php echo esc_html( hootkit()->get_string( $modset ) ); ?>
									<span class="hk-toggle-box"><input type="checkbox" value="1" <?php echo $maintogglecheck; ?>/><span class="hk-toggle"></span></span>
								</h2>
								<div class="hk-mods">
									<?php foreach ( $titlearray as $mod => $title ) :
										$id = sanitize_html_class( $mod );
										?>
										<div class="hk-mod hk-row">
											<div class="hk-toggle-box">
												<input name="<?php echo esc_attr( $modset ) . '[]'; ?>" type="checkbox" value="<?php echo $id; ?>" <?php if ( in_array( $mod, $activemodules ) ) echo 'checked="checked"'; ?> />
												<span class="hk-toggle"></span>
											</div>
											<span class="modname"><?php echo $title; ?></span><?php /* <label for ="<?php echo $id; ?>"></label> - cretaes issue with js set for .hk-toggle click */ ?>
											<?php
											$descimg = ( file_exists( hootkit()->dir . 'admin/assets/mod-' . sanitize_file_name( $mod ) . '.jpg' ) ) ? hootkit()->uri . 'admin/assets/mod-' . sanitize_file_name( $mod ) . '.jpg' : '';
											if ( $descimg || !empty( $hkmodules[$mod]['desc'] ) ) {
												echo '<span class="mod-desc"><span class="moddescicon">?</span><span class="moddesc">';
													if ( $descimg ) echo '<img src="' . esc_url( $descimg ) . '">';
													if ( !empty( $hkmodules[$mod]['desc'] ) ) echo '<span>' . esc_html( $hkmodules[$mod]['desc'] ) . '</span>';
												echo '</span></span>';
											} ?>
										</div>
									<?php endforeach; ?>
									<?php if ( !empty( $pmodsets[ $modset ] ) ) : foreach ( $pmodsets[ $modset ] as $mod ) :
										$title = esc_html( str_ireplace( 'JNESHK > ', '', hootkit()->get_string( $mod ) ) );
										$hkmodules[$mod]['desc'] = __( 'Premium Feature',  'hootkit' );
										$id = sanitize_html_class( $mod );
										?>
										<div class="hk-mod hk-row hk-disablemod">
											<div class="hk-toggle-box">
												<span class="hk-toggle"></span>
											</div>
											<span class="modname"><?php echo $title; ?></span><?php /* <label for ="<?php echo $id; ?>"></label> - cretaes issue with js set for .hk-toggle click */ ?>
											<?php
											$descimg = ( file_exists( hootkit()->dir . 'admin/assets/mod-' . sanitize_file_name( $mod ) . '.jpg' ) ) ? hootkit()->uri . 'admin/assets/mod-' . sanitize_file_name( $mod ) . '.jpg' : '';
											if ( $descimg || !empty( $hkmodules[$mod]['desc'] ) ) {
												echo '<span class="mod-desc"><span class="moddescicon">?</span><span class="moddesc">';
													if ( $descimg ) echo '<img src="' . esc_url( $descimg ) . '">';
													if ( !empty( $hkmodules[$mod]['desc'] ) ) echo '<span>' . esc_html( $hkmodules[$mod]['desc'] ) . '</span>';
												echo '</span></span>';
											} ?>
										</div>
									<?php endforeach; endif; ?>
								</div>
							</div>
							<?php
						endforeach;
						?>
					</div>

				</form>
			</div><!-- .hootkit-container -->

		</div><!-- .hootkit-wrap -->

		<?php

	}

	/**
	 * Ajax handler for handling settings
	 *
	 * @since  1.1.0
	 * @access public
	 * @return void
	 */
	public function admin_ajax_settings_handler() {
		// Check nonce and permissions
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'hootkitadmin-settings-nonce' ) ) {
			wp_send_json( array( 'setactivemodules' => false, 'msg' => __( 'Invalid request.', 'hootkit' ) ) );
			exit;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json( array( 'setactivemodules' => false, 'msg' => __( 'Insufficient permissions.', 'hootkit' ) ) );
			exit;
		}

		// Set Response
		$response = array();

		// Handle request
		$handle = ( !empty( $_POST['handle'] ) ) ? $_POST['handle'] : '';
		if ( $handle == 'setactivemodules' ) {
			// Sanitize data and create array for storing
			$values = $store = array();
			parse_str( $_POST['values'], $values );
			$modules = hootkit()->get_config( 'modules' );
			foreach ( $values as $modset => $mods ) {
				foreach ( $mods as $mod ) {
					if ( in_array( $mod, $modules ) ) $store[] = $mod;
				}
			}
			// Store new value
			update_option( 'hootkit-activemodules', $store );
			$response['setactivemodules'] = true;
		}

		// Send response.
		wp_send_json( $response );
		exit;
	}

	/**
	 * Returns the instance.
	 *
	 * @since 1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}