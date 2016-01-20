<?php
	
	/**
	 * Clase: Image
	 * Ubicación: storm/Image/extended.class.php
	 * Fecha: Mon, 19 Dec 2011 03:47:44 +0100
	 * 
	*/
	
	class Image extends Image_auto {
		// TODO: Parametrizar el directorio de imágenes
		
		/**
		 *  Para insertar un nuevo registro, debo pasar la ruta de
		 *  una imagen válida (puede ser de un archivo local o uno remoto con http://...)
		*/
		public static function INSERT($image_path) {
			// Compruebo si el archivo es en realidad una imagen:
			
			//$finfo = finfo_open(FILEINFO_MIME_TYPE);
			//$mime = finfo_file($finfo, $image_path);

			$temp_hash = md5(microtime());
			Rack::Write('temp', $temp_hash, $image_path);
			$temp_path = Rack::Path('temp', $temp_hash);
			
			$is = getimagesize($temp_path);
			$mime = $is['mime'];
			
			switch ($mime) {
				case 'image/jpeg':
					$gd = @imagecreatefromjpeg($temp_path);
					break;
				case 'image/png':
					$gd = @imagecreatefrompng($temp_path);
					break;
				case 'image/gif':
					$gd = @imagecreatefromgif($temp_path);
					break;
				case 'image/bmp':
					$gd = @imagecreatefrombmp($temp_path);
					break;
				default:
					return null;
			}
			
			if (is_resource($gd)) {
				$width = imagesx($gd);
				$height = imagesy($gd);
				$hash = md5_file($temp_path);
				$list = Image::SELECT("Hash='".Database::escape($hash)."'");
				if (count($list)) {
					// La imagen ya existe :S
					$image = $list[0];
					$image->_setCounter($image->getCounter()+1);
				} else {
					// Creo un nuevo registro de imagen :)
					$image = parent::INSERT();
					$image->_setWidth($width);
					$image->_setHeight($height);
					$image->_setMime($mime);
					$image->_setHash($hash);
					$image->_setSize(@filesize($temp_path));
					$image->_setCounter(1);
					// Copiar imagen a la carpeta de imágenes con el id de $image->getId(); (o con el hash)
					Rack::Write('img', md5($image->ID()), $temp_path);
				}
				Rack::Remove('temp', $temp_hash);
				return $image;
			} else {
				// Error al abrir la imagen
				Rack::Remove('temp', $temp_hash);
				return null;
			}
		}
		
		/**
		 * Sólo borro la imagen cuando nadie más la utiliza
		*/
		public function DELETE($physical = true) {
			if (intval($this->getCounter())==1) {
				$image_id = $this->ID();
				parent::DELETE($physical = true);
				Rack::Remove('img', md5($image_id));
				// TODO: eliminar imagenes de la cache
			} else {
				// Decremento el contador
				$this->_setCounter($this->getCounter()-1);
			}
		}
		
		// Hago los campos mime, hash, width y height de sólo lectura:
		public function setWidth($value) {}
		public function setHeight($value) {}
		public function setMime($value) {}
		public function setHash($value) {}
		public function setSize($value) {}
		public function setCounter($value) {}
		
		
		
		// Hago los campos mime, hash, width y height privados:
		private function _setWidth($value) {
			parent::setWidth($value);
		}
		
		private function _setHeight($value) {
			parent::setHeight($value);
		}
		
		private function _setMime($value) {
			parent::setMime($value);
		}
		
		private function _setHash($value) {
			parent::setHash($value);
		}
		
		private function _setSize($value) {
			parent::setSize($value);
		}
		
		private function _setCounter($value) {
			parent::setCounter($value);
		}
		
		public function toString() {
			return "/img/{$this->getId()}";
		}
	}
