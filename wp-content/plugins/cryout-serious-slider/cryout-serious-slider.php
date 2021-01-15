<?php /*
Plugin Name: Cryout Serious Slider
Plugin URI: https://www.cryoutcreations.eu/wordpress-plugins/cryout-serious-slider
Description: A highly efficient SEO friendly fully translatable accessibility ready free image slider for WordPress. Seriously!
Version: 1.2.0
Author: Cryout Creations
Author URI: https://www.cryoutcreations.eu
Text Domain: cryout-serious-slider
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Cryout_Serious_Slider {

	public $version = "1.2.0";
	public $options = array();
	public $shortcode_tag = 'serious-slider';
	public $mce_tag = 'serious_slider';

	public $slug = 'cryout-serious-slider';
	public $posttype = 'cryout_serious_slide';  // 20 chars!
	public $taxonomy = 'cryout_serious_slider_category';

	private $butts = 2;
	private $title = '';
	private $thepage = '';
	private $aboutpage = '';
	private $addnewpage = '';
	private $plugin_dir = '';

	public $defaults = array(
		'cryout_serious_slider_sort' => 'date', 		// date, order
		'cryout_serious_slider_sizing' => 0, 			// 0 = flexible, 1 = keep max slider size, 2 = force images to slider size
		'cryout_serious_slider_width' => '1920', 		// px
		'cryout_serious_slider_height' => '800', 		// px
		'cryout_serious_slider_responsiveness' => 'maintain', // 'legacy', 'maintain'
		'cryout_serious_slider_hidetitles' => 0, // 1 = hide

		'cryout_serious_slider_theme' => 'light',		// light, light2, dark, square, tall, captionleft, captionbottom, theme
		'cryout_serious_slider_overlay' => 1, 			// 1 = autohide, 2 = visible
		'cryout_serious_slider_textsize' => '1.0', 		// em
		'cryout_serious_slider_align' => 'center', 		// left, center, right, justify
		'cryout_serious_slider_caption_width' => '1030',// px
		'cryout_serious_slider_textstyle' => 'textshadow',// none, textshadow, bgcolor
		'cryout_serious_slider_accent' => '#2D939F',	// color code

		'cryout_serious_slider_autoplay' => 1, 			// 1 = autoplay
		'cryout_serious_slider_animation' => 'slide', 	// fade, slide, overslide, underslide, parallax, hflip, vflip
		'cryout_serious_slider_hover' => 'hover', 		// hover, false
		'cryout_serious_slider_delay' => 5000,			// ms
		'cryout_serious_slider_transition' => 1000,		// ms
		'cryout_serious_slider_captionanimation' => 'slide'	// ms
	);

	public function __construct(){

		// plugin variables
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->plugin_url = plugin_dir_url( __FILE__ );

		// plugin externals
		require_once( $this->plugin_dir . 'inc/helpers.php' );
		require_once( $this->plugin_dir . 'inc/shortcodes.php' );
		require_once( $this->plugin_dir . 'inc/widgets.php' );

		$this->sanitizer = new Cryout_Serious_Slider_Sanitizers;

		// plugin init
		add_action( 'init', array( $this, 'register' ) );

		// cpt and taxonomy
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'setup_theme', array( $this, 'register_taxonomies' ) );
		
		// force support in polylang
		add_filter( 'pll_get_post_types', array( $this, 'pll_post_types' ) );
		add_filter( 'pll_get_taxonomies', array( $this, 'pll_taxonomies' ) );
		
		// disable wp auto-p on cpt - turned off in 1.0.4
		// add_filter( 'the_post', array( $this, 'autop_control' ) );

		// create slides from media images
		add_action( 'created_term', array( $this, 'generate_slider' ), 10, 3 );

	} // __construct()

	/**
	 * handles slider+slides generation via
	 * the quick image selection feature
	 */
	public function generate_slider( $term_id, $tt_id, $taxonomy ) {
		if ( !empty($_POST['cryout_serious_slider_imagelist']) ) {
			$image_list = esc_attr( $_POST['cryout_serious_slider_imagelist'] );
			$image_list = explode( ',', $image_list );
			foreach ($image_list as $image_id) {
				// fetch image info
				$metadata = get_post( $image_id );
				if ( $metadata ) {
					$post = array(
						'post_title' => $metadata->post_title,
						'post_content' => ( !empty( $metadata->post_excerpt ) ? $metadata->post_excerpt : $metadata->post_content ),
						'post_status' => 'publish',
						'post_type' => 'cryout_serious_slide',
						'menu_order' => 1,
					);
					// create sample slide
					$pid = wp_insert_post( $post );
					// add featured image
					set_post_thumbnail( $pid, $image_id );
					// assign slide to slider 'category'
					wp_set_object_terms($pid, $term_id, $taxonomy, true);
				}
			} // foreach
		} // if
	} // generate_slider()


	/**********************
	* main class registration function
	***********************/
	public function register(){

		$this->title = __( 'Serious Slider', 'cryout-serious-slider' );
		$this->aboutpage = 'edit.php?post_type=' . $this->posttype . '&page=' . $this->slug . '-about';
		$this->addnewpage = 'post-new.php?post_type=' . $this->posttype;
		
		// slider buttons filter support
		$this->butts = apply_filters( 'cryout_serious_slider_buttoncount', $this->butts );

		if (! is_admin() ) {
			// frontend script and style
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		} // if (! is_admin())

		if (is_admin() ) {

			// plugin info hooks
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'actions_links' ), -10 );
			add_filter( 'plugin_row_meta', array( $this, 'meta_links' ), 10, 2 );
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );

			// slides list page columns customizations
			add_filter( 'manage_edit-'.$this->posttype.'_columns', array($this, 'columns_edit' ) );
			add_action( 'manage_'.$this->posttype.'_posts_custom_column', array($this, 'columns_content'), 10, 2 );
			add_filter( 'manage_edit-'.$this->posttype.'_sortable_columns', array($this, 'order_column_register_sortable') );
			
			// taxonomy list columns customizations
			add_filter( 'manage_edit-'.$this->taxonomy.'_columns', array($this, 'columns_edit_taxonomy' ) );
			add_action( 'manage_'.$this->taxonomy.'_custom_column', array($this, 'custom_content_taxonomy' ), 10, 3);
			add_action( 'restrict_manage_posts', array($this, 'add_taxonomy_filters') );

			// meta
			add_action( 'save_post', array($this, 'metabox_save') );

			// shortcode button
			add_action( 'admin_head', array( $this, 'admin_head') );
			add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );

			// mce slider button
			add_action( 'media_buttons', array( $this, 'media_slider_button' ) );
			$localized_mce_strings = array(
				'text_retrieving_sliders' => __('Retrieving sliders...', 'cryout-serious-slider'),
				'text_retrieving_sliders_error' => __('Error retrieving sliders', 'cryout-serious-slider'),
				'text_serious_slider' => __('Cryout Serious Slider', 'cryout-serious-slider'),
				'text_serious_slider_tooltip' => __('Serious Slider', 'cryout-serious-slider'),
				'text_insert_slider' => __('Insert Slider', 'cryout-serious-slider'),
				'text_cancel' => __('Cancel', 'cryout-serious-slider'),
				'text_select_slider' => __('Select Slider', 'cryout-serious-slider'),
				'text_add_slider' => __('Add Slider', 'cryout-serious-slider'),
				'nonce' => wp_create_nonce( 'cryout-sslider-column-image' ),
			);

			wp_enqueue_script( 'cryout-serious-slider', plugins_url( 'resources/backend.js', __FILE__ ), array('wp-color-picker'), $this->version );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_localize_script( 'cryout-serious-slider', 'cryout_serious_slider_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_localize_script( 'cryout-serious-slider', 'CRYOUT_MCE_LOCALIZED', $localized_mce_strings );

			// ajax handling for slider parameters in shortcode button generator
			add_action( 'wp_ajax_cryout_serious_slider_ajax', array( $this, 'get_sliders_json' ) ); // auth users
			add_action( 'wp_ajax_nopriv_cryout_serious_slider_ajax', array( $this, 'get_sliders_json' ) ); // no auth users

			// ajax handling for slider image
			add_action( 'wp_ajax_cryout_serious_slider_set_image', array( $this, 'ajax_set_image' ) );
			add_action( 'wp_ajax_cryout_serious_slider_delete_image', array( $this, 'ajax_delete_image' ) );
			
			// privacy policy info
			add_action( 'admin_init', array( $this, 'privacy_content') );


		} // if (is_admin())

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

	} // register()


	/**********************
	* translation domain
	***********************/
	function load_textdomain() {
		load_plugin_textdomain( 'cryout-serious-slider', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**********************
	* frontend enqueues
	***********************/
	public function enqueue_scripts() {
		wp_enqueue_script( 'cryout-serious-slider-jquerymobile', plugins_url( 'resources/jquery.mobile.custom.min.js', __FILE__ ), array('jquery'), $this->version );
		wp_enqueue_script( 'cryout-serious-slider-script', plugins_url( 'resources/slider.js', __FILE__ ), NULL, $this->version );
	} // enqueue_scripts()

	public function enqueue_styles() {
		wp_register_style( 'cryout-serious-slider-style', plugins_url( 'resources/style.css', __FILE__ ), NULL, $this->version );
		wp_enqueue_style( 'cryout-serious-slider-style' );
	} // enqueue_styles()


	/**********************
	* plugin page
	***********************/

	// register about page to dashboard menu
	public function settings_menu() {
		$this->thepage = add_submenu_page( 'edit.php?post_type='.$this->posttype, __('About', 'cryout-serious-slider'), __('About', 'cryout-serious-slider'), 'edit_others_posts', $this->slug . '-about', array( $this, 'plugin_page' ) );
	} // settings_menu()

	// about page callback
	public function plugin_page() {
		if (!empty($_GET['add_sample_content'])&&current_user_can('edit_others_posts'))
			include_once( $this->plugin_dir . 'demo/demo-content.php' );
		require_once( $this->plugin_dir . 'inc/about.php' );
	} // plugin_page()

	// add plugin actions links
	public function actions_links( $links ) {
		array_unshift( $links, '<a href="' . $this->aboutpage . '">' . __( 'About Plugin', 'cryout-serious-slider' ) . '</a>' );
		return $links;
	}

	// add plugin meta links
	public function meta_links( $links, $file ) {
		// Check plugin
		if ( $file === plugin_basename( __FILE__ ) ) {
			unset( $links[2] );
			$links[] = '<a href="http://www.cryoutcreations.eu/cryout-serious-slider/" target="_blank">' . __( 'Plugin homepage', 'cryout-serious-slider' ) . '</a>';
			$links[] = '<a href="https://www.cryoutcreations.eu/forums/f/wordpress/plugins/serious-slider" target="_blank">' . __( 'Support forum', 'cryout-serious-slider' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/plugins/cryout-serious-slider/#developers" target="_blank">' . __( 'Changelog', 'cryout-serious-slider' ) . '</a>';
		}
		return $links;
	}


	/**********************
	* helpers
	***********************/

	/* return taxonomy id for slide id */
	public function get_slide_slider( $slide_ID, $taxonomy = '') {
		if (empty($taxonomy)) $taxonomy = $this->taxonomy;
		$tax = wp_get_object_terms( $slide_ID, $taxonomy );
		if (!empty($tax))
			return $tax[0]->term_id;
		else
			return 0;
	} // get_slide_slider()

	/* return sliders list for mce insert window */
	public function get_sliders_json() {
		$sliders = $this->get_sliders();
		echo json_encode($sliders);
		wp_die();
	} // get_sliders_json()

	/* prototype slider retrieval function */
	public function get_sliders() {
		$data = get_terms( $this->taxonomy, array( 'hide_empty' => false ) );

		$sliders = array();
		foreach ($data as $slider) {
			$sliders[] = array('text'=>$slider->name, 'value'=>$slider->term_id);
		}

		if (count($sliders)<1) $sliders = array( array('text' => __('No sliders available. Create a slider first...', 'cryout-serious-slider'), 'value' => 0) );

		return $sliders;
	} // get_sliders()

	/* theme compatibility function */
	public function get_sliders_list() {
		$data = get_terms( $this->taxonomy, array( 'hide_empty' => false ) );

		$sliders = array();
		foreach ($data as $slider) {
			if (!empty($slider->term_id)) $sliders[$slider->term_id] = $slider->name;
		}
		return $sliders;
	} // get_sliders_list()

	/* customize taxonomy selection box in add slide window */
	function custom_category_picker( $post, $box ) {
		$defaults = array( 'taxonomy' => 'category' );
		if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
			$args = array();
		} else {
			$args = $box['args'];
		}
		$r = wp_parse_args( $args, $defaults );
		$tax_name = esc_attr( $r['taxonomy'] );
		$taxonomy = get_taxonomy( $r['taxonomy'] );
		?>
		<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
				<?php
				$name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
				?>
				<ul id="<?php echo $tax_name; ?>_selector" data-wp-lists="list:<?php echo $tax_name; ?>" class="form-no-clear">
					<?php
						$cat_dropdown_args = array(
							'taxonomy'         => $tax_name,
							'hide_empty'       => 0,
							'name'             => 'tax_input[' . $tax_name . ']',
							'orderby'          => 'name',
							'selected'		   => $this->get_slide_slider( $post->ID ),
							'hierarchical'     => 0,
							'show_option_none' => '&mdash; ' . __('Select slider', 'cryout-serious-slider') . ' &mdash;',
						);

						wp_dropdown_categories( $cat_dropdown_args );
					?>
				</ul>
				<a class="taxonomy-add-new" href="edit-tags.php?taxonomy=<?php echo $this->taxonomy ?>&post_type=<?php echo $this->posttype; ?>" id=""><?php _e(
				'Manage Sliders', 'cryout-serious-slider') ?></a>
		</div>
		<?php
	} // slide_custom_category()

    // set slide image via ajax
    public function ajax_set_image() {

		if ( ! isset( $_POST[ 'cryout_sslider_column_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'cryout_sslider_column_nonce' ], 'cryout-sslider-column-image' ) ) {
			die( __( 'Sorry, you are not allowed to edit this item.', 'cryout-serious-slider' ) );
		}
		if ( isset( $_POST[ 'post_id' ] ) && isset( $_POST[ 'thumbnail_id' ] ) ) {
			// sanitze ids
			$post_id		= absint( $_POST[ 'post_id' ][ 0 ] );
			$thumbnail_id	= absint( $_POST[ 'thumbnail_id' ] );
			// try to set thumbnail; returns true if successful
			$success = set_post_thumbnail( $post_id, $thumbnail_id );
			if ( $success ) {

				$post_title = _draft_or_post_title( $post_id );
				// image selection link
				$html .= sprintf(
					'<a href="%1$s" id="sslide_set_%2$s" class="sslide_set_link" title="%3$s">%4$s<br />%5$s</a>',
					esc_url( get_upload_iframe_src( 'image', $post_id ) ),
					$post_id,
					esc_attr( sprintf( __( 'Change image for "%s"', 'cryout-serious-slider' ), $post_title ) ),
					get_the_post_thumbnail( $post_id, 'thumbnail' ),
					esc_html( __( 'Change Image', 'cryout-serious-slider' ) )
				);

				// 'remove' image link
				$html .= sprintf(
					'<br><a href="#" id="sslide_delete_%1$s" class="sslide_delete_link hide-if-no-js" title="%2$s">%3$s</a>',
					$post_id,
					esc_attr( sprintf( __( 'Remove image from "%s"', 'cryout-serious-slider' ), $post_title ) ),
					esc_html( __( 'Remove Image', 'cryout-serious-slider') )
				);

				// return response to Ajax script
				echo $html;

			} else {
				// return error message to Ajax script
				esc_html_e( 'Item not added.', 'cryout-serious-slider' );
			}
		}
		die();
    } // ajax_set_image()

    // remove slider image via ajax
    public function ajax_delete_image() {
		if ( ! isset( $_POST[ 'cryout_sslider_column_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'cryout_sslider_column_nonce' ], 'cryout-sslider-column-image' ) ) {
			die( __( 'Sorry, you are not allowed to edit this item.', 'cryout-serious-slider' ) );
		}
		if ( isset( $_POST[ 'post_id' ] ) ) {
			// sanitze post id
			$post_id = absint( $_POST[ 'post_id' ][ 0 ] );
			// try to delete thumbnail; returns true if successful
			$success = delete_post_thumbnail( $post_id );
			if ( $success ) {

				// 'set thumbnail' link
				$html = sprintf(
					'%5$s<br><a href="%1$s" id="sslide_set_%2$s" class="sslide_set_link" title="%3$s">%4$s</a>',
					esc_url( get_upload_iframe_src( 'image', $post_id ) ),
					$post_id,
					esc_attr( sprintf( __( 'Set image for "%s"', 'quick-featured-images' ), _draft_or_post_title( $post_id ) ) ),
					esc_html( __( 'Set Image', 'cryout-serious-slider' ) ),
					__( 'None', 'cryout-serious-slider' )
				);

				// return response to Ajax script
				echo $html;

			} else {
				// return error message to Ajax script
				$text = 'Item not updated.';
				esc_html_e( $text );
			}
		}
		die();
    } // ajax_delete_image()

	/* removes autop filtering from the slider's cpt - disabled in 1.0.4 */
	function autop_control( $post ) {
		if( $this->posttype === $post->post_type ) {
			remove_filter( 'the_content', 'wpautop' );
		}
	} // autop_control()


	/**********************
	* custom post types
	***********************/
	public function register_post_types() {

		/* Set up arguments for the custom post type. */
		$args = array(
			'public' 			=> false,
			'show_ui'			=> true,
			'show_admin_column' => true,
			'show_in_admin_bar'	=> true,
			'query_var' 		=> true,
			'description' 		=> __( 'Description.', 'cryout-serious-slider' ),
			'show_in_nav_menus' => false,
			'menu_position' 	=> 21,
			'menu_icon' 		=> plugins_url('/resources/images/serious-slider-icon.svg',__FILE__),
			'capability_type' 	=> 'page',
			'supports' 			=> array(
					'title',
					'editor',
					//'excerpt',
					'thumbnail',
					'page-attributes',
			),
			'labels' 				=> array(
					'name'               => _x( 'Slides', 'post type general name', 'cryout-serious-slider' ),
					'singular_name'      => _x( 'Slide', 'post type singular name', 'cryout-serious-slider' ),
					'menu_name'          => _x( 'Serious Slider', 'admin menu', 'cryout-serious-slider' ),
					'name_admin_bar'     => _x( 'Serious Slide', 'add new on admin bar', 'cryout-serious-slider' ),
					'add_new'            => _x( 'Add New Slide', 'and new in menu', 'cryout-serious-slider' ),
					'add_new_item'       => __( 'Add New Slide', 'cryout-serious-slider' ),
					'new_item'           => __( 'New Slide', 'cryout-serious-slider' ),
					'edit_item'          => __( 'Edit Slide', 'cryout-serious-slider' ),
					'view_item'          => __( 'View Slide', 'cryout-serious-slider' ),
					'all_items'          => __( 'All Slides', 'cryout-serious-slider' ),
					'search_items'       => __( 'Search Slide', 'cryout-serious-slider' ),
					'parent_item_colon'  => __( 'Parent Slides:', 'cryout-serious-slider' ),
					'item_published'	 => __( 'Slide published.', 'cryout-serious-slider' ),
					'item_published_privately' => __( 'Slide published privately.', 'cryout-serious-slider' ),
					'item_reverted_to_draft'   => __( 'Slide reverted to draft.', 'cryout-serious-slider' ),
					'item_scheduled'     => __( 'Slide scheduled.', 'cryout-serious-slider' ),
					'item_updated'       => __( 'Slide updated.', 'cryout-serious-slider' ),
					'not_found'          => sprintf( __( 'No slides found. Go ahead and <a href="%1$s">add some</a> or <a href="%2$s">load sample content</a>.', 'cryout-serious-slider' ), $this->addnewpage, $this->aboutpage ),
					'not_found_in_trash' => __( 'No slides found in Trash.', 'cryout-serious-slider' )
			),
			'taxonomies' 			=> array(
					$this->taxonomy,
			),
			'register_meta_box_cb' 	=> array( $this, 'metabox_register' ),
		);

		/* Register the post type. */
		register_post_type( $this->posttype, $args );

	} // register_post_types()

	/* Set up custom taxonomies for the custom post type */
	public function register_taxonomies() {

		$cat_args = array(
			'public'			=> false,
			'hierarchical'      => true,
			'labels'            => array(
					'name'              => _x( 'Sliders', 'taxonomy general name', 'cryout-serious-slider' ),
					'singular_name'     => _x( 'Slider', 'taxonomy singular name', 'cryout-serious-slider' ),
					'search_items'      => __( 'Search Sliders', 'cryout-serious-slider' ),
					'all_items'         => __( 'All Sliders', 'cryout-serious-slider' ),
					'parent_item'       => __( 'Parent Slider', 'cryout-serious-slider' ),
					'parent_item_colon' => __( 'Parent Slider:', 'cryout-serious-slider' ),
					'edit_item'         => __( 'Edit Slider', 'cryout-serious-slider' ),
					'update_item'       => __( 'Update Slider', 'cryout-serious-slider' ),
					'add_new_item'      => __( 'Add New Slider', 'cryout-serious-slider' ),
					'new_item_name'     => __( 'New Slider', 'cryout-serious-slider' ),
					'menu_name'         => __( 'Manage Sliders', 'cryout-serious-slider' ),
					'not_found'         => __( 'No sliders found', 'cryout-serious-slider' ),
					'no_terms'			=> __( 'No sliders', 'cryout-serious-slider' ),
					'back_to_items'     => __( '&larr; Back to Sliders', 'cryout-serious-slider' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,

			'meta_box_cb' => array( $this, 'custom_category_picker' ), // customize taxonomy box selector
		);

		register_taxonomy( $this->taxonomy, array( $this->posttype ), $cat_args );
		add_action( $this->taxonomy . '_add_form_fields', array($this, 'metatax_main_add'), 10, 2 );
		add_action( $this->taxonomy . '_edit_form_fields', array($this, 'metatax_main_edit'), 10, 2 );
		add_action( $this->taxonomy . '_edit_form', array($this, 'right_column'), 10, 2 ); // _pre_edit_form // _edit_form

		add_action( 'edited_' . $this->taxonomy, array($this, 'save_taxonomy_custom_meta'), 10, 2 );
		add_action( 'create_' . $this->taxonomy, array($this, 'save_taxonomy_custom_meta'), 10, 2 );
		add_action( 'delete_' . $this->taxonomy, array($this, 'delete_taxonomy_custom_meta'), 10, 2 );

	} // register_taxonomies()
	
	/**
	 * Register post type in Polylang
	 */
	public function pll_post_types( $post_types ){
		return array_merge( $post_types, array( $this->posttype ) );
	} // pll_post_types()
	/**
	 * Register taxonomy in Polylang
	 */
	public function pll_taxonomies( $taxonomies ){
		return array_merge( $taxonomies, array( $this->taxonomy ) );
	} // pll_taxonomies()

	/**********************
	* dashboard layout customization
	***********************/
	public function columns_edit( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'cryout-serious-slider' ),
			$this->taxonomy => __( 'Slider', 'cryout-serious-slider' ),
			'featured_image' => __( 'Slide Image', 'cryout-serious-slider' ),
			'date' => __( 'Date', 'cryout-serious-slider' ),
			'menu_order' => __( 'Order', 'cryout-serious-slider' ),
		);
		return $columns;
	} // columns_edit()

	// Show the featured image & taxonomy in posts list
	public function columns_content($column_name, $post_id) {
	global $post;
	$post_id = $post->ID;

		switch ($column_name) {

			case $this->taxonomy:

				$terms = get_the_terms( $post->ID, $this->taxonomy );
				if ( !empty( $terms ) ) {

					$out = array();
					foreach ( $terms as $term ) {
						$out[] = sprintf( '<a href="%1$s">%2$s</a><div class="row-actions"><span class="edit"><a href="%3$s">%4$s</a></span></div>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, $this->taxonomy => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->taxonomy, 'display' ) ),
							esc_url( add_query_arg( array(  'action' => 'edit', 'taxonomy' => $this->taxonomy, 'tag_ID' => $term->term_id, 'post_type' => $post->post_type ), 'edit-tags.php' ) ),
							__('Edit slider', 'cryout-serious-slider')
						);

					}
					echo join( ', ', $out );

				}

				else {
					_e( 'None', 'cryout-serious-slider' );
				}

			break;

			case 'featured_image':

				$thumbnail_id = get_post_thumbnail_id( $post_id );
				// check if image file exists, omit filters in get_attached_file() ('true')
				if ( $thumbnail_id ) {
					if ( $thumb = wp_get_attachment_image( $thumbnail_id, 'thumbnail' ) ) {
						if ( current_user_can( 'edit_others_posts', $thumbnail_id ) ) {
							$post_title = _draft_or_post_title( $post_id );
							// image selection link
							printf(
								'<a href="%1$s" id="sslide_set_%2$s" class="sslide_set_link" title="%3$s">%4$s<br />%5$s</a>',
								esc_url( get_upload_iframe_src( 'image', $post_id ) ),
								$post_id,
								esc_attr( sprintf( __( 'Change image for "%s"', 'cryout-serious-slider' ), $post_title ) ),
								$thumb,
								esc_html( __( 'Change', 'cryout-serious-slider' ) )
							);

							// 'remove' image link
							printf(
								' / <a href="#" id="sslide_delete_%1$s" class="sslide_delete_link hide-if-no-js" title="%2$s">%3$s</a>',
								$post_id,
								esc_attr( sprintf( __( 'Remove image from "%s"', 'cryout-serious-slider' ), $post_title ) ),
								esc_html( __( 'Remove', 'cryout-serious-slider') )
							);
						} else {
							// if no edit capatibilities show image only
							echo $thumb;
						} // if user can
					} // if thumb
				} else {
					// no featured image set
					if ( current_user_can( 'edit_others_posts' ) ) {
						printf(
							'%5$s<br><a href="%1$s" id="sslide_set_%2$s" class="sslide_set_link" title="%3$s">%4$s</a>',
							esc_url( get_upload_iframe_src( 'image', $post_id ) ),
							$post_id,
							esc_attr( sprintf( __( 'Set image for "%s"', 'quick-featured-images' ), _draft_or_post_title( $post_id ) ) ),
							esc_html( __( 'Set Image', 'cryout-serious-slider' ) ),
							__( 'None', 'cryout-serious-slider' )
						);
					} // if user can
				} // if thumbnail_id

			break;

			case 'menu_order':

				$order = $post->menu_order;
				echo $order;

			break;
		}
	} // columns_content()

	/* Add sort by columns support */
	public function order_column_register_sortable($columns){
	  $columns['menu_order'] = 'menu_order';
	  $columns[$this->taxonomy] = $this->taxonomy;
	  return $columns;
	} // order_column_register_sortable()

	/* Add shortcode column in taxonomy screen */
	public function columns_edit_taxonomy( $columns ){
		return array_merge(
			array_splice( $columns, 0, count($columns)-1 ),
			array( 'shortcode' => __('Shortcode', 'cryout-serious-slider') ),
			array_splice( $columns, count($columns)-1, 1 )
		);
	} // columns_edit_taxonomy()

	public function custom_content_taxonomy( $empty, $column, $id ) {
		switch ($column) {
			case 'shortcode':
				echo '[serious-slider id="' . $id . '"]';
				break;
			default:
				break;
			} // end switch
	} // custom_content_taxonomy()

	/* filter cpt by taxonomy */
	function add_taxonomy_filters() {
		global $typenow;

		$taxonomies = array( $this->taxonomy );

		// must set this to the post type you want the filter(s) displayed on
		if( $typenow == $this->posttype ){

			foreach ($taxonomies as $tax_slug) {
				$tax_obj = get_taxonomy($tax_slug);
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if (!empty($_GET[$tax_slug])) $filtered_tax = sanitize_text_field($_GET[$tax_slug]); else $filtered_tax = '';
				if(count($terms) > 0) {
					echo "<select name='$tax_slug' id='filter_$tax_slug' class='postform'>";
					printf( "<option value=''>%s</option>", sprintf( _x('Select %s', 'select terms', 'cryout-serious-slider'), $tax_name ) );
					foreach ($terms as $term) {
						echo '<option value='. $term->slug, $filtered_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	} // add_taxonomy_filters()

	// add right column content (with shortcode hint) on edit slider page */
	function right_column( $tag, $taxonomy ) {
		$term_ID = $tag->term_id;
		$term_slug = $tag->slug;
		include_once( $this->plugin_dir . 'inc/right-column.php' );
	} // right_column()


	/**********************
	* slide meta
	***********************/
	/* Custom post types metaboxes */
	function metabox_register() {
	    add_meta_box('serious_slider_metaboxes', __( 'Slide Properties', 'cryout-serious-slider' ), array($this, 'metabox_main'), $this->posttype, 'normal', 'high');
	} // metabox_register()

	function metabox_main() {
	    global $post;
		$values = get_post_custom( $post->ID );
		$text = isset( $values['cryout_serious_slider_link'] ) ? $values['cryout_serious_slider_link'][0] : '';
		$check = isset( $values['cryout_serious_slider_target'] ) ? esc_attr( $values['cryout_serious_slider_target'][0] ) : '';

		for ($i=1;$i<=$this->butts;$i++) {
			${'button'.$i} = isset( $values['cryout_serious_slider_button'.$i] ) ? esc_attr( $values['cryout_serious_slider_button'.$i][0] ) : '';
			${'button'.$i.'_url'} = isset( $values['cryout_serious_slider_button'.$i.'_url'] ) ? esc_attr( $values['cryout_serious_slider_button'.$i.'_url'][0] ) : '';
			${'button'.$i.'_target'} = isset( $values['cryout_serious_slider_button'.$i.'_target'] ) ? esc_attr( $values['cryout_serious_slider_button'.$i.'_target'][0] ) : '';
		}

		require_once( $this->plugin_dir . 'inc/meta.php' );

	} // metabox_main()

	function metabox_save( $post_id ) {

		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if( !isset( $_POST['cryout_serious_slider_meta_nonce'] ) || !wp_verify_nonce( $_POST['cryout_serious_slider_meta_nonce'], 'cryout_serious_slider_meta_nonce' ) ) return;
		if( !current_user_can( 'edit_post' ) ) return;
		$allowed = '';

		// main slide image link & target
		if( isset( $_POST['cryout_serious_slider_link'] ) )
			update_post_meta( $post_id, 'cryout_serious_slider_link', esc_url_raw( $_POST['cryout_serious_slider_link'], $allowed ) );
		$chk = isset( $_POST['cryout_serious_slider_target'] );
		update_post_meta( $post_id, 'cryout_serious_slider_target', $chk );

		// buttons, links and targets
		for ($i=1;$i<=$this->butts;$i++) {
			if ( isset( $_POST['cryout_serious_slider_button'.$i] ) )
				update_post_meta( $post_id, 'cryout_serious_slider_button'.$i, esc_attr( $_POST['cryout_serious_slider_button'.$i] ) );
			if ( isset( $_POST['cryout_serious_slider_button'.$i.'_url'] ) )
				update_post_meta( $post_id, 'cryout_serious_slider_button'.$i.'_url', esc_url_raw( $_POST['cryout_serious_slider_button'.$i.'_url'], $allowed ) );
			${'chk_btn'.$i} = isset( $_POST['cryout_serious_slider_button'.$i.'_target'] );
			update_post_meta( $post_id, 'cryout_serious_slider_button'.$i.'_target', ${'chk_btn'.$i} );
		}

	} // metabox_save()


	/**********************
	* slider/taxonomy meta
	***********************/
	public function metatax_main_add() {

		$the_meta = $this->defaults;
		require_once( $this->plugin_dir . 'inc/taxmeta.php' );

	} // metabox_main_add()

	public function metatax_main_edit($term) {

		$tid = $term->term_id;
		$the_meta = get_option( "cryout_serious_slider_${tid}_meta" );
		$the_meta = wp_parse_args( $the_meta, $this->defaults ); ?>
		<tr class="form-field">
			<td colspan="2">
		<?php require_once( $this->plugin_dir . 'inc/taxmeta.php' );?>
		</td>
		</tr><?php

	} // metatax_main_edit()

	function save_taxonomy_custom_meta( $tid ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$term_meta = get_option( "cryout_serious_slider_${tid}_meta" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = sanitize_text_field($_POST['term_meta'][$key]);
				}
			}
			// Save the option array.
			update_option( "cryout_serious_slider_${tid}_meta", $term_meta );
		}
	} // save_taxonomy_custom_meta()

	function delete_taxonomy_custom_meta( $term_id ) {
		delete_option( "cryout_serious_slider_${term_id}_meta" );
	} // delete_taxonomy_custom_meta()


	/**********************
	* mce extension
	***********************/

    // media button
    public function media_slider_button() {

        if ( ! current_user_can( 'edit_others_posts' ) ) {
            return;
        }

		global $post_type;
        global $pagenow;

        if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && ( $this->posttype != $post_type ) ) {
            $content = sprintf('<a href="#" class="button media-serious-slider-button" title="%1$s"> <span class="wp-media-buttons-icon" style="background: url(\'%2$s\'); background-repeat: no-repeat; background-position: center 1px;"></span> %3$s </a>', 
				__( "Insert Serious Slider into post", "cryout-serious-slider" ),
				$this->plugin_url . 'resources/images/serious-slider-editor-icon.png',
				__( 'Add Slider', 'cryout-serious-slider' ) );
			echo $content; 
        }

    } // media_slider_button()

	// mce button
	function admin_head() {
		global $post_type;
		global $pagenow;

		// don't allow slider shortcode inside slide posts
		if( $this->posttype != $post_type && in_array( $pagenow, array( 'edit.php', 'post-new.php', 'post.php' ) ) ) {
			// check user permissions
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}

			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this ,'register_mce_external_plugins' ) );
				add_filter( 'mce_buttons_2', array( $this, 'regiter_mce_buttons' ) );
			}
		}
	} // admin_head()

	function register_mce_external_plugins( $plugin_array ) {
		$plugin_array[$this->mce_tag] = plugins_url( 'resources/mce-button.js' , __FILE__ );
		return $plugin_array;
	} // register_mce_external_plugins()

	function regiter_mce_buttons( $buttons ) {
		array_push( $buttons, $this->mce_tag );
		return $buttons;
	} // regiter_mce_buttons()

	/* admin styles and scripts */
	function admin_enqueue_scripts($hook){
		global $post_type;
		global $pagenow;
		// slides
		if ( in_array( $pagenow, array( 'edit.php', 'post-new.php', 'post.php' ) ) ) {
			wp_enqueue_style('serious-slider-shortcode', plugins_url( 'resources/mce-button.css' , __FILE__ ), NULL, $this->version );
			wp_enqueue_media();
		};
		// slides, sliders or plugin about page
		if( ($hook == $this->thepage) || ( $this->posttype == $post_type ) ) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style('serious-slider-admincss', plugins_url( 'resources/backend.css' , __FILE__ ), NULL, $this->version );
			wp_enqueue_media();
		};
	} // admin_enqueue_scripts()
	
    public function privacy_content(){
	    if ( function_exists('wp_add_privacy_policy_content') ) {
		    wp_add_privacy_policy_content(
		    	__('Cryout Serious Slider','cryout-serious-slider'),
			    __("This plugin doesn't collect any kind of data from either website visitors or administrators.",'cryout-serious-slider')
		    );
	    }
    } // privacy_content()


	/**********************
	* form helpers
	***********************/
	function inputfield( $id, $current, $title='', $desc='', $class='', $extra='', $extra2='', $type='number' ) {
	/* wordpress/wp-admin/js/tags.js empties all text input elements with
	   $('input[type="text"]:visible, textarea:visible', form).val('');
	   on form submit as of 4.4.2; using type="number" by default as workaround */
 		?>
		<div class="seriousslider-option seriousslider-option-input">
			<span><?php echo $title ?></span>
			<input id="<?php echo $id ?>" name="<?php echo $id ?>" class="<?php echo $class ?>" type="<?php echo $type ?>" value="<?php echo $current ?>" <?php echo $extra2 ?>> <?php echo $extra ?>
			<p class="description"><?php echo $desc ?></p>
		</div>

	<?php
	} // inputfield()
	function selectfield( $id, $options=array(), $current, $title='', $desc='', $class='', $extra='' ) { ?>
		<div class="seriousslider-option seriousslider-option-input">
			<span><?php echo $title ?></span>
			<select id="<?php echo $id ?>" name="<?php echo $id ?>" class="<?php echo $class ?>">
				<?php foreach ($options as $value => $label) { ?>
						<option value="<?php echo $value ?>" <?php selected( $current, $value); ?>><?php echo $label ?></option>
				<?php } ?>
			</select>
			<p class="description"><?php echo $desc ?></p>
		</div>
	<?php
	} // selectfield()

	function titlefield( $text ) {
		echo $text;
	} /// titlefield()

} // class Cryout_Serious_Slider

/* * * * get things going * * * */
$cryout_serious_slider = new Cryout_Serious_Slider;

// EOF
