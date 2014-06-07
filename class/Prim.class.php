<?php

/**
 * Clase: Prim
 * Ubicación: /class/Prim.class.php
 * Descripción: Processing Images
 *
 * autor: gerardooscarjt@gmail.com
 * fecha: 2013/02/10
*/

class Prim {

	protected $_crop_l = 0; // crop-left
	protected $_crop_r = 0; // crop-right
	protected $_crop_t = 0; // crop-top
	protected $_crop_b = 0; // crop-bottom
	protected $_width = null; // width
	protected $_height = null; // height
	protected $_mode = 'cover'; // mode
	protected $_quality = 80; // quality
	protected $_format = 'jpeg'; // output format
	protected $_image = null; // Image object
	protected $_image_magick = null; // Image magick object
	protected $_path = '';

	const MAX_WIDTH = 2000;
	const MAX_HEIGHT = 2000;

	private function __construct(&$image) {
		$this->_image = &$image;
		$this->_path = Rack::Path('img', md5($image->ID()));
	}

	public static function transform(&$image) {
		$mime = $image->getMime();
		switch ($mime) {
			case 'image/jpeg':
				return new PrimJpeg($image);
				break;
			
			case 'image/png':
			case 'image/x-png':
				return new PrimPng($image);
				break;
			
			case 'image/gif':
				return new PrimGif($image);
				break;
			
			case 'value':
				return new PrimBmp($image);
				break;
		}
		return null;
	}	

	public function saveTo($destination) {
		$this->open();
		$src = $this->_image_magick;

		$src_x = $this->_crop_l;
		$src_y = $this->_crop_t;
		$src_w = $this->_image->getWidth() - $this->_crop_l - $this->_crop_r;
		$src_h = $this->_image->getHeight() - $this->_crop_t - $this->_crop_b;

		// TODO: Calc result width and height
		$dst_x = 0;
		$dst_y = 0;

		if (null == $this->_width && null == $this->_height) {
			// None dimension is setted
			$dst_w = $this->_image->getWidth();
			$dst_h = $this->_image->getHeight();
		} else if (null != $this->_width && null != $this->_height) {
			// Both dimensions are setted
			// TODO: cover posibility
			$dst_w = $this->_width;
			$dst_h = $this->_height;
		} else if (null == $this->_width && null != $this->_height) {
			// Only Height is setted
			$dst_w = $this->_height * $this->_image->getWidth() / $this->_image->getHeight();
			$dst_h = $this->_height;
		} else if (null != $this->_width && null == $this->_height) {
			// Only Width is setted
			$dst_w = $this->_width;
			$dst_h = $this->_width * $this->_image->getHeight() / $this->_image->getWidth();
		}

		// Calculate the mode
		if ('stretch' == $this->_mode) {
			// Do nothing
		} else if ('cover' == $this->_mode) { // Default mode
			if (null != $this->_width && null != $this->_height) {
				$dst_aspect = $dst_h/$dst_w;
				$src_aspect = $src_h/$src_w;
				if ($src_aspect > $dst_aspect) { // Crop top and bottom
					$new_height = ceil($src_w*$dst_aspect);
					$src_y += floor( ($src_h - $new_height) / 2 );
					$src_h = $new_height;
				} else { // Crop left and right
					$new_width =  ceil($src_h/$dst_aspect);
					$src_x += floor( ($src_w - $new_width) / 2 );
					$src_w = $new_width;
				}
			}
		} else if ('contain' == $this->_mode) {
			// TODO: Unimplemented
		}

		// Create destination image
		$dst =  imagecreatetruecolor($dst_w, $dst_h);
		imagealphablending($dst, false);
		$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
		imagefilledrectangle($dst, 0 , 0 , $dst_w, $dst_h, $transparent);

		// Transform
		imagecopyresampled (
			$dst, $src, // Image resources
			$dst_x, $dst_y, // Destination coordinates origin point
			$src_x, $src_y, // Source coordinates origin point
			$dst_w, $dst_h, // Destination size vector
			$src_w, $src_h); // Source size vector

		imagedestroy($src);

		$this->_save($dst, $destination);

		imagedestroy($dst);
	}

