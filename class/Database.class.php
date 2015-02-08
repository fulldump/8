<?php

	/**
	* Document this
	*
	* author: gerardooscarjt@gmail.com
	*/
	
	class Database {

		static $log = array();
		static $link = null;
		static $n = 0;
		static $config = null;
		
		/**
		 * Disable instantiation methods
		*/
		private function __construct() {}
		private function __clone() {}
		public function __destruct() {
			if (is_resource($this->link) )
				mysqli_close($this->link);
		}

		private static function connect() {
			if (null !== self::$link) {
				return;
			}

			self::load_config();
			self::$link = new mysqli(
				self::$config['HOST'],
				self::$config['USER'],
				self::$config['PASSWORD'],
				self::$config['DATABASE']
			);

			if (self::$link->connect_errno) {
				self::$link = null;
				return;
			}

			self::$link->set_charset('utf8');
		}

		public static function escape($param) {
			self::connect();
			return self::$link->real_escape_string($param);
		}

		public static function getInsertId() {
			self::connect();
			return self::$link->insert_id;
		}
		
		public static function sql($sql) {
			self::connect();

			if (!is_array($sql)) {
				$sql = array($sql);
			}

			foreach ($sql as $s) {
				$result = self::one_sql($s);
			}

			return $result;
		}

		private static function one_sql($sql) {
			if (Config::get('DATABASE_LOG_ENABLED')) {
				self::$log[] = $sql;
			}

			$result = self::$link->query($sql);
			
			if (self::$link->errno) {
				Profiling::log(self::$link->error);
			}

			self::$n++;

			return $result;
		}
		
		/**
		 * Documentar esto
		*/
		public static function getN() {
			return self::$n;
		}
		
		/**
		 * Documentar esto
		*/
		public static function configure($HOST, $DATABASE, $USER, $PASSWORD) {
			$link = new mysqli($HOST, $USER, $PASSWORD, $DATABASE);

			if ($link->connect_errno) {
				return;
			}

			self::load_config();
			self::$config['HOST'] = $HOST;
			self::$config['DATABASE'] = $DATABASE;
			self::$config['USER'] = $USER;
			self::$config['PASSWORD'] = $PASSWORD;
			self::store_config();
		}
		
		/**
		 * Implementa la interfaz FileStorable
		*/
		public static function load_config() {
			if (self::$config == null) {
				self::$config = FileStore::read(__CLASS__);
				if (self::$config===null) {
					self::$config = array(
						'HOST' => 'unconfigured host',
						'USER' => 'unconfigured user',
						'PASSWORD' => 'unconfigured password',
						'DATABASE' => 'unconfigured database',
					);
				}
			}
		}
		
		/**
		 * Implementa la interfaz FileStorable
		*/
		public static function store_config() {
			FileStore::write(__CLASS__, self::$config);
		}
	}
