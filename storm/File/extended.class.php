<?php
	
	/**
	 * Clase: File
	 * Ubicación: storm/File.class.php
	 * Fecha: Tue, 12 Jun 2012 21:35:55 +0200
	 * 
	*/
	
	class File extends File_auto {
		// TODO: Parametrizar el directorio de ficheros
		
		/**
		 *  Para insertar un nuevo registro, debo pasar la ruta de
		 *  un archivo (puede ser de un archivo local o uno remoto con http://...)
		*/
		public static function INSERT($file_path, $mime) {
		

			$hash = md5_file($file_path);
			$list = File::SELECT("Hash='".mysql_real_escape_string($hash)."'");
			if (count($list)) {
				$file = $list[0];
				$file->_setCounter($file->getCounter()+1);
			} else {
				$file = parent::INSERT();
				$file->_setMime($mime);
				$file->_setHash($hash);
				$file->_setSize(filesize($file_path));
				$file->_setCounter(1);
				$file->setUser(Session::getUser());
				$file->setTimestamp(time());
				Rack::Write('file', md5($file->ID()), $file_path);
				$file->updateSearchIndex();
			}
			
			return $file;
		}
		
		public function setName($value) {
			$result = parent::setName($value);
			$this->updateSearchIndex();
			return $result;
		}

		public function setDescription($value) {
			$result = parent::setDescription($value);
			$this->updateSearchIndex();
			return $result;
		}

		/**
		 * Sólo borro el archivo cuando nadie más lo utiliza
		*/
		public function DELETE() {
			if (intval($this->getCounter())==1) {
				// Eliminar imagen y toda la cache
				unlink('files/'.$this->getId());
				parent::DELETE();
			} else {
				// Decremento el contador
				$this->_setCounter($this->getCounter()-1);
			}
		}
		
		// Hago los campos mime, hash, size y counter de sólo lectura:
		public function setMime($value) {}
		public function setHash($value) {}
		public function setSize($value) {}
		public function setCounter($value) {}
		
		// Actualizo el índice de búsqueda
		public function updateSearchIndex() {
			$si = '';
			$si .= ' '.$this->getMime();
			$si .= ' '.$this->getName();
			$si .= ' user:'.$this->getUser()->getName();
			$si .= ' '.date('r',$this->getTimestamp());
			$si .= ' '.$this->getDescription();
			$this->setSearchIndex($si);
		}

		// Hago los campos mime, hash, size y counter privados:
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
			return '<div style="font-size:10px;">'.$this->getMime().'<br>Size:'.$this->getSize().'B</div>';
		}
	}
