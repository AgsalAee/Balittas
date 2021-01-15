<?php 

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) exit;

?>

	<div class="wrap" id="serious-slider-about">
		<h2><?php //echo $this->title; ?></h2>
		<?php
		if ( ! isset( $_REQUEST['add_sample_content'] ) ) $_REQUEST['add_sample_content'] = false;

		if ( $_REQUEST['add_sample_content'] ) {
			if (current_user_can('edit_others_posts')) {
				/* because wp doesn't auto display saved notice on non-options pages */ ?>
				<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
					<p><strong><?php _e('Sample slider created.', 'cryout-serious-slider');?></strong><br>
					<?php _e('Sample content added. Navigate to Slider and Slides sections to see the sample content.', 'cryout-serious-slider') ?></p>
					<button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'cryout-serious-slider' ) ?></span></button>
				</div>
			<?php } else { ?>
				<div class="notice notice-warning is-dismissible">
					<p><?php _e('You do not have sufficient permissions to create sample content.', 'cryout-serious-slider') ?></p>
					<button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'cryout-serious-slider' ) ?></span></button>
				</div>
			<?php } 
		} // endif ?>

		<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">

				<div class="postbox" id="serious-slider-header">
					<img src="<?php echo plugins_url('../resources/images/serious-slider-header.png', __FILE__); ?>" />

					<div id="serious-slider-description"> <?php 
					/**
				     * translating the plugin description would be a waste of resources, 
					 * so this part is not localizable 
					 **/                                     ?>
					
					<h3>Serious Slider is a highly efficient SEO friendly fully translatable free image slider for WordPress.</h3>
					
						<div id="seriousslider-tabs">
							<ul>
								<li><a href="#features">Features</a></li>
								<li><a href="#customization">Customization</a></li>
								<li><a href="#functionality">Functionality</a></li>
							</ul>
							<div id="features">
								<ul>
									<li><strong>Create unlimited sliders with unlimited slides.</strong> The only limit is your imagination on how to use them.</li>
									<li><strong>Add titles, texts, buttons and links to each slide.</strong> All slide texts support HTML tags and even other shortcodes.</li>
									<li><strong>Easy to use media button.</strong> Effortlessly add slideshows in posts, pages or custom post types via the “Add Slider” media button in the WordPress text editor. Then just select your desired image slider from a dropdown list, no need to remember or copy slider IDs</li>
									<li><strong>Auto-generated Shortcodes and PHP integration.</strong> Use the auto generated shortcode to include slideshows with themes or other plugins. Copy-paste the auto generated PHP code to integrate with custom code.</li>
									<li><strong>Serious Slider Widget.</strong> Display slideshows in sidebars via the provided Serious Slider widget</li>
									<li><strong>Use multiple sliders in the same page.</strong></li>
									<li><strong>Familiar admin user interface.</strong> Create sliders and slides with the familiarity of managing posts and categories, without having to learn another user interface</li>
									<li><strong>Lightweight and powerful.</strong> Only minimum JavaScript and CSS3 are being loaded on your site</li>
									<li><strong>Fast slider creation.</strong> Create awesome, responsive WordPress slideshows in a matter of seconds</li>
									<li><strong>Browser compatibility.</strong> The image slider looks and behaves great on various devices and browsers</li>
									<li><strong>7+ Appearance Styles.</strong> Choose from different appearance styles to make the navigation arrows, bullets, buttons and colors match your site.</li>
									<li><strong>7+ Transition Effects.</strong> Fade, Slide, Overslide, Underslide, Parallax, Horizontal flip and Vertical Flip.</li>
									<li><strong>5+ Caption Text Animations.</strong> Choose how caption text appears on the slide: Fade, Slide, Blur and Zoom In/Out.</li>
									<li><strong>Highly customizable.</strong> Customize image sizes, timings, text size and alignment, text shadow, background color and accent color.</li>
									<li><strong>Individual options for each slider.</strong> All the customization options and set individually for every slider.</li>
									<li><strong>Translation ready.</strong> Every single line of text in the slider is translatable both in the front-end as well in the back-end. Compatible with multi-language plugins (WPML, qTranslate, PolyLang).</li>
									<li><strong>SEO friendly.</strong> Built with search engines in mind, the slider uses correct HTML semantics.</li>
									<li><strong>Accessibility ready.</strong></li>
									<li><strong>Once click demo content.</strong> It’s that easy, you’re one click away from a working image slider to get you started.</li>
								</ul>
							</div>
							<div id="customization">
								<ul>
									<li>Add individual URLs to target specific pages</li>
									<li>Add slide buttons with customizable link, link text and “open in new window” option</li>
									<li>Choose how to make text over images more visible: either add text shadow, multiline text background or full caption background</li>
									<li>Choose from 7 slider styles, 7 transition effects and 5 caption text animations</li>
									<li>Customize your slider’s transition duration and delay</li>
									<li>Choose between auto-height and fixed size for your images</li>
									<li>Customize your slider’s font size, text alignment, caption size and accent color</li>
								</ul>						
							</div>
							<div id="functionality">
								<ul>
									<li>Our image slider uses WordPress core functionality only, providing you with the familiar WordPress interface for creating both slides and slides.</li>
									<li>Easily transfer existing slides from one slider to another</li>
									<li>Schedule slides to automatically become visible at any time in the future.</li>
									<li>Quickly restore deleted slides from the Trash</li>
									<li>Use WordPress’ text editor to add HTML content and even shortcodes to your slides</li>
									<li>Bulk edit slides and sliders</li>
								</ul>
							</div>

						</div><!-- tabs-->
					</div><!--description-->
				</div>

			</div> <!-- post-body-content-->

			<div class="postbox-container" id="postbox-container-1">

						<div class="meta-box-sortables">

							<div class="postbox">
								<h3 style="text-align: center;" class="hndle">
									<img id="serious-slider-logo" src="<?php echo plugins_url('../resources/images/serious-slider-128.png', __FILE__); ?>" />
									<span><strong><?php echo $this->title; ?></strong></span>
								</h3>

								<div class="inside">
									<div style="text-align: center; margin: auto">
										<strong><?php printf( __('version: %s','cryout-serious-slider'), $this->version ); ?></strong><br>
										<?php _e('by','cryout-serious-slider') ?> Cryout Creations<br>
										<a class="button button-primary" href="http://www.cryoutcreations.eu/wordpress-plugins/cryout-serious-slider" target="_blank"><?php _e('Plugin Homepage', 'cryout-serious-slider') ?></a>
									</div>
								</div>
							</div>

							<div class="postbox">
								<h3 style="text-align: center;" class="hndle">
									<span><?php _e('Need help?','cryout-serious-slider') ?></span>
								</h3><div class="inside">
									<div style="text-align: center; margin: auto">
										<a class="button button-secondary" href="http://www.cryoutcreations.eu/wordpress-tutorials/create-slider-serious-slider-plugin" target="_blank"><?php _e('Documentation', 'cryout-serious-slider') ?></a>
										<a class="button button-primary" href="http://www.cryoutcreations.eu/priority-support" target="_blank"><?php _e('Priority Support', 'cryout-serious-slider') ?></a>
										<a class="button button-secondary" href="http://www.cryoutcreations.eu/forums/f/wordpress/plugins/serious-slider" target="_blank"><?php _e('Support Forum', 'cryout-serious-slider') ?></a>
									</div>
								</div>
							</div>

							<div class="postbox">
								<h3 style="text-align: center;" class="hndle">
									<span><?php _e('Demo Content','cryout-serious-slider') ?></span>
								</h3>
								<div class="inside">
									<div style="text-align: center; margin: auto">
										<a class="button button-secondary" href="<?php echo $this->aboutpage . '&add_sample_content=1' ?>">
											<?php _e('Create Sample Slider', 'cryout-serious-slider');?>
										</a>
										<p class="description"><small><?php _e('This will create a sample slider with 3 slides which you can use as a basis for your own content.', 'cryout-serious-slider') ?></small></p>
									</div>
								</div> <!--inside-->
							</div> <!--postbox-->

						</div>
			</div> <!-- postbox-container -->

		</div> <!-- post-body -->
		<br class="clear">
		</div> <!-- poststuff -->

	</div><!--end wrap-->
