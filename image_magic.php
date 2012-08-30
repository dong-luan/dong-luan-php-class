<?php
$image_width;
$image_height;
class Image_magic {
	private $image_height;
	private $image_width;
	private $image;
	private $extension;
	private $new_image;
	private $tmp_image;
	function __construct($file_name) {
		$this -> image = $this -> get_image($file_name);
		if ($this -> image != NULL) {
			$this -> image_width = imagesx($this -> image);
			$this -> image_height = imagesy($this -> image);
		}
	}

	//Check file có đúng định dạng không, nếu đúng trả về đuôi file
	public function get_image($file_name) {
		$path_parts = pathinfo($file_name);
		$this -> extension = $path_parts['extension'];
		switch ($this->extension) {
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

	//Lấy về đuôi mở rộng của file ảnh
	public function get_extension() {
		return $this -> extension;
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

	//Cắt ảnh
	public function crop($src_x, $src_y, $new_width, $new_height) {
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

	//xoay ảnh
	public function rotation($degrees) {
		$this -> image = imagerotate($this -> image, $degrees, -1);
		imagealphablending($this -> image, true);
		imagesavealpha($this -> image, true);
	}

	//Đóng dấu water_mark
	public function add_watermark($watermark_path, $pos_x, $pos_y) {
		$path_parts = pathinfo($watermark_path);
		switch($path_parts['extension']) {
			case 'jpg' :
				$watermark_img = imagecreatefromjpeg($watermark_path);
				break;

			case 'jpeg' :
				$watermark_img = imagecreatefromjpeg($watermark_path);
				break;

			case 'png' :
				$watermark_img = imagecreatefrompng($watermark_path);
				break;

			case 'gif' :
				$watermark_img = imagecreatefromgif($watermark_path);
				break;
			default :
				$watermark_img = NULL;
				break;
		}
		$this -> add_watermark_exact_position($watermark_img, $pos_x, $pos_y);
	}

	private function add_watermark_exact_position($watermark_img, $pos_x, $pos_y) {
		$watermark_width = imagesx($watermark_img);
		$watermark_height = imagesy($watermark_img);
		imagecopy($this -> image, $watermark_img, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_top_left($watermark_img) {
		$watermark_width = imagesx($watermark_img);
		$watermark_height = imagesy($watermark_img);
		$pos_x = $this -> image_width - ($watermark_width + 5);
		imagecopy($this -> image, $watermark_img, 5, 5, $pos_x, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_top_right($watermark_img) {
		$watermark_width = imagesx($watermark_img);
		$watermark_height = imagesy($watermark_img);
		imagecopy($this -> image, $watermark_img, 5, 5, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_top_center($watermark_img) {
		$watermark_width = imagesx($watermark_img);
		$watermark_height = imagesx($watermark_img);
		$pos_x = $this -> image_width / 2 - $watermark_width / 2;
		imagecopy($this -> image, $watermark_img, $pos_xp, 5, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_bottom_left() {
		$watermark_width = imagesx($watermark_img);
		$watermark_height = imagesy($watermark_img);
		imagecopy($this -> image, $watermark_img, 5, 5, 0, 0, $watermark_width, $watermark_height);
	}

	private function add_watermark_bottom_right() {
       
	}

	private function add_watermark_bottom_center() {

	}

	private function add_watermark_middle_right() {

	}

	private function add_watermark_center() {

	}

	private function add_watermark_middle_left() {

	}

	//Hiển thị ảnh ra
	public function show() {
		switch ($this->get_extension()) {
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
				if (imagetypes() & IMG_JPG) {
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
		$dest_path_extension = $path_parts['extension'];
		switch ($dest_path_extension) {
			case 'jpg' :
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this -> new_image, $dest_path, $quality);
				}
				break;

			case 'jpeg' :
				if (imagetypes() & IMG_JPEG) {
					imagejpeg($this -> new_image, $dest_path, $quality);
				}
				break;

			case 'png' :
				if (imagetypes() & IMG_PNG) {
					$quality = round(($quality / 100) * 9);
					$quality = 9 - $quality;
					imagepng($this -> new_image, $dest_path, $quality);
				}
				break;

			case 'gif' :
				if (imagetypes() & IMG_GIF) {
					imagegif($this -> new_image, $dest_path);
				}
				break;

			default :
				break;
		}
		imagedestroy($this -> new_image);
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

	// function insert text on image

	//function add string
	public function add_text() {
		$text_color = imagecolorallocate($this -> image, 0, 0, 0);
		$font = imageloadfont('arial.gdf');
		imagestring($this -> image, $font, 15, 20, 'FAP', $text_color);
	}

	// function test insert text => 'right bottom'
	public function test_insert_text($text, $font_size) {
		$font = "arial.ttf";

		// load font chu (chi dung cho font .gdf)
		$f = imageloadfont($font);
		$i = strlen($text);
		// dem do dai cua chuoi
		$h = imagefontheight($f);
		// tinh chieu cao cua chuoi
		$w = $i * imagefontwidth($f);
		// tinh do rong cua chuoi = do dai * do rong cua font

		$x = $this -> image_width - $w;
		// lay toa do x = chieu rong cua anh - do dai cua chu
		$y = $this -> image_height - $h;
		// lay toa do y = chieu cao cua anh - do cao cua chu

		$red = imagecolorallocate($this -> image, 0xFF, 0x00, 0x00);
		// mau do
		imagefttext($this -> image, $font_size, 0, $x, $y, $red, $font, $text);
	}

	public function insert_text($text, $font_size = 13, $color = 'black', $font = 'arial', $x = 0, $y = 0, $angle = 0, $shadow_color = 'null', $shadow_width = 1) {
		$font .= ".ttf";

		if (!is_numeric($x)) {
			$bbox = imagettfbbox($font_size, 0, $font, $text);
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

		if (!is_numeric($y)) {
			$bbox = imagettfbbox($font_size, 0, $font, $text);
			if ($y == 'top') {
				$y = -$bbox[7] * 2 - $font_size + 5;
				// top
			} elseif ($y == 'middle') {
				$y = $bbox[1] + (imagesy($this -> image) / 2) - ($bbox[5] / 2) - $font_size / 2;
				// middle
			} elseif ($y == 'bottom') {
				$y = imagesy($this -> image) - 15;
				// bottom
			}
		} else {
			$y += $font_size;
		}

		$array_color = array("red" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x00), "white" => imagecolorallocate($this -> image, 0xFF, 0xFF, 0xFF), "turquoise" => imagecolorallocate($this -> image, 0x00, 0xFF, 0xFF), "grey" => imagecolorallocate($this -> image, 0xC0, 0xC0, 0xC0), "light grey" => imagecolorallocate($this -> image, 0xC0, 0xC0, 0xC0), "dark grey" => imagecolorallocate($this -> image, 0x80, 0x80, 0x80), "blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xFF), "light blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xFF), "dark blue" => imagecolorallocate($this -> image, 0x00, 0x00, 0xA0), "black" => imagecolorallocate($this -> image, 0x00, 0x00, 0x00), "purple" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x80), "light purple" => imagecolorallocate($this -> image, 0xFF, 0x00, 0x80), "dark purple" => imagecolorallocate($this -> image, 0x80, 0x00, 0x80), "orange" => imagecolorallocate($this -> image, 0xFF, 0x80, 0x40), "brown" => imagecolorallocate($this -> image, 0x80, 0x40, 0x00), "yellow" => imagecolorallocate($this -> image, 0xFF, 0xFF, 0x00), "burgundy" => imagecolorallocate($this -> image, 0x80, 0x00, 0x00), "green" => imagecolorallocate($this -> image, 0x00, 0xFF, 0x00), "pastel green" => imagecolorallocate($this -> image, 0x00, 0xFF, 0x00), "forest green" => imagecolorallocate($this -> image, 0x80, 0x80, 0x00), "grass green" => imagecolorallocate($this -> image, 0x40, 0x80, 0x80), "pink" => imagecolorallocate($this -> image, 0xFF, 0x00, 0xFF));
		// check color
		if (substr($color, 0, 1) == "#") {
			$color_text = imagecolorallocate($this -> image, '0x' . substr($color, 1, 2), "0x" . substr($color, 3, 2), "0x" . substr($color, 5, 2));
		} else {
			$color_text = $array_color[$color];
		}

		// check select shadow
		if ($shadow_color != 'null') {
			// check color
			if (substr($shadow_color, 0, 1) == "#") {
				$color_shadow_text = imagecolorallocate($this -> image, '0x' . substr($shadow_color, 1, 2), "0x" . substr($shadow_color, 3, 2), "0x" . substr($shadow_color, 5, 2));
			} else {
				$color_shadow_text = $array_color[$shadow_color];
			}

			imagefttext($this -> image, $font_size, $angle, $x + $shadow_width, $y + $shadow_width, $color_shadow_text, $font, $text);
		}
		$text . wordwrap($text, $this -> image_width, '\n');

		imagefttext($this -> image, $font_size, $angle, $x, $y, $color_text, $font, $text);

	}

}
?>