<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* The shortcode class */
class Cryout_Serious_Slider_Shortcode {

	public $shortcode_tag = 'serious-slider';
	private $id = 0;
	private $cid = 0;
	private $custom_style = array();
	private $custom_script = array();
	private $butts = 2;

	function __construct($args = array()){
		//register shortcode
		add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_render' ) );
		$this->butts = apply_filters( 'cryout_serious_slider_buttoncount', $this->butts );

		include_once( plugin_dir_path(__FILE__) . '/helpers.php' );
		$this->sanitizer = new Cryout_Serious_Slider_Sanitizers;
	}

	function shortcode_style() {
		$sid = $this->id;
		$cid = $this->cid;
		$options = $this->shortcode_options($sid);
		foreach ($options as $id => $opt) ${$id} = $opt;

		ob_start();
		?><style type="text/css">
			/* cryout serious slider styles */
		<?php echo implode("\n", $this->custom_style); ?>
		</style><?php
		$custom_style = ob_get_clean();

		echo preg_replace( '/([\n\s])+/', ' ', $custom_style );
	} // shortcode_slyle()

	function shortcode_script() {
		ob_start();
		?>
		<script type="text/javascript">
			/* cryout serious slider scripts */
		<?php echo implode("\n", $this->custom_script); ?>
		</script>
		<?php
		ob_end_flush();
	} // shortcode_slyle()

	function shortcode_render($attr) {

		global $cryout_serious_slider;

		// exit silently if slider is is not defined
		if ( empty($attr['id'])) { return; }

		$options = apply_filters('cryout_serious_slider_shortcode_attributes', $this->shortcode_options( $attr['id'] ), $attr, $attr['id']);
		extract($options);

		if (!empty($attr['count'])) $count = esc_attr($attr['count']); else $count = -1;

		$hidetitle = ( !empty($hidetitles) || !empty($attr['hidetitle']) );
		$hidecaption = !empty($attr['hidecaption']);

		switch ($sort) {
			case 'order':
			// sort by order param
			$orderby = 'menu_order';
			$order = 'ASC';
			break;
		case 'rand':
			// sort by order param
			$orderby = 'rand';
			$order = 'ASC';
			break;
		default:
			// sort by publish date (default)
			$orderby = 'date';
			$order = 'DESC';
			break;
		} // switch

		// allow order override via shortcode
		if (!empty($attr['orderby'])) $orderby = esc_attr($attr['orderby']);

		$slider_classes = array();
		$slider_classes[] = 'seriousslider-overlay' . $overlay;
		$slider_classes[] = 'seriousslider-' . $theme;
		$slider_classes[] = 'seriousslider-responsive-' . $responsiveness;
		$slider_classes[] = 'seriousslider-hidetitles-' . $hidetitles;
		$slider_classes[] = 'seriousslider-' . $animation;
		$slider_classes[] = 'seriousslider-sizing' . $sizing;
		$slider_classes[] = 'seriousslider-align' . $align;
		$slider_classes[] = 'seriousslider-caption-animation-' . $captionanimation;
		$slider_classes[] = 'seriousslider-textstyle-' . $textstyle;
		$slider_classes = implode(' ', $slider_classes);


		$cid = abs($attr['id']).'-rnd'.rand(1000,9999);

		$the_query = new WP_Query(
			array(
				'post_type' => array( $cryout_serious_slider->posttype ),
				'order' => $order,
				'orderby' => $orderby,
				'showposts' => $count,
					'tax_query' => array(
					array(
						'taxonomy' => $cryout_serious_slider->taxonomy,
						'field'    => 'id',
						'terms'    => array( $cid ),
					),
				),
			)
		);

		$counter = 0;
		$this->id = $attr['id'];
		$this->cid = $cid;

		ob_start(); ?>
			.serious-slider-<?php echo $cid ?> { max-width: <?php echo intval( $width ); ?>px; }
			.serious-slider-<?php echo $cid ?>.seriousslider-sizing1, .serious-slider-<?php echo $cid ?>.seriousslider-sizing1 img { max-height: <?php echo intval( $height ); ?>px;  }
			.serious-slider-<?php echo $cid ?>.seriousslider-sizing2, .serious-slider-<?php echo $cid ?>.seriousslider-sizing2 img.item-image { height: <?php echo intval( $height ); ?>px;  }
			.serious-slider-<?php echo $cid ?> .seriousslider-caption-inside { max-width: <?php echo intval($caption_width) ?>px;  font-size: <?php echo round($textsize,2) ?>em; }

			.serious-slider-<?php echo $cid ?> .seriousslider-inner > .item {
				-webkit-transition-duration: <?php echo round($transition/1000,2) ?>s;
				-o-transition-duration: <?php echo round($transition/1000,2) ?>s;
				transition-duration: <?php echo round($transition/1000,2) ?>s; }

			.seriousslider-textstyle-bgcolor .seriousslider-caption-title span {
				background-color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, 0.6);
			}

