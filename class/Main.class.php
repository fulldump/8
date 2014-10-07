<?php

	/**
	 * Class: Main
	 * Location: /class/Main.class.php
	 * Description:
	 * Typical use:
	 *		Main::go(); 
	 *
	 * autor: gerardooscarjt@gmail.com
	 * date: 2011/07/17
	*/
	
	class Main {

		public static $cwd = '';

		private static function initializeCwd() {
			self::$cwd = getcwd().'/';
		}

		/**
		 * Fuerza una conexión a base de datos. El motivo es tener disponible el
		 * método mysql_real_escape_string
		*/
		private static function forceDatabaseConnection() {
			Database::getInstance();
		}

		private static function sessionStart() {
			Session::getSessionId();
		}

		private static function magicQuotes() {
			if (get_magic_quotes_gpc())
				$_POST = array_map('stripslashes_deep', $_POST);
			
			function stripslashes_deep($value) {
				$value = is_array($value) ?
							array_map('stripslashes_deep', $value) :
							stripslashes($value);
				return $value;
			}
		}

		private static function autoload() {

			function __autoload($class_name) {
				$path = Main::$cwd.'class/'.$class_name.'.class.php';
				if (file_exists($path)) {
					include_once($path);
				} else {
					include_once(Main::$cwd.'storm/'.$class_name.'/auto.class.php');
					include_once(Main::$cwd.'storm/'.$class_name.'/extended.class.php');
				}
			}

		}

		private static function httpHeadersSpoofing() {
			header("Server: Microsoft-IIS/7.5");
			header("X-Powered-By: ASP.NET");
			header("X-AspNet-Version: 2.0.50727");
		}


		private static function processLogIn() {
			if (isset($_POST['ACTION']) && $_POST['ACTION'] == 'LOGIN') {
				Session::login($_POST['user'], $_POST['pass']);
			}
		}

		private static function processLogOut() {
			if (isset($_POST['ACTION']) && $_POST['ACTION'] == 'LOGOUT') {
				Session::logout();
			}
		}

		/** This method is the main controller */
		public static function serveWeb() {
			if (Config::get('PROFILING_ENABLED')) Profiling::start('index');

			$url = $_SERVER['REQUEST_URI'];

			$hash = md5($url);

			if (file_exists('cache/'.$hash)) {
				include('cache/'.$hash);
			} else {
				Router::setUrl($url);
				switch( Router::$node->getProperty('type')) {
					case 'page':
						ControllerPage::compile();
						break;
					case 'php':
						ControllerPhp::compile();
						break;
				}
			}

			if (Config::get('PROFILING_ENABLED')) Profiling::end();				
		}

		public static function go($error_level=0) {
			error_reporting($error_level);
			self::initializeCwd();
			self::httpHeadersSpoofing();

			self::autoload();
			self::sessionStart();

			self::magicQuotes();
			self::processLogIn();
			self::processLogOut();

			self::serveWeb();
		}

		public static function goCli($error_level=E_ALL) {
			error_reporting($error_level);
			self::initializeCwd();
			self::autoload();
			self::forceDatabaseConnection();
		}

		public static function goDebug() {
			self::go(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
		}

	}
