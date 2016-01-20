<?php

class Rack {

	const DEEPNESS = 6;
	const BASE_PATH = 'rack/';

	public static function Write($collection, $hash, $path) {
		// Prepare the directory
		self::Make($collection, $hash);

		// Do the copy
		$src = $path;
		$dst = self::Path($collection, $hash);
		copy($src, $dst);
	}

	public static function Path($collection, $hash) {
		return self::Dir($collection, $hash) . $hash;
	}

	public static function Dir($collection, $hash) {
		$path = '';
		for ($i=0; $i<Rack::DEEPNESS; $i++) {
			$path .= $hash[$i] . '/';
		}

		return Rack::BASE_PATH . $collection . '/' . $path;
	}

	public static function Make($collection, $hash) {
		$dir = self::Dir($collection, $hash);
		mkdir($dir, 0777, true);
	}

	public static function Remove($collection, $hash) {
		return unlink(self::Path($collection, $hash));
	}

}