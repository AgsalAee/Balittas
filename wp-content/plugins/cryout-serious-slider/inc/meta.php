<?php 

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) exit;

?>

		<?php wp_nonce_field( 'cryout_serious_slider_meta_nonce', 'cryout_serious_slider_meta_nonce' ); ?>

		<?php for ($i=1;$i<=$this->butts;$i++) { ?>
		<p>
			<label for="cryout_serious_slider_button<?php echo $i ?>"><?php printf( __('Button %s Label:', 'cryout-serious-slider'), $i ) ?></label>
			<input type="text" size="30" name="cryout_serious_slider_button<?php echo $i ?>" id="cryout_serious_slider_button<?php echo $i ?>" value="<?php echo ${'button'.$i} ?>" />
			<span>&nbsp;&nbsp;</span>
			<label for="cryout_serious_slider_button<?php echo $i ?>_url"><?php printf( __('Link URL:', 'cryout-serious-slider'), $i ) ?></label>
			<input type="text" size="40" name="cryout_serious_slider_button<?php echo $i ?>_url" id="cryout_serious_slider_button<?php echo $i ?>_url" value="<?php echo ${'button'.$i.'_url'} ?>" />
			<span>&nbsp;&nbsp;</span>
			<input type="checkbox" id="cryout_serious_slider_button<?php echo $i ?>_target" name="cryout_serious_slider_button<?php echo $i ?>_target" <?php checked( ${'button'.$i.'_target'} ); ?> />
			<label for="cryout_serious_slider_button<?php echo $i ?>_target"><?php _e('Open in New Window', 'cryout-serious-slider') ?></label>
		</p>
		<?php } ?>
		
		<p>
			<label for="cryout_serious_slider_link"><?php _e('Slide Image Link URL:', 'cryout-serious-slider') ?></label>
			<input type="text" size="60" name="cryout_serious_slider_link" id="cryout_serious_slider_link" value="<?php echo $text; ?>" />
			<span>&nbsp;&nbsp;</span>
			<input type="checkbox" id="cryout_serious_slider_target" name="cryout_serious_slider_target" <?php checked( $check ); ?> />
			<label for="cryout_serious_slider_target"><?php _e('Open in New Window', 'cryout-serious-slider') ?></label>
		</p>
		<p>	<em><?php _e('Leave fields empty to disable elements.', 'cryout-serious-slider') ?></em> </p>
