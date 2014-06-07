<?php
	
	/**
	 * Clase: FileInstance
	 * Ubicación: storm/FileInstance/extended.class.php
	 * Fecha: Wed, 13 Jun 2012 00:48:08 +0200
	 * 
	*/
	
	class FileInstance extends FileInstance_auto {
	
		public static function INSERT() {
			$row = parent::INSERT();
			$label = Label::INSERT();
			$label->setText('Hyperlink');
			$row->setLabel($label);
			return $row;
		}
		
		
		public function getFile() {
			$array = unserialize(parent::getFile());
			if (!is_array($array) || !count($array))
				return null;
			
			$id = ControllerAbstract::$language;
			if (array_key_exists($id, $array)) {
				return File::ROW($array[$id]);
			} else {
				reset($array);
				return File::ROW(current($array));
			}
		}
		
		public function setFile($value) {
			if (is_object($value) && $value->getClassName() == 'File') {
				$language = ControllerAbstract::$language;
				$id = 1;
				if (is_object($language))
					$id = $language->getId();
				
				$array = unserialize(parent::getFile());
				if (!is_array($array)) {
					$array = array();
				}
				
				$array[$id] = strip_tags($value->getId());				
				
				parent::setFile(serialize($array));
			}
		}		
				
	}
