<?php



class Multilingual {

	public static $available_languages = null;
	public static $default_language = null;

	public static function get($raw) {
		self::config();
		$items = @unserialize($raw);
		if (false === $items) {
			return null;
		}

		$current = current($items);

		if (array_key_exists(self::$default_language, $items)) {
			return $items[self::$default_language];
		}

		return $current;
	}

	public static function set($raw, $value) {
		self::config();
		$items = @unserialize($raw);
		if (false === $items) {
			$items = array();
		}

		$items[self::$default_language] = $value;

		return serialize($items);
	}

	public static function config() {
		if (null === self::$available_languages || null === self::$default_language) {
			self::$default_language = Router::$language;
			self::$available_languages = array_map(trim ,explode(',', Config::get('AVAILABLE_LANGUAGES')));
		}
	}

}