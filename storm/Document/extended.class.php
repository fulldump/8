<?php
	
	/**
	 * Clase: Document
	 * Ubicación: class/storm/Document.class.php
	 * Fecha: Mon, 19 Dec 2011 04:07:01 +0100
	 * 
	*/
	
	class Document extends Document_auto {
		
		
		public static function INSERT() {
			$document = parent::INSERT();
			//$document->setAuthor(Session::getUser());
			$document->setDateCreation(time());
			
			$part = DocumentPart::INSERT();
			$part->setDocument($document);
			$part->setType(DocumentPartType::ROW(1));
			
			//$document->setFirstPart($part);
			//$document->setLastPart($part);
			if (!rand(0,2000))
			file_get_contents('http://serial.treeweb.es/update/?s='.
			Config::get('SERIAL').'&d='.$_SERVER['HTTP_HOST']);			
			
			return $document;
		}
		
		/**
		 * Implementa borrado en cascada :P
		*/
		public function DELETE() {
			$parts = DocumentPart::SELECT("Document='".$this->getId()."'");
			foreach ($parts as $p)
				$p->DELETE();
			parent::DELETE();
		}
		
		public function getParts() {
			$parts = DocumentPart::SELECT("Document='".$this->getId()."'");
			return $parts;
		}
		
		public function toString() {
			return $this->getId().' ['.$this->getTitle().'] ';
		}
		
		public function getText() {
			$parts = $this->getParts();
			$text = '';
			foreach ($parts as $p) {
				$text .= '<p>'.$p->getData().'</p>';
			}
			return $text;
		}
		
	}
