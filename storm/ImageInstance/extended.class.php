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

		public static function getByName($name) {
			$name = Database::escape($name);
			$images = self::SELECT("`Name` = '$name'");
			if (1 != count($images)) {
				return null;
			}
			return $images[0];
		}

		public function getImage() {
			return Image::ROW(Multilingual::get(parent::getImage()));
		}

		public function setImage($value) {
			if (is_object($value) && $value->getClassName() == 'Image') {
				$id = $value->getId();
				parent::setImage(Multilingual::set(parent::getImage(), $id));
			}
		}

	}
