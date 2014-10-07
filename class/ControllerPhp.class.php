<?php

	
	class ControllerPhp {
	
		private $php = null;

		private function __construct() {
			$this->php = SystemPhp::get(Router::$node->getProperty('reference'));

			if (null !== $this->php) {
				include($this->php->getFilename());
			}
		}

		public static function compile() {
			
			return new ControllerPhp();

		}

	}
