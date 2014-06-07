<?php

class PrimGif extends Prim {

	protected function open() {
		$this->_image_magick = imagecreatefromgif($this->_path);
	}

	protected function _save($resource, $destination) {
		return imagejpeg ($resource, $destination);
	}

}
