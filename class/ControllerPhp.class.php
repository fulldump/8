<?php

	
	class ControllerPhp {
	
		private $router = null;
		private $php = null;

		private function __construct($router) {
			$this->router = $router;
			

			$this->php = SystemPhp::get($router->node->getProperty('reference'));

			if (null !== $this->php) {
				include($this->php->getFilename());
			}
		}

		public static function compile($router) {
			
			return new ControllerPhp($router);

		}

	}
