<?php
	
	/**
	 * Documentar esto
	 *
	 * autor: gerardooscarjt@gmail.com
	 * fecha: 27/11/2010
	*/
	
	class FileStore {
		
		static private $data = null;
		const file = '.file_store';
		
		/**
		 * Documentar esto
		*/
		public static function read($key) {
			self::loadData();
			if (isset(self::$data[$key]))
				return self::$data[$key];
			return null;
		}
		
		/**
		 * Documentar esto
		*/
		public static function write($key, &$data) {
					
			self::loadData();
			self::$data[$key]=$data;
			file_put_contents(self::file,serialize(self::$data));
		}
		
		/**
		 * Documentar esto
		*/
		public static function remove($key) {
			self::loadData();
			unset(self::$data[$key]);
			file_put_contents(self::file,serialize(self::$data));
		}
		
		/**
		 * Documentar esto
		*/
		private static function loadData() {
			if (self::$data == null) {
				if (file_exists(self::file)) {
					self::$data = unserialize(file_get_contents(self::file));
				} else {
					self::$data = array();
				}
			}
		}
		
		/**
		 * Documentar esto
		*/
		public static function print_r() {
			echo '<pre>';
			print_r(self::$data);
			echo '</pre>';
		}
		
	}	