	public function setRules($string) {

		// Pass form string to array:
		$list = explode(';', $string);

		// Map attributes:
		$attributes = array();
		foreach ($list as $l) {
			$a = explode(':', trim($l));
			$key = trim($a[0]);
			if ($key != '') {
				$attributes[$key] = explode(' ', $a[1]);
			}
		}

		// Process:
		foreach  ($attributes as $A=>$a) {
			switch ($A) {
				case 'crop':
				case 'c':
					$this->_setCrop($a);
					break;
				case 'crop-left':
				case 'c-l':
					$this->_setCropLeft($a[0]);
					break;
				case 'crop-right':
				case 'c-r':
					$this->_setCropRight($a[0]);
					break;
				case 'crop-top':
				case 'c-t':
					$this->_setCropTop($a[0]);
					break;
				case 'crop-bottom':
				case 'c-b':
					$this->_setCropBottom($a[0]);
					break;
				case 'width':
				case 'w':
					$this->_setWidth($a[0]);
					break;
				case 'height':
				case 'h':
					$this->_setHeight($a[0]);
					break;
				case 'mode':
				case 'm':
					$this->_setMode($a[0]);
					break;
				case 'quality':
				case 'q':
					$this->_setQuality($a[0]);
					break;
				case 'format':
				case 'f':
					$this->_setFormat($a[0]);
					break;
			}
		}
	}

	/**
	 * Return transformation hash
	*/
	public function getHash() {
		$transformation = ''
			.$this->_crop_l
			.$this->_crop_r
			.$this->_crop_t
			.$this->_crop_b
			.$this->_width
			.$this->_height
			.$this->_mode
			.$this->_quality
			.$this->_format;
		return md5($transformation);
	}

	private function _setCrop ($values) {
		$count = count($values);
		if ($count == 1) {
			$this->_setCropTop($values[0]);
			$this->_setCropRight($values[0]);
			$this->_setCropBottom($values[0]);
			$this->_setCropLeft($values[0]);
		} else if ($count == 2) {
			$this->_setCropTop($values[0]);
			$this->_setCropRight($values[1]);
			$this->_setCropBottom($values[0]);
			$this->_setCropLeft($values[1]);
		} else if ($count == 3) {
			$this->_setCropTop($values[0]);
			$this->_setCropRight($values[1]);
			$this->_setCropBottom($values[2]);
			$this->_setCropLeft($values[1]);
		} else if ($count == 4) {
			$this->_setCropTop($values[0]);
			$this->_setCropRight($values[1]);
			$this->_setCropBottom($values[2]);
			$this->_setCropLeft($values[3]);
		}
	}

	private function _setCropLeft($value) {
		$value = intval($value);
		if ($value >= 0) {
			$this->_crop_l = $value;
		}
	}

	private function _setCropRight($value) {
		$value = intval($value);
		if ($value >= 0) {
			$this->_crop_r = $value;
		}
	}

	private function _setCropTop($value) {
		$value = intval($value);
		if ($value >= 0) {
			$this->_crop_t = $value;
		}
	}

	private function _setCropBottom($value) {
		$value = intval($value);
		if ($value >= 0) {
			$this->_crop_b = $value;
		}
	}

	private function _setWidth($value) {
		$value = intval($value);
		if ($value > 0 && $value < self::MAX_WIDTH) {
			$this->_width = $value;
		}
	}

	private function _setHeight($value) {
		$value = intval($value);
		if ($value > 0 && $value < self::MAX_HEIGHT) {
			$this->_height = $value;
		}
	}

	private function _setMode($value) {
		if ($value == 'stretch') {
			$this->_mode = 'stretch';
		} else if ($value == 'cover') {
			$this->_mode = 'cover';
		} else if ($value == 'contain') {
			$this->_mode = 'contain';
		}
	}

	private function _setQuality($value) {
		$value = intval($value);
		if ($value > 0 && $value <=100) {
			$this->_quality = $value;
		}
	}

	private function _setFormat($value) {
		switch ($value) {
			case 'jpeg':
			case 'jpg':
				$this->_format = 'jpeg';
				break;
			
			case 'png':
				$this->_format = 'png';
				break;
			
			case 'gif':
				$this->_format = 'gif';
				break;

			case 'bmp':
				$this->_format = 'bmp';
				break;
		}
	}

}
