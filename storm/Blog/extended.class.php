<?php
	
	/**
	 * Clase: Blog
	 * Ubicación: class/storm/Blog.class.php
	 * Fecha: Fri, 20 Jan 2012 20:42:51 +0100
	 * 
	*/
	
	class Blog extends Blog_auto {
		
		public static function INSERT() {
			$row = parent::INSERT();
			$row->setTimeCreation(time());
			$row->setAuthor(Session::getUser());
			
			// Insert a demo post:
			$post = $row->newPost();
			$post->setTitle('Demo');
			
			
			return $row;
		}
		
		public function getPosts($all=false) {
			$where = "Blog ='".Database::escape($this->getId())."' ORDER BY TimeCreation DESC";
			if (!$all)
				$where = "TimePublished < '".time()."' AND ".$where;
			return BlogPost::SELECT($where);
		}
		
		public function newPost() {
			$row = BlogPost::INSERT();
			$row->setBlog($this);
			return $row;
		}
		
		public function toString() {
			return $this->getTitle();
		}
		
		public function getPostByUrl($url) {
			$where = "Blog ='".Database::escape($this->getId())."' AND Url = '".Database::escape($url)."'";
			$lista = BlogPost::SELECT($where);
			if (count($lista))
				return $lista[0];
			return null;
		}
	}
