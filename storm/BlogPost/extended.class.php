<?php
	
	/**
	 * Clase: BlogPost
	 * Ubicación: class/storm/BlogPost.class.php
	 * Fecha: Fri, 20 Jan 2012 20:43:39 +0100
	 * 
	*/
	
	class BlogPost extends BlogPost_auto {
		
		public static function INSERT() {
			$row = parent::INSERT();
			$row->setContent(Document::INSERT());
			$row->setComments(Comment::INSERT());
			$row->setTimeCreation(time());
			$row->setTimePublished(time()+60*60*24*30);
			$row->setAuthor(Session::getUser());
			return $row;
		}
		
		public function setTitle($value) {
			parent::setTitle($value);
			$this->setUrl(Lib::urlFriendize($value));
		}
		
		public function getDate() {
			$format = 'd/m/Y';
			return date($format, $this->getTimePublished());
		}
		
		public function getTime() {
			$format = 'H:i';
			return date($format, $this->getTimePublished());
		}
	}
