<?php
/**
 * Serious Slider helper functions
 * inherited from Cryout Framework 
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
class Cryout_Serious_Slider_Sanitizers {

	/* sanitizes a RGB colour code to make sure it has proper structure and starts with # */
	public function color_clean( $color ){
		if ( '' === $color ) return '';
		if ( preg_match( '/^#?(([a-f0-9]{3}){1,2})$/i', $color, $matches ) )
			return '#' . $matches[1];
		return '';
	} // color_clean()

	/* converts hex colour code to RGB series to be used in a rgba() CSS colour definition */
	public function hex2rgb( $hex ) {
	   $hex = str_replace("#", "", $hex);
	   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
			if(strlen($hex) == 3) {
			   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
			   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
			   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
			   $r = hexdec(substr($hex,0,2));
			   $g = hexdec(substr($hex,2,2));
			   $b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
			return implode(",", $rgb); // returns the rgb values separated by commas
	   else: return "";  // input string is not a valid hex color code
	   endif;
	} // hex2rgb()

	/* adds a differential value to a RGB colour code; returns a hex colour code */
	public function hexadder( $hex, $inc ) {
	   $hex = str_replace("#", "", $hex);
	   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
			if(strlen($hex) == 3) {
			   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
			   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
			   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
			   $r = hexdec(substr($hex,0,2));
			   $g = hexdec(substr($hex,2,2));
			   $b = hexdec(substr($hex,4,2));
			}

			$rgb_array = array($r,$g,$b);
			$newhex="#";
			foreach ($rgb_array as $el) {
				$el+=$inc;
				if ($el<=0) { $el='00'; }
				elseif ($el>=255) {$el='ff';}
				else {$el=dechex($el);}
				if(strlen($el)==1)  {$el='0'.$el;}
				$newhex.=$el;
			}
			return $newhex;
	   else: return "";  // input string is not a valid hex color code
	   endif;
	} // hexadder()

	/* adds or subtracts a differential value to or from a RGB colour code; returns a hex colour code;
       sign of the operation is decided based on the colour lightness */
	public function hexdiff($hex,$inc,$f='') {
	   // $f = '-' | '+'
	   $hex = str_replace("#", "", $hex);
	   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
			if(strlen($hex) == 3) {
			   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
			   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
			   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
			   $r = hexdec(substr($hex,0,2));
			   $g = hexdec(substr($hex,2,2));
			   $b = hexdec(substr($hex,4,2));
			}

			$rgb_array = array($r,$g,$b);
			$newhex="#";

			// guess decimal lightness
			if ( ((int)$r < 102) && ((int)$g < 102) && ((int)$b < 102) ) $sign = +1; else $sign = -1;

			// forced sign handling
			if (!empty($f)) $sign = ($f == '-'? -1 : +1);

			foreach ($rgb_array as $el) {
				$el += $sign * (int)$inc;
				if ( $el<0 ) { $el='00'; }
				elseif ( $el>255 ) { $el='ff'; }
				else { $el = dechex($el); }
				if ( strlen($el)==1 ) { $el='0'.$el; }
				$newhex .= $el;
			}

			return $newhex;
	   else: return "";  // input string is not a valid hex color code
	   endif;
	} // hexdiff()

} // Cryout_Serious_Slider_Sanitizer()

// FIN