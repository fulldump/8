<?php

	/**
	 * Class: SystemSession
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemSession extends SystemSession_auto {

		public static function INSERT() {
			$session_id = md5(microtime());
			$sql = "INSERT INTO `SystemSession` (`id`, `__timestamp__`, `__operation__`, `SessionId`) VALUES (NULL, ".time().", 'INSERT', '$session_id')";
			$result = Database::sql($sql);
			$id = Database::getInsertId();
			return self::ROW($id);
		}

		public static function add(&$data) {
			// Fields
			$SessionId = md5(microtime());
			$Ip = Database::escape($_SERVER['REMOTE_ADDR']);
			$UserAgent = Database::escape($_SERVER['HTTP_USER_AGENT']);
			$Created = time();
			$Data = Database::escape(serialize($data));
			$sql = "INSERT INTO `SystemSession` (`id`, `__timestamp__`, `__operation__`, `SessionId`, `Ip`, `UserAgent`, `Created`, `Data`) VALUES (NULL, ".time().", 'INSERT', '$SessionId', '$Ip', '$UserAgent', '$Created', '$Data')";

			// Run query
			$result = Database::sql($sql);
			$id = Database::getInsertId();
			return self::ROW($id);
		}

	}
