<?php

	require_once ('class/ControllerAbstract.class.php');
	
	class ControllerPhp extends ControllerAbstract {
	
		public static $php=null;

		public static function compile() {
			self::$php = SystemPhp::get(self::$node->getReference());
			
			if (null === self::$php) {
				// TODO: ERROR 500
			} else {
				include(self::$php->getFilename());
			}

		}
		
	}
