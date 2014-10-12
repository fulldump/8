<?php

	/**
	 * Class: SystemRoute
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemRoute extends SystemRoute_auto {

		public static function INSERT() {
			$route = parent::INSERT();
			$route->setTitle('New Page');
			
			return $route;
		}
		
		public static function getRoot() {
			return parent::ROW(1);
		}
		
		public function DELETE($physical = true) {
			if ($this->getId() != 1)
				return parent::DELETE($physical);
			return false;
		}
		
		public function getTitle() {
			$array = unserialize(parent::getTitle());
			if (!is_array($array) || !count($array)) {
				return '';
			}
			
			// $language = ControllerAbstract::$language;
			// if (array_key_exists($language, $array)) {
			// 	return $array[$language];
			// } else {
				reset($array);
				return current($array);
			// }
		}		
						
		public function setTitle($title) {		
			$url = Lib::urlFriendize($this->getTitle());
					
			$language = ControllerAbstract::$language;
				
			$array = unserialize(parent::getTitle());
			if (!is_array($array)) {
				$array = array();
			}
		
			$array[$language] = strip_tags($title);
			parent::setTitle(serialize($array));
		
		
			$this->Modify();			
			
			if ($url == $this->getUrl()) {
				$i = 0;
				$url_friendly = Lib::urlFriendize($this->getTitle());
				while (count(SystemRoute::SELECT("Url='".mysql_real_escape_string($url_friendly)."' AND NOT id='".mysql_real_escape_string($this->getId())."'"))) {
					$i++;
					$url_friendly = $url.'-'.$i;
				}
				parent::setUrl($url_friendly);
			}
		}
		
		// TODO: Ordenar los hijos descendientes en función de los enlaces siguiente y anterior
		public function getChildren() {
			return parent::SELECT("Parent='".mysql_real_escape_string(parent::getId())."'");
		}
		
		public function getChildByUrl($url) {
			$list = parent::SELECT("Parent='".mysql_real_escape_string(parent::getId())."' AND `Url`='".mysql_real_escape_string($url)."'");
			if (count($list)) {
				return $list[0];
			} else {
				return null;
			}
		}
		
		public function isRoot() {
			return $this->getId() == 1;
		}
		
		public function isDefault() {
			return $this->getId() == Config::get('DEFAULT_PAGE');
		}
		
		public function getPath($edit=false) {
			$path = '/';
			
			$node = $this;
			while (!$node->isRoot() && !$node->isDefault()) {
				$path ='/'.$node->getUrl().$path;
				$node = $node->getParent();
			}
			
			if (ControllerAbstract::$view!== null && ControllerAbstract::$view->getName() != Config::get('DEFAULT_VIEW'))
				$path = '/'.ControllerAbstract::$view->getURL().$path;


			if (ControllerAbstract::$language!==null && ControllerAbstract::$language != Config::get('DEFAULT_LANGUAGE'))
				$path = '/'.ControllerAbstract::$language.$path;
			
			

			if ($edit && Lib::editingMode())
				$path .= '?edit';			
			return $path;
		}
		
		public function Modify() {
			$this->setLastModified(time());
		}

		public function setDescription($value) {
			$language = ControllerAbstract::$language;
				
			$array = unserialize(parent::getDescription());
			if (!is_array($array)) {
				$array = array();
			}
		
			$array[$language] = strip_tags($value);
			parent::setDescription(serialize($array));
			$this->Modify();
		}
		
		public function getDescription() {
			$array = unserialize(parent::getDescription());
			if (!is_array($array) || !count($array))
				return '';
			
			$language = ControllerAbstract::$language;
			if (array_key_exists($language, $array)) {
				return $array[$language];
			} else {
				reset($array);
				return current($array);
			}
		}


		public function setKeywords($value) {
			parent::setKeywords($value);
			$this->Modify();
		}

		
		/*
		public function &appendChild(&$child) {			
			if (is_object($child) && $child->getClassName() == 'Route') {
				
				// Saco a child de la lista enlazada
				$previous = $child->getPrevious();
				$next = $child->getNext();
				// Si el nodo anterior existe:
				if (!is_null($previous)) $previous->setNext($next);
				// Si el nodo siguiente existe:
				if (!is_null($next)) $next->setPrevious($previous);
				$child->setNext(null);
				$child->setPrevious(null);
				
				// Busco el último nodo (si existe)
				$last_child_list = SystemRoute::SELECT('Parent='.$this->getId().' AND Next=0');
				if (count($last_child_list)) {
					$last_child = $last_child_list[0];
					$last_child->setNext($child);
					$child->setPrevious($last_child);
				}
				
				$child->setParent($this); // Última línea
				
				return $child;
			} else {
				return null;
			}
		}*/
		
		public function toString() {
			return $this->getUrl();
		}

	}
