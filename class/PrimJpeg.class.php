<?php




class PrimJpeg extends Prim {

	protected function open() {
		$this->_image_magick = imagecreatefromjpeg($this->_path);
	}

	protected function _save($resource, $destination) {
		$quality = max(1, $this->_quality);
		return imagejpeg ($resource, $destination, $quality);
	}

}
