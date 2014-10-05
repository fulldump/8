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
		$this->requested_url = $url;

		// Explode
		$this->parts = explode('/', $url);
		if ($this->parts[0] == '') {
			array_shift($this->parts);
		}

		// Extract language
		$this->_extract_language();

		// If auto url decode
		// if (true) {
		// 	foreach ($this->_parts as $i=>$part) {
		// 		$this->_parts[$i] = rawurldecode($part);
		// 	}
		// }

		// Find node
		$this->node = static::$root;
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