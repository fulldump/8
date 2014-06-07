<?php

	/**
	 * Class: SystemSession
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemSession extends SystemSession_auto {

		public static function INSERT() {
			$session_id = md5(microtime());
			$db = Database::getInstance();
			$sql = "INSERT INTO `SystemSession` (`id`, `__timestamp__`, `__operation__`, `SessionId`) VALUES (NULL, ".time().", 'INSERT', '$session_id')";
			$result = $db->sql($sql);
			$id = mysql_insert_id();
			return self::ROW($id);
		}

		public static function add(&$data) {
			$db = Database::getInstance();
			
			// Fields
			$SessionId = md5(microtime());
			$Ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
			$UserAgent = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
			$Created = time();
			$Data = mysql_real_escape_string(serialize($data));
			$sql = "INSERT INTO `SystemSession` (`id`, `__timestamp__`, `__operation__`, `SessionId`, `Ip`, `UserAgent`, `Created`, `Data`) VALUES (NULL, ".time().", 'INSERT', '$SessionId', '$Ip', '$UserAgent', '$Created', '$Data')";

			// Run query
			$result = $db->sql($sql);
			$id = mysql_insert_id();
			return self::ROW($id);
		}

	}
