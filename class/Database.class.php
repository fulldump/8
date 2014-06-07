<?php

	/**
	* Documentar esto
	*
	* autor: gerardooscarjt@gmail.com
	*/
	
	require_once('class/FileStore.class.php');
	
	class Database {
		static $instance = null;
		static $log = array();
		private $link = null;
		private static $n = 0;
		private static $data = null;
		
		/**
		 * Documentar esto
		*/
		private function __construct() {
			self::loadData();
			self::connect();
		}

		private function connect() {
			$this->link = mysql_connect(self::$data['HOST'], self::$data['USER'], self::$data['PASSWORD']);
			mysql_select_db(self::$data['DATABASE'], $this->link);
			mysql_query("SET NAMES 'utf8'", $this->link);			
		}
		
		/**
		 * Documentar esto
		*/
		private function __clone() {
			
		}
		
		/**
		 * Documentar esto
		*/
		public function __destruct() {
            if (is_resource($this->link) )
                mysql_close($this->link);
        }
		
		/**
		 * Documentar esto
		*/
		public static function getInstance() {
			if (self::$instance==null)
				self::$instance = new Database();
			return self::$instance;
		}
		
		/**
		 * Documentar esto
		*/
		public function sql($sql) {
			if (Config::get('DATABASE_LOG_ENABLED')) {
				self::$log[] = $sql;
			}
            if (is_array($sql)) {
                foreach ($sql as $s) {
                    $res = mysql_query($s, $this->link);
                    //if (mysql_errno($this->link)) Logger::error(mysql_error($this->link));
					if (mysql_errno($this->link)) echo mysql_error($this->link);
                    self::$n++;
                }
            } else {
                $res = mysql_query($sql, $this->link);
                //if (mysql_errno($this->link)) Logger::error(mysql_error($this->link));
				if (mysql_errno($this->link)) echo mysql_error($this->link);
                self::$n++;
            }
            return $res;
		}
		
		/**
		 * Documentar esto
		*/
		public function getN() {
			return self::$n;
		}
		
		/**
		 * Documentar esto
		*/
		public static function configure($HOST, $DATABASE, $USER, $PASSWORD) {
			// FALTA: comprobar antes si la conexión con estos datos es correcta.
			self::$data['HOST'] = $HOST;
			self::$data['DATABASE'] = $DATABASE;
			self::$data['USER'] = $USER;
			self::$data['PASSWORD'] = $PASSWORD;
			FileStore::write(__CLASS__, self::$data);
			self::getInstance()->connect();
		}
		
		/**
		 * Documentar esto
		*/
		public function getHost() {
			return self::$data['HOST'];
		}
		
		/**
		 * Documentar esto
		*/
		public function getDatabase() {
			return self::$data['DATABASE'];
		}
		
		/**
		 * Documentar esto
		*/
		public function getUser() {
			return self::$data['USER'];
		}
		
		/**
		 * Documentar esto
		*/
		public function getPassword() {
			return self::$data['PASSWORD'];
		}
		
		/**
		 * Implementa la interfaz FileStorable
		*/
		public static function loadData() {
			if (self::$data == null) {
				self::$data = FileStore::read(__CLASS__);
				if (self::$data===null)
					self::$data = array();
			}
		}
		
		/**
		 * Implementa la interfaz FileStorable
		*/
		public static function saveData() {
			FileStore::write(__CLASS__, self::$data);
		}
	}
