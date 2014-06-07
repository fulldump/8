<?php

	require_once ('class/ControllerAbstract.class.php');
	
	class ControllerPage extends ControllerAbstract {
	
		public static $page=null;

		public static $title = '';
		public static $keywords = '';
		public static $description = '';
		
		public static $html = '';
		public static $css = '';
		public static $js = '';
		
		public static $error_404 = false;

		private static $components_loaded = array();
		
		public static function compile() {

			$if_modified_since = '';
			if (Config::get('IF_MODIFIED_SINCE_ENABLED')) {
				header('Last-Modified: '.date('r', self::$node->getLastModified()));
				$time = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
				if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&  $time>=self::$node->getLastModified() ) {
					header("HTTP/1.1 304 Not Modified");
					exit(0);
				}
			}

			self::$page = SystemPage::ROW(self::$node->getReference());
			self::$title = self::$node->getTitle();
			self::$keywords = self::$node->getKeywords();
			self::$description = self::$node->getDescription();
			
			self::$html = '';
			self::$css = '';
			self::$js = '';
			
			self::$components_loaded = array();
			
			$error_invalid_page_reference = false;

			if (is_null(self::$page)) {
				// Error: referencia a página no válida
				$error_invalid_page_reference = true;
			} else {
				self::$page->render();
			}

			// MODIFICACIONES DE LA VISTA
			include(self::$view->getFilename());
			
			// Inicializo el contexto
			self::$html = 
'<?php
	ControllerPage::$page = SystemPage::ROW('.self::$page->getId().');
	ControllerAbstract::$node = SystemRoute::ROW('.self::$node->getId().');
	ControllerAbstract::$url = '.var_export(self::$url,true).';
	ControllerAbstract::$language = \''.ControllerAbstract::$language.'\';
	header(\'Expires: '.date('r', time()+5).'\');
	'.$if_modified_since.'
?>'.self::$html;

			
			ob_start();
			
			eval('?>'.self::$html.'<?');
			
			if (count(self::$url) || $error_invalid_page_reference) {
				// Todavía quedan parámetros sin procesar
				self::$error_404 = true;
				header("HTTP/1.0 404 Not Found");
				self::$url = array();
				ob_clean();
				self::$node = SystemRoute::ROW(Config::get('404_PAGE'));
				self::compile();
			} else {
				// CACHEO EL CONTENIDO:
				if (!self::$error_404 && Config::get('CACHE_ENABLED')) {
					Cache::add(self::$path, self::$html);
				}
			}
		}
		
		public static function appendCSS($css) {
			self::$css .= $css;
		}
		
		public static function appendHTML($html) {
			self::$html .= $html;
		}
		
		public static function appendJS($js, $component = null) {
			$tokens = TreeScript::getParse($js);
			foreach ($tokens as $token) {
				if ($token['type'] == 'tag' && $token['name'] == 'INCLUDE') {
					self::requireComponent($token['data']['component']);
				} else if ($token['type'] == 'tag' && $token['name'] == 'AJAX') {

					if (ControllerAbstract::$language != Config::get('DEFAULT_LANGUAGE')) {
						self::$js .= '/'.ControllerAbstract::$language;
					}
					
					self::$js .= '/__ajax__/'.$component.'/'.$token['data']['name'];
				} else if ($token['type'] == 'text'){
					self::$js .= $token['data'];
				}
            }
		}
		
		public static function requireComponent($name) {
			if (!array_key_exists($name, self::$components_loaded)) {
				// Busco el componente:
				$component = SystemComponent::getComponentByName($name);
				if (null === $component) {
					// Error
					self::$components_loaded[$name]="error";
				} else {
					self::$components_loaded[$name]=true;
					self::appendJS($component->getJS(), $name);					
					self::$css = $component->getCSS().self::$css;
				}
			} 
		}
		
	}
