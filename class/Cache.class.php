<?php

class Cache {

	private static $CACHE_DIR = 'cache/';

	public static function add($hash, $data) {
		$hash = md5($hash);
		return file_put_contents(self::$CACHE_DIR.$hash, $data);
	}

}
