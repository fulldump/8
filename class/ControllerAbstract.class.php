<?php

	abstract class ControllerAbstract {
		
		public static $scheme;
		public static $host;
		public static $path;
		public static $language;
		public static $view;
		public static $node;
		public static $hash;
		public static $controller;
		public static $url;
		public static $cached = false; // TODO: 
		
		
		static function setUrl($url) {
			self::$path = $url;
			self::$url = explode('/',$url); array_shift(self::$url);
			self::$host = $_SERVER['SERVER_NAME'];
			self::$scheme = explode('/', $_SERVER['SERVER_PROTOCOL']);
			self::$scheme = strtolower(self::$scheme[0]);
		
			// Anexo los parámetros get opcionalmente
			if (false /*Config::get URL_HASH_QUERY*/) {
				ksort($_GET);
				$url .= '?';
				foreach ($_GET as $G=>$g) {
					$url.=$G.'='.$g.'&';
				}
			}
			
			
			self::$hash = md5($url);
			
			// TODO: meter un parámetro de configuración 'CACHE_DIR'=cache/
			if (file_exists('cache/'.self::$hash)) {
				self::$cached = true;
				include('cache/'.self::$hash);
			} else {
				// Determino el idioma
				$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));
				if(in_array(self::$url[0], $available_languages) && self::$url[0]!=Config::get('DEFAULT_LANGUAGE')) {
					self::$language = self::$url[0];
					array_shift(self::$url);
				} else {
					self::$language = Config::get('DEFAULT_LANGUAGE');
				}
				header('Content-Language: '.self::$language);
				
				// Determino la vista
				self::$view = SystemView::get(self::$url[0]);
				if(is_null(self::$view) || self::$url[0]==Config::get('DEFAULT_VIEW')) {
					self::$view = SystemView::get(Config::get('DEFAULT_VIEW'));
				} else {
					array_shift(self::$url);
				}
				
				// Determino el nodo en el que empiezo a buscar:
				self::$node = SystemRoute::ROW(Config::get('DEFAULT_PAGE'));
				if (self::$node->getChildByUrl(self::$url[0])===null) {
					$root = SystemRoute::getRoot();
					$root_child = $root->getChildByUrl(self::$url[0]);
					if ($root->getChildByUrl(self::$url[0])!==null
						&& $root_child->getId() != self::$node->getId()) {
						self::$node = $root;
					}
				}
				
				
				// Busco las rutas a partir del nodo elegido:
				$new_node = self::$node;
				while( count(self::$url) && !is_null($new_node) ) {
					$new_node = $new_node->getChildByUrl(self::$url[0]);
					if (!is_null($new_node)) {
						self::$node = $new_node;
						array_shift(self::$url);
					}
				}
				
				// Redirijo si no acaba en '/' y tiene hijos
				if (!count(self::$url) && count(self::$node->getChildren()) ) {
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: '.self::$node->getPath());
				} else if (count(self::$url)==1 && self::$url[0]=='') {
					array_shift(self::$url);
				}
				
				// Obtengo el controlador
				self::$controller=self::$node->getController();
				
				// Proceso el controlador correspondiente
				switch (self::$controller) {
					case 'page':
						ControllerPage::compile();
						break;
					case 'php':
						echo ControllerPhp::compile();
						break;
				}


			}
		}
		
		static public function print_r() {
			echo 'Scheme: '.self::$scheme.'<br>';
			echo 'Host: '.self::$host.'<br>';
			echo 'Path: '.self::$path.'<br>';
			echo 'Language: '.print_r(self::$language, true).'<br>';
			echo 'View: '.print_r(self::$view->getName(), true).'<br>';
			echo 'Node: '.print_r(self::$node->getPath(), true).'<br>';
			echo 'Hash: '.self::$hash.'<br>';
			echo 'Controller: '.self::$controller->getName().'<br>';
			echo 'Url: '.print_r(self::$url, true).'<br>';
		} 
		
	}
