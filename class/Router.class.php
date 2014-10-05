<?php

class Router {

	public static $filename = 'router.json';
	public static $root = null;

	public $requested_url = '';
	public $language;
	public $parts;
	public $parameters = array();
	public $node;

	public function __construct($url=null) {
		self::load();

		$this->requested_url = $url;

		$this->_preprocess_url();

		$this->_extract_language();

		// Select starting node:
		////////////////////////
		$start = static::$root->getById(Config::get('DEFAULT_PAGE'));

		// Only to fix the extreme case with corrupt configuration
		if (null === $start) {
			$start = static::$root;
		}

		// If home page does not match
		if (count($this->parts) && !$this->_node_match($start, $this->parts[0])) {
			$start = static::$root;
		}

		// Search from starting node:
		/////////////////////////////
		$this->node = $start;
		foreach ($this->parts as $part) {

			$found = false;
			foreach ($this->node->children as $key=>$node) {
				if ($key == $part) {
					// do nothing
				} else if (self::isParameter($key)) {
					$this->parameters[$key] = $part;
				} else {
					continue;
				}
				$found = true;
				$this->node = $node;
				array_shift($this->parts);
				break;
			}
			
			if (!$found) {
				break;
			}

		}
	}

	private static function isParameter($part) {
		return '{' == mb_substr($part, 0, 1) && mb_substr($part, -1, 1) == '}';
	}

	private function _extract_language() {
		$default_language = Config::get('DEFAULT_LANGUAGE');
		$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));
		$tentative_language = $this->parts[0];
		if ($tentative_language != $default_language && in_array($tentative_language, $available_languages)) {
			array_shift($this->parts);
			$this->language = $tentative_language;
		} else {
			$this->language = $default_language;
		}
	}

	private function _preprocess_url() {
		// Parse url
		$parse = parse_url('http://dummy:80'.$this->requested_url);
		$path = $parse['path'];
		$query = $parse['query'];

		// Split by '/'
		$this->parts = explode('/', $path);


		// Remove first if empty
		if (count($this->parts) && '' === $this->parts[0]) {
			array_shift($this->parts);
		}

		// Remove last if empty
		if (count($this->parts) && '' === end($this->parts)) {
			array_pop($this->parts);
		}

		// Decode url parts
		foreach ($this->parts as $i=>$part) {
			$this->parts[$i] = rawurldecode($part);
		}
	}

	private function _node_match($node, $key) {
		if (null === $node) {
			return false;
		}

		foreach ($node->children as $k=>$child) {
			if ($k == $key || self::isParameter($k)) {
				return true;
			}
		}

		return false;
	}

	public static function load() {
		if (null !== self::$root) {
			return;
		}

		self::$root = new Node();

		$data = json_decode(@file_get_contents(self::$filename), true);
		if (null !== $data) {
			self::$root->fromArray($data);
		}
	}

	public static function save() {
		file_put_contents(
			self::$filename,
			json_encode(self::$root->toArray(), JSON_PRETTY_PRINT));
	}

}