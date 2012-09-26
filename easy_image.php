<?php
/*
 @authors  Nguyen Trang Dong, Nguyen Thanh Luan
 @email    toptep.net@gmail.com
 @website  toptep.net
 */

class easy_image {
	private $image_height;
	private $image_width;
	private $image;
	private $new_image;
	private $tmp_image;
	private $file_name;
	private $ext;

	function __construct($file_name) {
		$this -> image = $this -> get_image($file_name);
		//$this -> get_image($file_name);
		if ($this -> image != NULL) {
			$this -> image_width = imagesx($this -> image);
			$this -> image_height = imagesy($this -> image);
			$this -> ext = $this -> get_extension($file_name);
			$this -> file_name = $file_name;
		} else {
			return;
		}
	}

	public function resize($new_width, $new_height) {
		$this -> image_width = imagesx($this -> image);
		$this -> image_height = imagesy($this -> image);

		if (is_numeric($new_width) && is_string($new_height) && $new_height == "auto") {
			$new_height = $this -> get_dynamic_height($new_width);
		}

		if (is_string($new_width) && $new_width == "auto" && is_numeric($new_height)) {
			$new_width = $this -> get_dynamic_with($new_height);
		}

		if ($new_width > 0 && $new_height > 0) {
			$this -> tmp_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($this -> tmp_image, $this -> image, 0, 0, 0, 0, $new_width, $new_height, $this -> image_width, $this -> image_height);
			$this -> image = $this -> tmp_image;
		}
	}
	//crop images
	public function crop() {
		$num_of_arguments = func_num_args();
		switch ($num_of_arguments) {
			case '2' :
				$arguments = func_get_args();
				$new_width = $arguments[0];
				$new_height = $arguments[1];
				if (is_numeric($new_width) && $new_width > 0 && is_numeric($new_height) && $new_height > 0) {
					$this -> crop_center($new_width, $new_height);
				}
				break;

			case '4' :
				$arguments = func_get_args();
				$src_x = $arguments[0];
				$src_y = $arguments[1];
				$new_width = $arguments[2];
				$new_height = $arguments[3];
				if (is_numeric($new_width) && $new_width > 0 && is_numeric($new_height) && $new_height > 0 && is_numeric($src_x) && $src_x > 0 && is_numeric($src_y) && $src_y > 0) {
					$this -> crop_exact($src_x, $src_y, $new_width, $new_height);
				}
			default :
				break;
		}
	}

	//add watermark
	public function add_watermark() {
		$num_of_agruments = func_num_args();
		switch ($num_of_agruments) {
			case 1 :
				$agruments = func_get_args();
				$watermark_file = $agruments[0];
				if (is_string($watermark_file)) {
					$watermark_image = $this -> get_image($watermark_file);
				} else {
					return;
				}
				if ($watermark_image != NULL) {
					$this -> add_watermark_center($watermark_image);
				} else {
					return;
				}
				break;

			case 3 :
				$agruments = func_get_args();
				$watermark_file = $agruments[0];
				$pos_x = $agruments[1];
				$pos_y = $agruments[2];
				if (is_string($watermark_file)) {
					$watermark_image = $this -> get_image($watermark_file);
				} else {
					return;
				}
				if ($watermark_image != NULL && is_numeric($pos_x) && is_numeric($pos_y)) {
					$this -> add_watermark_exact_position($watermark_image, $pos_x, $pos_y);
				} else {
					return;
				}
				break;

			case 4 :
				$agruments = func_get_args();
				$mode = $agruments[0];
				$watermark_file = $agruments[1];

				if (is_string($watermark_file)) {
					$watermark_image = $this -> get_image($watermark_file);
				} else {
					return;
				}

				if (is_string($mode)) {
					switch ($mode) {
						case 'left_top' :
							$distance_border_left = $agruments[2];
							$distance_border_top = $agruments[3];
							if (is_numeric($distance_border_left) && $distance_border_left > 0 && is_numeric($distance_border_top) && $distance_border_top > 0) {
								$this -> add_watermark_left_top($watermark_image, $distance_border_left, $distance_border_top);
							} else {
								return;
							}
							break;

						case 'right_top' :
							$distance_border_right = $agruments[2];
							$distance_border_top = $agruments[3];
							if (is_numeric($distance_border_right) && $distance_border_right > 0 && is_numeric($distance_border_top) && $distance_border_top > 0) {
								$this -> add_watermark_right_top($watermark_image, $distance_border_right, $distance_border_top);
							} else {
								return;
							}
							break;

						case 'left_bottom' :
							$distance_border_left = $agruments[2];
							$distance_border_bottom = $agruments[3];
							if (is_numeric($distance_border_left) && $distance_border_left > 0 && is_numeric($distance_border_bottom) && $distance_border_bottom > 0) {
								$this -> add_watermark_left_bottom($watermark_image, $distance_border_left, $distance_border_bottom);
							} else {
								return;
							}
							break;

						case 'right_bottom' :
							$distance_border_right = $agruments[2];
							$distance_border_bottom = $agruments[3];
							if (is_numeric($distance_border_right) && $distance_border_right > 0 && is_numeric($distance_border_bottom) && $distance_border_bottom > 0) {
								$this -> add_watermark_right_bottom($watermark_image, $distance_border_right, $distance_border_bottom);
							} else {
								return;
							}
							break;

						default :
							return;
							break;
					};
					imagedestroy($watermark_image);
				} else {
					return;
				}

			default :
				break;
		}
	}

