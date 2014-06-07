<?php

	/**
	 * Clase: Session
	 * Ubicación: /class/Session.class.php
	 * Descripción: Gestiona lo relacionado con las sesiones
	 *
	 * autor: gerardooscarjt@gmail.com
	 * fecha: 2011/10/20
	*/
	
	class Session {
		
		private static $instance = null;
		private static $data = null;
		private static $_system_session = null;
		private static $_data_hash = '';
		
		private function __construct() {
			if (isset($_COOKIE[Config::get('COOKIE_NAME')]) && Lib::is_md5($_COOKIE[Config::get('COOKIE_NAME')])) {
			    self::openSession();
			} else {
			    self::createSession();
			}
		}		
		
		public function __destruct() {
			$serialized_data = serialize($_SESSION);
			if (md5($serialized_data)!=self::$_data_hash) {
				self::$_system_session->setData($serialized_data);
			}
		}
		
		public function __clone() {
			
		}
		
		public static function flush() {
			$serialized_data = serialize($_SESSION);
			self::$_system_session->setData($serialized_data);
		}

		public static function destroy() {
			self::$_system_session->DELETE();
			setcookie(Config::get('COOKIE_NAME'), '', time()+31536000, '/');
		}

		private static function openSession() {
			$session_id = $_COOKIE[Config::get('COOKIE_NAME')];
			if (Lib::is_md5($session_id)) {
				Database::getInstance();
				$sessions = SystemSession::SELECT("SessionId = '".$session_id."'");
				if (count($sessions)==1) {
					// Abro la sesión
					self::$_system_session = $sessions[0];
					$data = self::$_system_session->getData();
					self::$_data_hash = md5($data);
					$_SESSION = unserialize($data);
				} else {
					// Creo una sesión nueva
					self::createSession();
				}
			} else {
				// Nos están intentando meter una cookie chunga los cabrones, creo una sesión nueva:
				self::createSession();
			}
		}
		
		private static function createSession() {
			$_SESSION = array();
			self::$_system_session = SystemSession::add($_SESSION);
			self::$_data_hash = md5(serialize($_SESSION));
			setcookie(Config::get('COOKIE_NAME'), self::$_system_session->getSessionId(), time()+31536000, '/');
		}
		
		/**
		 * Implementa el patrón singleton
		*/
		private static function initialize() {
			if (self::$instance===null) 
				self::$instance = new Session();
		}
		
		public static function getUser() {
			self::initialize();
			if (self::$_system_session->getUser()!==null)
				return self::$_system_session->getUser();
			return SystemUser::ROW(Config::get('GUEST_USER_ID'));
		}
		
		public static function isGod() {
			return self::getUser()->getLogin() == 'admin';
		}
		
		/**
		 *
		 *
		*/
		public static function login($user, $pass) {
			self::initialize();
			if (!self::isLoggedIn()) {
				$user = SystemUser::validate($user, $pass);
				if ($user != null) {
					self::$_system_session->setUser($user);
					return true;
				}
			}
			return false;
		}
		
		public static function isLoggedIn() {
			self::initialize();
			return self::$_system_session->getUser() !== null;
		}
		
		public static function logout() {
			self::initialize();
			if (self::isLoggedIn()) {
				self::$_system_session->setData('');
				unset($_COOKIE[Config::get('COOKIE_NAME')]);
				self::createSession();
				return true;
			} else {
				// No hay nadie logado, para hacer logout deberías logarte antes.
				return false;
			}			
		}
		
		public static function getAll() {
			self::initialize();
			if (self::getUser() == null) {
				return array();
			} else {
				return SystemSession::SELECT("User=".self::getUser()->getId()." AND NOT SessionId='' ");
			}
		}
		
		public static function closeAll() {
			self::initialize();
			if (self::getUser() == null) {
				return array();
			} else {
				$sessions = SystemSession::SELECT("User=".self::getUser()->getId()." AND NOT SessionId='' ");
				foreach ($sessions as $s)
					if ($s->getSessionId() != self::getSessionId())
						$s->setSessionId('');
			}
		}
		
		public static function getSessionId() {
			self::initialize();
			return self::$_system_session->getSessionId();
		}
		
		public static function getCreated() {
			self::initialize();
			return self::$_system_session->getCreated();
		}
	}
