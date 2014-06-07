<?php

	/**
	 * Class: ImageInstance
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class ImageInstance extends ImageInstance_auto {

		public static function INSERT() {
			$row = parent::INSERT();
			$row->setImage(Image::INSERT('resources/default-image.png'));
			return $row;
		}

	}