	//show images
	public function show() {
		//caching image
		$this -> caching_headers($_SERVER['SCRIPT_FILENAME'], filemtime($_SERVER['SCRIPT_FILENAME']));
		switch ($this->ext) {
			case 'jpg' :
				if (imagetypes() & IMG_JPG) {
					header('Content-Type: image/jpg');
					imagejpeg($this -> image);
				}
				break;

			case 'jpeg' :
				if (imagetypes() & IMG_JPEG) {
					header('Content-Type: image/jpeg');
					imagejpeg($this -> image);
				}
				break;

			case 'png' :
				if (imagetypes() & IMG_PNG) {
					header('Content-Type: image/png');
					imagepng($this -> image);
				}
				break;

			case 'gif' :
				if (imagetypes() & IMG_GIF) {
					header('Content-Type: image/gif');
					imagegif($this -> image);
				}
				break;

			default :
				break;
		}
		imagedestroy($this -> image);
	}

	//Lưu ảnh vào ổ đĩa
	public function save($dest_path, $quality = "100") {
		$path_parts = pathinfo($dest_path);
		switch ($dest_path_extension) {
			case 'jpg' :
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this -> image, $dest_path, $quality);
				}
				break;

			case 'jpeg' :
				if (imagetypes() & IMG_JPEG) {
					imagejpeg($this -> image, $dest_path, $quality);
				}
				break;

			case 'png' :
				if (imagetypes() & IMG_PNG) {
					$quality = round(($quality / 100) * 9);
					$quality = 9 - $quality;
					imagepng($this -> image, $dest_path, $quality);
				}
				break;

			case 'gif' :
				if (imagetypes() & IMG_GIF) {
					imagegif($this -> image, $dest_path);
				}
				break;

