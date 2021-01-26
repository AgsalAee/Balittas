<?php
// display used featured images if user selected replacement with the selected image
if ( 'replace' == $this->selected_action ) {
	$blank_img_url = includes_url() . 'images/blank.gif';
	$img_ids = is_array( $this->selected_multiple_image_ids ) ? implode( ',', $this->selected_multiple_image_ids ) : '';

	if ( $this->is_error_no_old_image ) {
?>
<h2><?php esc_html_e( 'Notice', 'quick-featured-images' ); ?></h2>
<div class="qfi_content_inside">
	<p class="failure"><?php esc_html_e( 'You did not have selected an image from the list below. To go on select at least one image you want to replace by the selected image.', 'quick-featured-images' ); ?></p>
</div>
<?php 
	} // if( is_error_no_old_image )
?>
<form method="post" action="<?php echo esc_url( admin_url( sprintf( 'admin.php?page=%s&step=confirm', $this->page_slug ) ) ); ?>">
	<h2><?php esc_html_e( 'Select the featured images you want to replace by the selected image.', 'quick-featured-images' ); ?></h2>
	<p><?php esc_html_e( 'You can select multiple images. Select at least one image.', 'quick-featured-images' ); ?></p>
	<p><?php esc_html_e( 'To select multiple images click on the button and use the CTRL key while clicking on the images.', 'quick-featured-images' ); ?></p>
	<p>
		<input type="hidden" id="multiple_image_ids" name="replacement_image_ids" value="<?php echo $img_ids; ?>">
		<img id="blank_image" src="<?php echo $blank_img_url; ?>" alt="" /><br />
		<input type="button" id="select_images_multiple" class="button" value="<?php esc_attr_e( 'Choose Images', 'quick-featured-images' ); ?>" />
	</p>
	<p>
		<input type="hidden" name="image_id" value="<?php echo $this->selected_image_id; ?>" />
		<input type="hidden" name="action" value="<?php echo $this->selected_action; ?>" />
		<?php wp_nonce_field( 'quickfi_refine', $this->plugin_slug . '_nonce' ); ?>
		<input type="submit" class="button" value="<?php esc_attr_e( 'Preview filtering', 'quick-featured-images' ); ?>" />
	</p>
	<input type="hidden" id="selection_advice" name="selection_advice" value="<?php esc_attr_e( 'Use CTRL for multiple choice', 'quick-featured-images' ); ?>" />
</form>
<?php 
} else {
// else display filter selection
?>
<h2><?php esc_html_e( 'Refine your selections', 'quick-featured-images' ); ?></h2>
<p><?php esc_html_e( 'You can control the process with the following options.', 'quick-featured-images' ); ?></p>
<form method="post" action="<?php echo esc_url( admin_url( sprintf( 'admin.php?page=%s&step=refine', $this->page_slug ) ) ); ?>">
<?php
	switch ( $this->selected_action ) {
		case 'assign':
		case 'assign_randomly':
?>
<h3><?php esc_html_e( 'Optional: Select options', 'quick-featured-images' ); ?></h3>
	<fieldset>
		<legend><span><?php esc_html_e( 'Process Options', 'quick-featured-images' ); ?></span></legend>
		<p><?php esc_html_e( 'You can control the process with the following options.', 'quick-featured-images' ); ?></p>
<?php 
			// option for overwriting existing featured images
			$key = 'overwrite';
			$label = $this->valid_options[ $key ];
			$desc = __( 'Overwrite existing featured images with new ones', 'quick-featured-images' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo esc_html( $label ); ?>:</strong> <?php echo esc_html( $desc ); ?></label>
		</p>
<?php 
			// option for posts without featured image
			$key = 'orphans_only';
			$label = $this->valid_options[ $key ];
			$desc = __( 'Posts with featured images will be ignored, even if the Overwrite option is checked', 'quick-featured-images' );
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="options[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_options ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo esc_html( $label ); ?>:</strong> <?php echo esc_html( $desc ); ?></label>
		</p>
<?php
/*			if ( 'assign_randomly' == $this->selected_action ) {
?>
		<p><?php esc_html_e( 'There are two more options in the Pro version for random images:', 'quick-featured-images' ); ?></p>
		<ol>
			<li><?php esc_html_e( 'Use each selected image only once', 'quick-featured-images' ); ?></li>
			<li><?php esc_html_e( 'Remove excess featured images after all selected images are used', 'quick-featured-images' ); ?></li>
		</ol>
<?php
			} // if(assign_randomly) */
?>
	</fieldset>
<?php
			break;
	} // switch( selected_action )
?>
	<h3><?php esc_html_e( 'Optional: Add a filter', 'quick-featured-images' ); ?></h3>
	<fieldset>
		<legend><span><?php esc_html_e( 'Select filters', 'quick-featured-images' ); ?></span></legend>
		<p><?php esc_html_e( 'If you want select one of the following filters to narrow down the set of concerned posts and pages.', 'quick-featured-images' ); ?></p>
		<p><?php esc_html_e( 'You can select multiple filters. They will return an intersection of their results.', 'quick-featured-images' ); ?></p>
<?php 
	foreach ( $this->valid_filters as $key => $label ) {
		switch ( $key ) {
			case 'filter_post_types':
				$desc = __( 'Search by post type. By default all posts and pages will be affected.', 'quick-featured-images' );
				break;
			case 'filter_category':
				$desc = __( 'Search posts by category', 'quick-featured-images' );
				break;
			case 'filter_tag':
				$desc = __( 'Search posts by tag', 'quick-featured-images' );
				break;
			default:
				$desc = '';
		}
?>
		<p>
			<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="filters[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $this->selected_filters ) ); ?>>
			<label for="<?php printf( 'qfi_%s', $key ); ?>"><strong><?php echo esc_html( $label ); ?>:</strong> <?php echo esc_html( $desc ); ?></label>
		</p>
<?php
	} // foreach()
?>
	</fieldset>
	<h3><?php esc_html_e( 'If you encounter a white, blank page, read this', 'quick-featured-images' ); ?></h3>
	<p><?php esc_html_e( 'Facing a white blank page while trying to treat thousands of posts is the effect of limited memory capacities on the website server. Instead of treating a huge amount of posts in one single go try to treat small amounts of posts multiple times successively.', 'quick-featured-images' ); ?></p>
	<p><?php esc_html_e( 'To achieve that use the filters and repeat the process until your task is done. This way is not as fast as one single run, but still much faster than setting the images for each post manually.', 'quick-featured-images' ); ?></p>
<?php
	$text = 'Next';
?>
	<h3><?php esc_html_e( $text ); ?></h3>
	<p><?php esc_html_e( 'On the next page you can refine the filters. If you did not select any filter you will go to the preview list directly.', 'quick-featured-images' ); ?></p>
	<p>
<?php
// remember selected multiple images if there are some
if ( $this->selected_multiple_image_ids ) {
	$v = implode( ',', $this->selected_multiple_image_ids );
?>
		<input type="hidden" name="multiple_image_ids" value="<?php echo $v; ?>" />
<?php
}
	$text = 'Next &raquo;';
?>
		<input type="hidden" name="image_id" value="<?php echo $this->selected_image_id; ?>" />
		<input type="hidden" name="action" value="<?php echo $this->selected_action; ?>" />
		<?php wp_nonce_field( 'quickfi_select', $this->plugin_slug . '_nonce' ); ?>
		<input type="submit" class="button" value="<?php esc_attr_e( $text ); ?>" />
	</p>
</form>
<?php
} // if( 'replace' == action )
