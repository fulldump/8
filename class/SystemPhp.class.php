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
		
	class SystemPhp {

		protected static $dir_base = 'php';
		
		protected static $data = array();
		protected $name = '';
		protected $php = null;
		
		public function __construct($name) {
			$this->name = $name;
		}

		public static function SELECT() {
			$select = array();

			$path = self::$dir_base.'/';
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
		
		/**
		 * VULNERABILITY: User could create files outside the folder 'component/'
		*/
		public static function INSERT($name) {
			$filename = self::$dir_base.'/'.$name.'.php';

			if (file_exists($filename)) {
				return null;
			} else {
				file_put_contents($filename, "<?php /* Your PHP here */ \n print_r($_POST); ?>"); chmod($filename, 0777);
				return self::get($name);
			}
		}
		
		public function DELETE($physical=true) {
			// TODO: check if this is being used
			// TODO: remove
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getPHP() {
			if (null === $this->php) {
				$this->php = file_get_contents(self::$dir_base.'/'.$this->name.'.php');
			}
			return $this->php;
		}
		
		public function setPHP($code) {
			return file_put_contents(self::$dir_base.'/'.$this->name.'.php', $code);
		}

		public static function get($name) {
			if (!array_key_exists($name, self::$data)) {
				$filename = self::$dir_base.'/'.$name.'.php';
				if (file_exists($filename)) {
					self::$data[$name] = new SystemPhp($name);
				} else {
					return null;
				}
			}
			return self::$data[$name];
		}

		public function getFilename() {
			return self::$dir_base.'/'.$this->name.'.php';
		}

	}
