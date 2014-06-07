<?php

	/**
	 * Class: SystemCache
	 * Created on: Sat, 08 Mar 2014 03:15:13 +0100
	*/

	class SystemCache extends SystemCache_auto {

		private static $CACHE_DIR = 'cache/';
		
		public static function INSERT($hash, $data, $flags=null) {
			$hash = md5($hash);
			if (!is_null($flags))
				$flags = '|'.implode('|',$flags).'|';
				
			$list = parent::SELECT("Hash='".mysql_real_escape_string($hash)."'");
			if (count($list)) {
				$row = $list[0];
				if (file_exists(self::$CACHE_DIR.$hash))
					@unlink(self::$CACHE_DIR.$hash);
			} else {
				$row = parent::INSERT($hash, $data, $flags=null);
			}
			$row->setTimestamp(time());
			$row->setHash($hash);
			$row->setFlags($flags);
			file_put_contents(self::$CACHE_DIR.$hash, $data);
		}
		
		public static function GET($hash) {
			$hash = md5($hash);
			if (file_exists(self::$CACHE_DIR.$hash)) {
				include(self::$CACHE_DIR.$hash);
				return true;
			}
			return false;
		}

	}
