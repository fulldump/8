<?php
	
	class ControllerPage {

		private $page = null;

		private $title = '';
		private $keywords = '';
		private $description = '';
		
		private $html = '';
		private $css = '';
		private $js = '';
		
		private $components_loaded = array();
		
		private function __construct() {
			$this->initialize(Router::$node);
		}

		public function render_view() {
			// MODIFICACIONES DE LA VISTA (advertencia: alto contenido alucinógeno)

			$this->css = '<?php header(\'Content-Type: text/css; charset=UTF-8\'); header("Expires: ".date("r", time()+9999999)); ?>'.$this->css;

			$this->js = '<?php header(\'Content-Type: text/javascript; charset=UTF-8\'); header("Expires: ".date("r", time()+9999999)); ?>'.$this->js;

			$hash_css = md5($this->css);
			Cache::add('/cache-css/'.$hash_css, $this->css);

			$hash_js = md5($this->js);
			Cache::add('/cache-js/'.$hash_js, $this->js);

			$ga = Config::get('GOOGLE_ANALYTICS');
			if (strlen($ga)) $ga = "\n\t\t".$ga;

			$this->html = '<?php
	header(\'Content-Type: text/html; charset=UTF-8\');
	ob_start();?>'.$this->html.'<?php
	$_HTML = ob_get_clean();
?><!DOCTYPE HTML>
<html lang="'.Router::$language.'">
	<head>
		<meta http-equiv="Content-Type" CONTENT="text/html; charset=UTF-8">
		<title>'.htmlentities($this->title, ENT_COMPAT, 'UTF-8').'</title>
		<meta name="keywords" content="'.htmlentities($this->keywords, ENT_COMPAT, 'UTF-8').'">
		<meta name="description" content="'.htmlentities($this->description, ENT_COMPAT, 'UTF-8').'">
		<meta name="apple-touch-fullscreen" content="YES">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" type="text/css" href="/cache-css/'.$hash_css.'" title="default">
		<script src="/cache-js/'.$hash_js.'" type="text/javascript"></script>
		<link href="/favicon.ico" rel="icon" type="image/x-icon">'.$ga.'
	</head>
	<body>
<?php echo $_HTML; ?> 
	</body>
</html>';

		}

		public function render_template() {
			$template = SystemTemplate::get(Router::$node->getProperty('template'));
			if (null === $template) {
				$template = SystemTemplate::get(Config::get('DEFAULT_TEMPLATE'));
			}

			$html = $template->getPHP();
			$tokens = TreeScript::getParse($html);
			$text = '';
			foreach ($tokens as $i=>$token) {
				if ($token['type'] == 'tag' && $token['name'] == 'BODY') {
					$text .= $this->html;
				} else {
					$this->tokenDefault($token, $text);
				}
			}


			$this->html = $text;
			$this->css .= $template->getCSS();
			$this->appendJS($template->getJS());
		}

		private function initialize($node) {

			$this->title = '';
			$this->keywords = '';
			$this->description = '';
			$this->html = '';
			$this->css = '';
			$this->js = '';

			$is_404 = null === $this->page;

			$this->page = SystemPage::get($node->getProperty('reference'));

			if (null === $this->page) {
				http_response_code(404);
				$this->initialize(Router::$root->getById(Config::get('404_PAGE')));
				return;
			}

			$this->title = $node->getProperty('title');
			$this->keywords = $node->getProperty('keywords');
			$this->description = $node->getProperty('description');

			$this->render_page();

			$this->render_view();

			ob_start();

			eval('?>'.$this->html);

			if(count(Router::$parts)) {
				ob_end_clean();
				Router::$parts = array();
				http_response_code(404);
				$this->initialize(Router::$root->getById(Config::get('404_PAGE')));
				return;
			}

			if (Config::get('CACHE_ENABLED')) {
				Cache::add(Router::$url, Router::export().$this->html);
				$filename = 'cache/'.md5(Router::$url);
				file_put_contents($filename, php_strip_whitespace($filename));
			}
		}

		private function render_page() {
			$html = $this->page->getPHP();

			$tokens = TreeScript::getParse($html);

			$text = '';
			foreach ($tokens as $token)
				$this->tokenDefault($token, $text);
			
			$this->html .= $text;

			$this->render_template();

			$this->css .= $this->page->getCSS();
			$this->appendJS($this->page->getJS());
		}

		private function tokenDefault(&$token, &$text) {
			if ($token['type'] == 'text') {
				$text .= $token['data'];
			} else if ($token['type'] == 'tag' && strtoupper($token['name']) == 'COMPONENT') {
				$name = $token['data']['name'];
				
				$component = SystemComponent::getComponentByName($name);
				if ($component !== null) {
					$text .= '<?php $data = '.var_export($token['data'], true).'; $flags = '.var_export($token['flags'], true).'; ?>';
					
					$html = $component->getPHP();
					$ctokens = TreeScript::getParse($html);
					$ctext = '';
					foreach ($ctokens as $ctoken){
						$this->tokenDefault($ctoken, $ctext);
					}
					$text .= $ctext;
					
					$this->requireComponent($name);
				}
			}
		}

		public static function compile() {
			return new ControllerPage();
		}
		
		private function appendJS($js, $component = null) {
			$tokens = TreeScript::getParse($js);
			foreach ($tokens as $token) {
				if ($token['type'] == 'tag' && $token['name'] == 'INCLUDE') {
					$this->requireComponent($token['data']['component']);
				} else if ($token['type'] == 'tag' && $token['name'] == 'AJAX') {

					if (Router::$language != Config::get('DEFAULT_LANGUAGE')) {
						$this->js .= '/'.Router::$language;
					}
					
					$this->js .= '/__ajax__/'.$component.'/'.$token['data']['name'];
				} else if ($token['type'] == 'text'){
					$this->js .= $token['data'];
				}
			}
		}
		
		private function requireComponent($name) {
			if (!array_key_exists($name, $this->components_loaded)) {
				// Busco el componente:
				$component = SystemComponent::getComponentByName($name);
				if (null === $component) {
					// Error
					$this->components_loaded[$name]="error";
				} else {
					$this->components_loaded[$name]=true;
					$this->appendJS($component->getJS(), $name);					
					$this->css = $component->getCSS().$this->css;
				}
			}
		}
	}