			default :
				break;
		}
		imagedestroy($this -> image);
	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	private $text;
	private $font_size = 13;
	private $font = "arial";
	private $x = 0;
	private $y = 0;
	private $color = "#000000";
	private $shadow_color = null;
	private $shadow_width = 1;
	private $angle = 0;
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function insert text to image
	public function add_text() {
		$num_of_arguments = func_num_args();
		$agruments = func_get_args();
		$text = $agruments[0];
		switch ($num_of_arguments) {
			case '1':  
				$this -> add_text_normal($text);
				break;
			case '2':  
				$font_size = $agruments[1];
				$this -> add_text_with_font($text, $font_size);
				break;
			case '3':  
				if(is_numeric($agruments[1])){
					$font_size = $agruments[1];
					$font = $agruments[2];
					$this -> add_text_with_font($text, $font_size, $font);
				}else{
					$color = $agruments[1];
					$font_size = $agruments[2];
					$this -> add_text_with_font_color($text, $color, $font_size);
				} 
				break;
			case '4':
					$color = $agruments[1];
					$font_size = $agruments[2];
					$font = $agruments[3];
					$this -> add_text_with_font_color($text, $color, $font_size, $font);
				break;
			case '5':
				$font_size = $agruments[1];
				$color = $agruments[2];
				$x = $agruments[3];
				$y = $agruments[4];
				$this -> add_text_with_x_y($text, $font_size, $color, $x, $y);
				break;
			case '6':
				$x = $agruments[3];
				$y = $agruments[4];
				
				if(!is_numeric($agruments[1])){
					$color = $agruments[1];
					$font_size = $agruments[2];
					$angle = $agruments[5];
					$this -> add_text_with_x_y_angle($text, $color, $font_size, $x, $y, $angle);
				}else{
					$font_size = $agruments[1];
					$color = $agruments[2];
					$font = $agruments[5];
					$this -> add_text_with_x_y($text, $font_size, $color, $x, $y, $font);
				} 
				break;
			case '7':
				$color = $agruments[1];
				$font_size = $agruments[2];
				$x = $agruments[3];
				$y = $agruments[4];
				$angle = $agruments[5];
				$font = $agruments[6];
				$this -> add_text_with_x_y_angle($text, $color, $font_size, $x, $y, $angle, $font);
				break;
			case '8':
				$color = $agruments[1];
				$font_size = $agruments[2];
				$x = $agruments[3];
				$y = $agruments[4];
				$angle = $agruments[5];
				$shadow_color = $agruments[6];
				$shadow_width = $agruments[7];
				$this -> add_text_with_x_y_angle_shadown($text, $color, $font_size, $x, $y, $angle, $shadow_color, $shadow_width);
				break;
			case '9':
				$color = $agruments[1];
				$font_size = $agruments[2];
				$x = $agruments[3];
				$y = $agruments[4];
				$angle = $agruments[5];
				$shadow_color = $agruments[6];
				$shadow_width = $agruments[7];
				$font = $agruments[8];
				$this -> add_text_with_x_y_angle_shadown($text, $color, $font_size, $x, $y, $angle, $shadow_color, $shadow_width, $font);
				break;
			default:
				break;
		}
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function active_add_text() {
		$this -> color = $this -> get_color($this -> color);
		$this -> y = $this -> get_y($this -> y);
		$this -> x = $this -> get_x($this -> x);
		$this ->font .= ".ttf"; 

		if ($this -> shadow_color != null) {
			$this -> shadow_color = $this -> get_color($this -> shadow_color);
			imagefttext($this -> image, $this -> font_size, $this -> angle, $this -> x + $this -> shadow_width, $this -> y + $this -> shadow_width, $this -> shadow_color, $this -> font, $this -> text);
		}

		imagefttext($this -> image, $this -> font_size, $this -> angle, $this -> x, $this -> y, $this -> color, $this -> font, $this -> text);
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_normal($text) {
		$this -> text = $text; 
		$this -> active_add_text();
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_with_font($text, $font_size, $font='arial') {
		$this -> text = $text;
		$this -> font_size = $font_size;
		$this -> font = $font;
		$this -> active_add_text();
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_with_font_color($text, $color, $font_size, $font='arial') {
		$this -> text = $text;
		$this -> color = $color;
		$this -> font_size = $font_size;
		$this -> font = $font;
		$this -> active_add_text();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_with_x_y($text, $font_size, $color, $x, $y, $font='arial'){
		$this -> text = $text;
		$this -> color = $color;
		$this -> font_size = $font_size;
		$this -> x = $x;
		$this -> y = $y;
		$this -> font = $font;
		$this -> active_add_text();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_with_x_y_angle($text, $color, $font_size, $x, $y, $angle, $font='arial'){
		$this -> text = $text;
		$this -> color = $color;
		$this -> font_size = $font_size;
		$this -> x = $x;
		$this -> y = $y;
		$this -> angle = $angle;
		$this -> font = $font;
		$this -> active_add_text();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add_text_with_x_y_angle_shadown($text, $color, $font_size, $x, $y, $angle, $shadow_color, $shadow_width, $font='arial'){
		$this -> text = $text;
		$this -> color = $color;
		$this -> font_size = $font_size;
		$this -> x = $x;
		$this -> y = $y;
		$this -> angle = $angle;
		$this -> shadow_width = $shadow_width;
		$this -> shadow_color = $shadow_color;
		$this -> font = $font;	
		$this -> active_add_text();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function get y
	public function get_y($y) {
		$font = $this ->font .".ttf";
		if (!is_numeric($y)) {
			$bbox = imagettfbbox($this -> font_size, 0, $font, $this -> text);
			if ($y == 'top') {
				$y = -$bbox[7] * 2 - $this -> font_size + 5;
				// top
			} elseif ($y == 'middle') {
				$y = $bbox[1] + (imagesy($this -> image) / 2) - ($bbox[5] / 2) - $this -> font_size / 2;
				// middle
			} elseif ($y == 'bottom') {
				$y = imagesy($this -> image) - 15;
				// bottom
			}
		} else {
			$y += $this -> font_size;
		}
		return $y;
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function get x
	public function get_x($x) {
		$font = $this ->font .".ttf";
		if (!is_numeric($x)) {
			$bbox = imagettfbbox($this -> font_size, 0, $font, $this -> text);
			if ($x == 'left') {
				$x = 5;
				// left
			} elseif ($x == 'center') {
				$x = $bbox[0] + (imagesx($this -> image) / 2) - ($bbox[4] / 2);
				// center
			} elseif ($x == 'right') {
				$x = imagesx($this -> image) - ($bbox[2]) + ($bbox[5]) + 10;
				// right
			}
		}
		return $x;
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function get color by color code and color name
	public function get_color($color_code) {
	
		if (substr($color_code, 0, 1) == "#") {
			$color = imagecolorallocate($this -> image, '0x' . substr($color_code, 1, 2), "0x" . substr($color_code, 3, 2), "0x" . substr($color_code, 5, 2));
		} else {
			$array_color = array("red" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x00), "white" => imagecolorallocate($this -> image, 0xFF, 0xFF, 0xFF), "turquoise" => imagecolorallocate($this -> image, 0x00, 0xFF, 0xFF), "grey" => imagecolorallocate($this -> image, 0xC0, 0xC0, 0xC0), "light grey" => imagecolorallocate($this -> image, 0xC0, 0xC0, 0xC0), "dark grey" => imagecolorallocate($this -> image, 0x80, 0x80, 0x80), "blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xFF), "light blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xFF), "dark blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xA0), "black" => imagecolorallocate($this -> image, 0x00, 0x00, 0x00), "purple" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x80), "light purple" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x80), "dark purple" => imagecolorallocate($this -> image, 0x80, 0x00, 0x80), "orange" => imagecolorallocate($this -> image, 0xFF, 0x80, 0x40), "brown" => imagecolorallocate($this -> image, 0x80, 0x40, 0x00), "yellow" => imagecolorallocate($this -> image, 0xFF, 0xFF, 0x00), "burgundy" => imagecolorallocate($this -> image, 0x80, 0x00, 0x00), "green" => imagecolorallocate($this -> image, 0x00, 0xFF, 0x00), "pastel green" => imagecolorallocate($this -> image, 0x00, 0xFF, 0x00), "forest green" => imagecolorallocate($this -> image, 0x80, 0x80, 0x00), "grass green" => imagecolorallocate($this -> image, 0x40, 0x80, 0x80), "pink" => imagecolorallocate($this -> image, 0xFF, 0x00, 0xFF)); 
			$color = $array_color[$color_code];
		}
		return $color;
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function grayscale() {
		imagefilter($this -> image, IMG_FILTER_GRAYSCALE);
	}

	public function emboss() {
		imagefilter($this -> image, IMG_FILTER_EMBOSS);
	}

	public function negative() {
		imagefilter($this -> image, IMG_FILTER_NEGATE);
	}

	public function blur() {
		imagefilter($this -> image, IMG_FILTER_GAUSSIAN_BLUR);
	}

	public function smooth($arg) {
		imagefilter($this -> image, IMG_FILTER_SMOOTH, $arg);
	}

	public function brightness($arg) {
		imagefilter($this -> image, IMG_FILTER_BRIGHTNESS, $arg);
	}

	public function contrast($arg) {
		imagefilter($this -> image, IMG_FILTER_CONTRAST, $arg);
	}

	//private method
	private function add_watermark_exact_position($watermark_image, $pos_x, $pos_y) {
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		imagecopyre($this -> image, $watermark_image, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_left_top($watermark_image, $distance_border_left, $distance_border_top) {
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		imagecopy($this -> image, $watermark_image, $distance_border_left, $distance_border_top, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_right_top($watermark_image, $distance_border_right, $distance_border_top) {
		$watermark_widht = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		$pos_x = $this -> image_width - ($watermark_widht + $distance_border_right);
		imagecopy($this -> image, $watermark_image, $pos_x, $distance_border_top, 0, 0, $watermark_widht, $watermark_height);
	}

	private function add_watermark_left_bottom($watermark_image, $distance_border_left, $distance_border_bottom) {
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		$pos_y = $this -> image_height - ($watermark_height + $distance_border_bottom);
		imagecopy($this -> image, $watermark_image, $distance_border_left, $pos_y, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_right_bottom($watermark_image, $distance_border_right, $distance_border_bottom) {
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		$pos_x = $this -> image_width - ($watermark_width + $distance_border_right);
		$pos_y = $this -> image_height - ($watermark_height + $distance_border_bottom);
		imagecopy($this -> image, $watermark_image, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_center($watermark_image) {
		$watermark_width = imagesx($watermark_image);
		$watermark_height = imagesy($watermark_image);
		$pos_x = ($this -> image_width / 2) - ($watermark_width / 2);
		$pos_y = ($this -> image_height / 2) - ($watermark_height / 2);
		imagecopy($this -> image, $watermark_image, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height);
	}

	private function get_dynamic_with($new_image_height) {
		$ratio = $this -> image_width / $this -> image_height;
		$new_image_width = $new_image_height * $ratio;
		return $new_image_width;
	}

	private function get_dynamic_height($new_image_width) {
		$ratio = $this -> image_height / $this -> image_width;
		$new_image_height = $new_image_width * $ratio;
		return $new_image_height;
	}

	private function get_extension($image) {
		$path_parts = pathinfo($image);
		return strtolower($path_parts['extension']);
	}

	//Check file extension
	private function get_image($file_name) {
		$ext = $this -> get_extension($file_name);
		switch ($ext) {
			case 'jpeg' :
				$img = imagecreatefromjpeg($file_name);
				break;

			case 'jpg' :
				$img = imagecreatefromjpeg($file_name);
				break;

			case 'png' :
				$img = imagecreatefrompng($file_name);
				break;

			case 'gif' :
				$img = imagecreatefromgif($file_name);
				break;

			default :
				$img = NULL;
				break;
		}
		return $img;
	}

	private function crop_exact($src_x, $src_y, $new_width, $new_height) {
		$this -> image_width = imagesx($this -> image);
		$this -> image_height = imagesy($this -> image);
		$distance_x = $this -> image_width - $src_x;
		$distance_y = $this -> image_height - $src_y;
		if ($distance_x > 0 && $distance_y > 0) {
			if ($new_width > $distance_x) {
				$new_width = $distance_x;
			}
			if ($new_height > $distance_y) {
				$new_height = $distance_y;
			}
			$this -> tmp_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($this -> tmp_image, $this -> image, 0, 0, $src_x, $src_y, $new_width, $new_height, $new_width, $new_height);
			$this -> image = $this -> tmp_image;
		}
	}

	private function crop_center($new_width, $new_height) {
		$src_x = ($this -> image_width / 2) - ($new_width / 2);
		$src_y = ($this -> image_height / 2) - ($new_height / 2);
		$this -> crop_exact($src_x, $src_y, $new_width, $new_height);
	}

	private function caching_headers($file, $timestamp) {
		// $gmt_mtime = gmdate('r', $timestamp);
		// header('ETag: "' . md5($timestamp . $file) . '"');
		// header('Last-Modified: ' . $gmt_mtime);
		// header('Cache-Control: public');
		//
		// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
		// if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $file)) {
		// header('HTTP/1.1 304 Not Modified');
		// exit();
		// }
		// }
	}

}
?>