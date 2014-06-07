<?php
	
	/**
	 * Class: SystemComponent
	 * Location: class/storm/SystemComponent.class.php
	 * Date: Sun, 28 Aug 2011 02:03:33 +0200
	 * Author: gerardooscarjt@gmail.com
	 * Typical use:
	 * $my_component = SystemComponent::INSERT('my_component');
	 * $my_component->setPHP('<? echo 'Hello world'; ?>');
	 * $my_component->setCSS('* { color: red; }');
	 * $my_component->setJS('alert(99)');
	 * ...
	*/

	class SystemComponent {

		protected static $dir_base = 'component';
		protected static $dir_ajax = 'ajax';
		
		protected static $data = array();
		protected $name = '';
		protected $php = null;
		protected $css = null;
		protected $js = null;
		protected $ajax = null;
		protected $validation = null;
		
		public function __construct($name) {
			$this->name = $name;
		}

		public static function SELECT() {
			$select = array();

			$path = self::$dir_base.'/';
			$d = dir($path);
			while (false !== ($entry = $d->read())) {
				$path_entry = $path.$entry.'/';
				if (is_dir($path_entry) && '.' !== $entry && '..' !== $entry) {
					self::getcomponentByName($entry);
					$select[] = &self::$data[$entry];
				}
			}
			$d->close();

			return $select;
		}
		
		/**
		 * VULNERABILITY: User could create files outside the folder 'component/'
		*/
		public static function INSERT($name) {
			$path = self::$dir_base.'/'.$name.'/';

			if (file_exists($path)) {
				return null;
			} else {
				mkdir($path);
				chmod($path, 0777);
				file_put_contents($path.'index.php', '<?php /* Your PHP here */?>'); chmod($path.'index.php', 0777);
				file_put_contents($path.'index.css', '/* Your CSS here */'); chmod($path.'index.css', 0777);
				file_put_contents($path.'index.js', '/* Your JS here */'); chmod($path.'index.js', 0777);
				file_put_contents($path.'validation.php', '/* Your validation PHP code here */'); chmod($path.'validation.php', 0777);

				$path_ajax = $path.self::$dir_ajax.'/';
				mkdir($path_ajax);
				chmod($path_ajax, 0777);

				return self::getComponentByName($name);
			}
		}
		
		public function DELETE($physical=true) {
			// TODO: check if this component is being used
			// TODO: remove the component
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getJS() {
			if (null === $this->js) {
				$this->js = file_get_contents(self::$dir_base.'/'.$this->name.'/index.js');
			}
			return $this->js;
		}
		
		public function getPHP() {
			if (null === $this->php) {
				$this->php = file_get_contents(self::$dir_base.'/'.$this->name.'/index.php');
			}
			return $this->php;
		}
		
		public function setCSS($code) {
			$this->css = $code;
			return file_put_contents(self::$dir_base.'/'.$this->name.'/index.css', $code);
		}
		
		public function getCSS() {
			if (null === $this->css) {
				$this->css = file_get_contents(self::$dir_base.'/'.$this->name.'/index.css');
			}
			return $this->css;
		}
		
		public function setValidation($value) {}
		
		public function getValidation() {
			if (null === $this->validation) {
				$this->validation = file_get_contents(self::$dir_base.'/'.$this->name.'/validation.php');
			}
			return $this->validation;
		}

		public static function getComponentByName($name) {
			return self::get($name);
		}

		public static function get($name) {
			if (!array_key_exists($name, self::$data)) {
				$path = self::$dir_base.'/'.$name.'/';
				if (file_exists($path)) {
					self::$data[$name] = new SystemComponent($name);
				} else {
					return null;
				}
			}
			return self::$data[$name];
		}
		
		public function setPHP($html) {
			// TODO: Corregir sintaxis...
			
			$tokens = TreeScript::getParse($html);
			$code = '';
			foreach ($tokens as $token) {
				if ($token['type'] == 'text') {
					$code .= $token['data'];
				} else {
					if (strtoupper($token['name']) == 'COMPONENT') {
						$name = $token['data']['name'];
						$component = SystemComponent::get($name);
						if ($component === null) {
							$token['data']['error'] = 'Component "'.$name.'" does not exists.';
							// El componente no existe :S
						} else {
							self::validateHTML($token, $component->getValidation());
						}
					}
					$code .= RenderToken::tokenToString($token);
				}
			}
			$this->php = $code;
			return file_put_contents(self::$dir_base.'/'.$this->name.'/index.php', $code);
		}
		
		private static function validateHTML(&$token, $code) {
			eval($code);
		}
		
		public function setJS($js) {
			//Procesar antes el js
			$tokens = TreeScript::getParse($js);
			$code = '';
						
			foreach ($tokens as $token) {
				if ($token['type'] == 'text') {
					$code .= $token['data'];
				} else {
					if (strtoupper($token['name']) == 'AJAX') {
						$name = $token['data']['name'];
						$autogenerate_list_ajax[$name] = '';
						if (strlen($name)) {
							if (!in_array($name, $this->getAjaxNames())) {
								$this->setAjax($name, '<?php print_r($_POST); ?>');
							}
						} else {
							$token['data']['warning'] = 'Missing attribute "name"';
						}
					}
					$code .= RenderToken::tokenToString($token);
				}
			}
			$this->js = $code;
			return file_put_contents(self::$dir_base.'/'.$this->name.'/index.js', $code);
		}

		public function getAjax($name) {
			return file_get_contents(self::$dir_base.'/'.$this->getName().'/'.self::$dir_ajax.'/'.$name.'.php');
		}

		public function setAjax($name, $value) {
			$path = self::$dir_base.'/'.$this->getName().'/'.self::$dir_ajax.'/'.$name.'.php';
			file_put_contents($path, $value);
			chmod($path, 0777);
		}

		public function getAjaxNames() {
			$select = array();

			$path = self::$dir_base.'/'.$this->name.'/'.self::$dir_ajax.'/';
			$d = dir($path);
			while (false !== ($entry = $d->read())) {
				$entry = pathinfo ($entry);
				if ('php' == $entry['extension']) {
					$select[] = $entry['filename'];
				}
			}
			$d->close();

			return $select;
		}
		
	}
