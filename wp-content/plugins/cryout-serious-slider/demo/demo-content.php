<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Cryout_Serious_Slider_DemoContent {

	private $sample_slides = array(
		array(
			'post' => array(
				'post_title' => 'Customizable Slider',
				'post_content' => "Donec mollis elit et odio consectetur mattis. In pellentesque aliquam euismod. Mauris condimentum dui nunc, in congue dui sodales et. Pellentesque quis arcu lorem. Nam scelerisque fermentum ligula, eu ultrices purus congue non. Nullam fringilla mi in venenatis blandit. Curabitur iaculis sagittis leo vitae egestas. Donec eros nisl, dignissim eget arcu sit amet, tincidunt fermentum augue.",
				'post_status' => 'publish',
				'post_type' => 'cryout_serious_slide',
				'menu_order' => 3,
				'image' => '/demo/sample-slide-3.jpg',
			),
			'meta' => array(
				'cryout_serious_slider_button1' => 'Button 1',
				'cryout_serious_slider_button1url' => '#',
				'cryout_serious_slider_button2' => 'Button 2',
				'cryout_serious_slider_button2url' => '#',
			),
		),
		array(
			'post' => array(
				'post_title' => 'WordPress Responsive Slider',
				'post_content' => "Integer maximus tellus neque, a rhoncus augue finibus non. Praesent ullamcorper dui non justo blandit dictum. Sed placerat elit eu lacus congue, in ultricies felis sodales. In convallis risus et enim convallis suscipit.",
				'post_status' => 'publish',
				'post_type' => 'cryout_serious_slide',
				'menu_order' => 2,
				'image' => '/demo/sample-slide-2.jpg',
			),
			'meta' => array(
				'cryout_serious_slider_button1' => 'Sample Button',
				'cryout_serious_slider_button1url' => '#',
			),
		),
		array(
			'post' => array(
				'post_title' => 'Serious Slider',
				'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris lorem felis, egestas in posuere ac, pellentesque et nisl. Etiam id aliquam nulla. Nunc id commodo erat, at aliquet enim. Maecenas ut tempus est.',
				'post_status' => 'publish',
				'post_type' => 'cryout_serious_slide',
				'menu_order' => 1,
				'image' => '/demo/sample-slide-1.jpg',
			),
			'meta' => array(
				'cryout_serious_slider_button1' => 'Read more',
				'cryout_serious_slider_button1url' => '#',
				'cryout_serious_slider_button2' => 'Read less',
				'cryout_serious_slider_button2url' => '#',
			),
		),
	); // sample_slides

	function __construct() {

		// create sample slider ('category')
		$term = wp_insert_term(
			'Sample Slider',   // the term
			'cryout_serious_slider_category', // the taxonomy
			array(
				'description' => '',
				'slug'        => 'sample-slider',
			)
		);

		// create the slides
		foreach ($this->sample_slides as $i=>$slide) {
			$post = $slide['post'];
			$meta = $slide['meta'];

			// create sample slide
			$pid = wp_insert_post( $post );

			// add featured image
			$post['image'] = plugins_url( $post['image'], dirname(__FILE__) );
			$this->image_helper($pid, $post['image']);
			unset($post['image']);

			// assign slide to slider 'category'
			wp_set_object_terms($pid, 'Sample Slider', 'cryout_serious_slider_category', true);

			// add meta
			foreach( $meta as $id=>$value ) {
				update_post_meta( $pid, $id, $value );
			}
		}


	} // __construct()


	function image_helper($id, $image) {
		// magic sideload image returns an HTML image, not an ID
		$media = media_sideload_image($image, $id);

		// therefore we must find it so we can set it as featured ID
		if(!empty($media) && !is_wp_error($media)){
			$args = array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_status' => 'any',
				'post_parent' => $id
			);

			// reference new image to set as featured
			$attachments = get_posts($args);

			if(isset($attachments) && is_array($attachments)){
				foreach($attachments as $attachment){
					// grab source of full size images (so no 300x150 nonsense in path)
					$image = wp_get_attachment_image_src($attachment->ID, 'full');
					// determine if in the $media image we created, the string of the URL exists
					if(strpos($media, $image[0]) !== false){
						// if so, we found our image. set it as thumbnail
						set_post_thumbnail($id, $attachment->ID);
						// only want one image
						break;
					}
				}
			}
		}

	} // image_helper()

} // class Cryout_Serious_Slider_DemoContent

new Cryout_Serious_Slider_DemoContent;
