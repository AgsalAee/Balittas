<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Cryout_Serious_Slider_Widget extends WP_Widget { 	

	public $shortcode_tag = 'serious-slider';
   
	public function __construct() { 
		$widget_ops = array('classname' => 'serious-slider-widget', 'description' => 'Insert a Serious Slider in a widget area' );
		$control_ops = array('width' => 350, 'height' => 350); // making widget window larger
		parent::__construct('cryout_serious_slider_widget', 'Serious Slider', $widget_ops, $control_ops);
	} // construct()
	  
	function form($instance) {
		
		global $cryout_serious_slider;
		
		$instance = wp_parse_args( (array) $instance, array( 'sid' => '' ) );
		$sid = $instance['sid']; 
		$sliders = $cryout_serious_slider->get_sliders();
		?>
		<div>
			<p><label for="<?php echo $this->get_field_id('sid'); ?>"><?php _e('Displayed Slider', 'cryout-serious-slider') ?>:</label> 
				<select class="widefat" id="<?php echo $this->get_field_id('sid'); ?>" name="<?php echo $this->get_field_name('sid'); ?>">
					<?php foreach ($sliders as $slider) { ?>
						<option value="<?php echo $slider['value'] ?>" <?php selected($slider['value'],$sid) ?>><?php echo $slider['text'] ?></option>
					<?php } ?> 
				</select> 
			</p>
		</div> <?php  
	} // form()

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['sid'] = $new_instance['sid'];
		return $instance;
	} // update()
	  
	function widget($args, $instance) { 
		if(!empty($instance['sid'])) {
				$slider_id = $instance['sid']; 
				echo $args['before_widget'];
				echo do_shortcode( '[' . $this->shortcode_tag . ' id=' . $slider_id. ']' );
				echo $args['after_widget'];
		};
	} // widget() 
  
} // class Cryout_Serious_Slider_Widget

function cryout_seriousslider_widgetinit() {
	return register_widget("Cryout_Serious_Slider_Widget");
} // cryout_seriousslider_widgetinit()

add_action( 'widgets_init', 'cryout_seriousslider_widgetinit' );

// FIN