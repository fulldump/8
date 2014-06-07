<?php

class PrimPng extends Prim {

	protected function open() {
		$this->_image_magick = imagecreatefrompng($this->_path);
	}

	protected function _save($resource, $destination) {
		imagesavealpha($resource, true);
		return imagepng ($resource, $destination, 9);
	}

}