			/* Indicators */
			.seriousslider-dark .seriousslider-indicators li.active,
			.seriousslider-square .seriousslider-indicators li.active,
			.seriousslider-tall .seriousslider-indicators li.active,
			.seriousslider-captionleft .seriousslider-indicators li.active,
			.seriousslider-captionbottom .seriousslider-indicators li.active {
				background-color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, 0.8);
			}

			/* Arrows */
			.seriousslider-dark .seriousslider-control:hover .control-arrow,
			.seriousslider-square .seriousslider-control:hover .control-arrow,
			.seriousslider-tall .seriousslider-control .control-arrow {
				background-color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, 0.8);
			}

			.seriousslider-tall .seriousslider-control:hover .control-arrow {
				color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, 1);
				background-color: #FFF;
			}

			.seriousslider-captionbottom .seriousslider-control .control-arrow,
			.seriousslider-captionleft .seriousslider-control .control-arrow {
				color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, .8);
			}

			.seriousslider-captionleft .seriousslider-control:hover .control-arrow {
				color: rgba( <?php echo $this->sanitizer->hex2rgb( $accent ); ?>, 1);
			}

			/* Buttons */

			<?php switch ($theme) {
				case 'light': ?>

				/* Light */
				.seriousslider-light .seriousslider-caption-buttons a:nth-child(2n+1),
				.seriousslider-light .seriousslider-caption-buttons a:hover:nth-child(2n) {
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-light .seriousslider-caption-buttons a:hover:nth-child(2n+1) {
					background-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
					border-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

			<?php break;
				case 'dark': ?>

				/* Dark */
				.seriousslider-dark .seriousslider-caption-buttons a:nth-child(2n) {
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-dark .seriousslider-caption-buttons a:hover:nth-child(2n+1) {
					border-color: #FFF;
				}

				.seriousslider-dark .seriousslider-caption-buttons a:hover:nth-child(2n) {
					border-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-dark .seriousslider-caption-buttons a:nth-child(2n+1)  {
					background-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
					border-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

			<?php break;
				case 'square': ?>

				/* Square */
				.seriousslider-square .seriousslider-caption-buttons a:nth-child(2n+1) {
					background-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-square .seriousslider-caption-buttons a:nth-child(2n) {
					background: #fff;
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-square .seriousslider-caption-buttons a:hover:nth-child(2n+1) {
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
					background: #FFF;
				}

				.seriousslider-square .seriousslider-caption-buttons a:hover:nth-child(2n) {
					color: #fff;
					background-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

			<?php break;
				case 'tall': ?>

				/* Tall */
				.seriousslider-tall .seriousslider-caption-buttons a:nth-child(2n+1) {
					background-color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-tall .seriousslider-caption-buttons a:nth-child(2n) {
					background: #FFF;
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

				.seriousslider-tall .seriousslider-caption-buttons a:hover {
					opacity: 0.8;
				}

			<?php break;
				case 'captionleft': ?>

				/* Left caption */
				.seriousslider-captionleft .seriousslider-caption-buttons a:hover {
					color: <?php echo $this->sanitizer->color_clean( $accent ); ?>;
				}

			<?php
				break;
				default:
				break;
			} // switch($theme)

		$this->custom_style[] = ob_get_clean();
		add_action( 'wp_footer', array($this, 'shortcode_style') );
		ob_start() ?>
		var interval = <?php echo $delay ?>/200;
		jQuery(document).ready(function(){


			jQuery('#serious-slider-<?php echo $cid ?>').carousel({
				interval: <?php if ($autoplay) echo $delay; else echo 'false'; ?>,
				pause: '<?php echo $hover ?>',
				stransition: <?php echo $transition ?>
			});

		});

		<?php
		$this->custom_script[] = ob_get_clean();
		add_action( 'wp_footer', array($this, 'shortcode_script') );

		if ( $the_query->have_posts() ):
		ob_start(); ?>
		<div id="serious-slider-<?php echo $cid ?>" class="cryout-serious-slider seriousslider serious-slider-<?php echo $cid ?> cryout-serious-slider-<?php echo $attr['id'] ?> <?php echo $slider_classes ?>" data-ride="seriousslider">
			<div class="seriousslider-inner" role="listbox">

			<?php while ($the_query->have_posts()):
				$the_query->the_post();
				$counter++;

				// retrieve parameters
				$slide_meta = get_post_meta( get_the_ID() );

				if ( !empty($slide_meta['cryout_serious_slider_link'][0]) )
						$meta_link = ' href="' . esc_url($slide_meta['cryout_serious_slider_link'][0]) . '"';
						else $meta_link = '';
				if ( !empty($slide_meta['cryout_serious_slider_target'][0]) && $slide_meta['cryout_serious_slider_target'][0] )
						$meta_target = 'target="_blank"';
						else $meta_target = '';
				for ( $i=1; $i<=$this->butts; $i++ ) {
					if ( !empty($slide_meta['cryout_serious_slider_button'.$i][0]) )
						${'meta_button'.$i} = $slide_meta['cryout_serious_slider_button'.$i][0]; else ${'meta_button'.$i} = FALSE;
					if ( !empty($slide_meta['cryout_serious_slider_button'.$i.'_url'][0]) )
						${'meta_button'.$i.'_url'} = $slide_meta['cryout_serious_slider_button'.$i.'_url'][0]; else ${'meta_button'.$i.'_url'} = '';
					if ( !empty($slide_meta['cryout_serious_slider_button'.$i.'_target'][0]) && $slide_meta['cryout_serious_slider_button'.$i.'_target'][0] )
						${'meta_button'.$i.'_target'} = 'target="_blank"'; else ${'meta_button'.$i.'_target'} = '';
				}

				$image_data = wp_get_attachment_image_src (get_post_thumbnail_ID( get_the_ID() ), 'full' );

				if ( !empty($sizing) && $sizing ) $sizes = 'width="' . $width . '" height="' . $height . '"'; else $sizes = '';

				$slide_title = get_the_title();
				$slide_text = get_the_content();

				?>

			<div class="item slide-<?php echo $counter ?> <?php if ($counter==1) echo 'active' ?>" role="option">
				<?php if (!empty($image_data[0])): ?>
				<a <?php echo $meta_link; ?> <?php echo $meta_target; ?>>
					<img class="item-image" src="<?php echo $image_data[0] ?>" alt="<?php the_title_attribute(); ?>" <?php echo $sizes ?>>
				</a>
				<?php endif; ?>
				<?php if (( !empty($slide_title) || !empty($slide_text) ) && !$hidecaption): ?>
				<div class="seriousslider-caption">
					<div class="seriousslider-caption-inside">
						<?php if (!empty($slide_title) && !$hidetitle) { ?><div class="seriousslider-caption-title"><span><?php the_title(); ?></span></div><?php } ?>
						<?php if (!empty($slide_text)) { ?><div class="seriousslider-caption-text"><?php the_content() ?></div><?php } ?>
						<div class="seriousslider-caption-buttons">
							<?php for ( $i=1; $i<=$this->butts; $i++ ) { ?>
								<?php if ( !empty(${'meta_button'.$i}) ) { ?>
									<a class="seriousslider-button" href="<?php echo esc_url( ${'meta_button'.$i.'_url'}) ?>" <?php echo ${'meta_button'.$i.'_target'} ?>><?php echo esc_attr( ${'meta_button'.$i} ) ?></a>
								<?php } ?>
							<?php } ?>
						</div>
					</div><!--seriousslider-caption-inside-->
				</div><!--seriousslider-caption-->
				<?php endif; ?>
				<div class="seriousslider-hloader"></div>
				<figure class="seriousslider-cloader">
					<svg width="200" height="200">
						<circle cx="95" cy="95" r="20" transform="rotate(-90, 95, 95)"/>
					</svg>
			  </figure>
			</div>

			<?php endwhile; ?>
			</div>

			<div class="seriousslider-indicators">
				<ol class="seriousslider-indicators-inside">
					<?php for ($i=0;$i<$counter;$i++) { ?>
					<li data-target="#serious-slider-<?php echo $cid ?>" data-slide-to="<?php echo $i?>" <?php if ($i==0) echo 'class="active"' ?>></li>
					<?php } ?>
				</ol>
			</div>

			<button class="left seriousslider-control" data-target="#serious-slider-<?php echo $cid ?>" role="button" data-slide="prev">
			  <span class="sicon-prev control-arrow" aria-hidden="true"></span>
			  <span class="sr-only"><?php _e('Previous Slide','cryout-serious-slider') ?></span>
			</button>
			<button class="right seriousslider-control" data-target="#serious-slider-<?php echo $cid; ?>" role="button" data-slide="next">
			  <span class="sicon-next control-arrow" aria-hidden="true"></span>
			  <span class="sr-only"><?php _e('Next Slide','cryout-serious-slider') ?></span>
			</button>
		</div>
		<?php
		wp_reset_query(); /* clean up the query */
		return ob_get_clean();
		endif; ?>
		<!-- end cryout serious slider -->
		<?php

	} // shortcode_render()

	function shortcode_options($sid) {

		global $cryout_serious_slider;

		if (is_numeric($sid)) {
			$data = get_option( "cryout_serious_slider_${sid}_meta" );
			$data = wp_parse_args( $data, $cryout_serious_slider->defaults );
		} else {
			$data = $cryout_serious_slider->defaults;
		}
		foreach ($data as $id=>$value){
			$options[str_replace('cryout_serious_slider_','',$id)] = $value;
		}
		return $options;
	} // shortcode_options()

} // class

/* Initialize the shortcode class */
$cryout_serious_slider_shortcode = new Cryout_Serious_Slider_Shortcode;

/* FIN */