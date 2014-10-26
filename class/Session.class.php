<?php

/**
 * Class:		Session
 * Location:	/class/Session.class.php
 * Description:	Manage all related to sessions system
 *
 * author:	gerardooscarjt@gmail.com
 * date:	2011/10/20
*/

class Session {

	private static $_instance = null;
	private static $_system_session = null;
	private static $_data_hash = '';

	private function __construct() {
		$_SESSION = array();
		self::$_data_hash = md5(serialize($_SESSION));

		if (isset($_COOKIE[Config::get('COOKIE_NAME')]) && self::_is_md5($_COOKIE[Config::get('COOKIE_NAME')])) {
		    self::_open_session();
		}
	}

	public function __destruct() {
		if (self::_has_changed()) {
			if (null === self::$_system_session) {
				self::_create_session();
			}
			self::$_system_session->setData(serialize($_SESSION));
		}
	}

	private function __clone() {}

	public static function start() {
		if (self::$_instance===null) 
			self::$_instance = new Session();
	}

	public static function destroy() {
		setcookie(Config::get('COOKIE_NAME'), '', 0, '/');

		if (null == self::$_system_session) {
			return;
		}
		self::$_system_session->DELETE();
		self::$_system_session = null;
	}

	public static function getUser() {
		if (null === self::$_system_session)
			return SystemUser::ROW(Config::get('GUEST_USER_ID'));

		return self::$_system_session->getUser();
	}

	public static function isGod() {
		return self::getUser()->getLogin() == 'admin';
	}
	
	public static function login($user, $pass) {
		if (!self::isLoggedIn()) {
			$user = SystemUser::validate($user, $pass);
			if ($user != null) {
				if (null === self::$_system_session) {
					self::_create_session();
				}
				self::$_system_session->setUser($user);
				return true;
			}
		}
		return false;
	}
	
	public static function logout() {
		if (self::isLoggedIn()) {
			setcookie(Config::get('COOKIE_NAME'), '', 0, '/');
			self::$_system_session->setUser(null);
			self::$_system_session->setData('');
			self::$_system_session = null;
			return true;
		} else {
			// No hay nadie logado, para hacer logout deberías logarte antes.
			return false;
		}			
	}

	public static function isLoggedIn() {
		if (null === self::$_system_session) {
			return false;
		}

		return self::$_system_session->getUser() !== null;
	}
	
	public static function getSessionId() {
		if (null === self::$_system_session) {
			return null;
		}
		return self::$_system_session->getSessionId();
	}

	public static function getCreated() {
		if (null === self::$_system_session) {
			return time();
		}

		return self::$_system_session->getCreated();
	}

	public static function getAll() {
		if (self::getUser() == null) {
			return array();
		} else {
			return SystemSession::SELECT("User=".self::getUser()->getId()." AND NOT SessionId='' ");
		}
	}

	public static function closeAll() {
		if (self::getUser() === null) {
			return array();
		} else {
			$sessions = SystemSession::SELECT("User=".self::getUser()->getId()." AND NOT SessionId='' ");
			foreach ($sessions as $s)
				if ($s->getSessionId() != self::getSessionId())
					$s->DELETE();
		}
	}

	private static function _open_session() {
		$session_id = $_COOKIE[Config::get('COOKIE_NAME')];
		if (self::_is_md5($session_id)) {
			$sessions = SystemSession::SELECT("SessionId = '".$session_id."'");
			if (count($sessions)==1) {
				// Abro la sesión
				self::$_system_session = $sessions[0];
				$data = self::$_system_session->getData();
				self::$_data_hash = md5($data);
				$_SESSION = unserialize($data);
			} else {
				// Creo una sesión nueva
				self::_create_session();
			}
		} else {
			// Nos están intentando meter una cookie chunga, creo una sesión nueva:
			self::_create_session();
		}
	}

	private static function _create_session() {
		self::$_system_session = SystemSession::add($_SESSION);
		self::$_data_hash = md5(serialize($_SESSION));
		setcookie(Config::get('COOKIE_NAME'), self::$_system_session->getSessionId(), time()+31536000, '/');
	}

	private static function _has_changed() {
		return md5(serialize($_SESSION)) !== self::$_data_hash;
	}

	private static function _is_md5($md5) {
		return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
	}

}
