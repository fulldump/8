<?php

	class Profiling {
		
		private static $_instance = null;
		private static $_stack = array();
		private static $_log = array();
		private $_persistence = null;
		private $_microtime = 0;
		private $_cwd = '';
		
		private function __construct() {
			$this->_microtime = microtime();
			$this->_cwd = getcwd();
			// $this->_persistence = ProfilingHistory::INSERT();
			self::$_stack[] = self::_create_item('root');
			register_shutdown_function(array(&$this, "_save"));
		}

		private static function _init() {
			if (null === self::$_instance) {
				self::$_instance = new Profiling();
			}
		}

		private static function _create_item($name) {
			return array(
				'name'=>$name,
				'microtime_start'=>microtime(),
				'queries_start'=>Database::getN(),
				'children'=>array()
			);
		}

		public static function start($name) {
			self::_init();
			$item = self::_create_item($name);

			self::$_stack[] = $item;
		}

		public static function log($message) {
			self::$_log[] = $message;
		}

		private static function _microtime_diff($start, $end) {
			$start = explode(' ', $start);
			$end = explode(' ', $end);
			return ($end[0] + $end[1])-($start[0] + $start[1]);
		}

		private static function _calculate_item($item) {
			$microtime_end = microtime();
			return array(
				'name'=>$item['name'],
				'time'=>self::_microtime_diff($item['microtime_start'], $microtime_end),
				'microtime_start'=>$item['microtime_start'],
				'microtime_end'=>$microtime_end,
				'queries'=>Database::getN() - $item['queries_start'],
				'children'=>$item['children'],
			);
		}
		
		public static function end() {
			self::_init();

			$child = array_pop(self::$_stack);
			$parent = array_pop(self::$_stack);
			$parent['children'][] = self::_calculate_item($child);
			self::$_stack[] = $parent;
		}

		public function _save() {
			$root = self::_calculate_item(array_pop(self::$_stack));

			$result = array(
				'url' => $_SERVER['REQUEST_URI'],
				'microtime' => $this->_microtime,
				'data' => $root,
				'log' => self::$_log,
				'included_files' => get_included_files(),
				'declared_classes' => get_declared_classes(),
				'memory' => array(
					'usage' => memory_get_usage(),
					'peak' => memory_get_peak_usage(),
				),
				'rusage' => getrusage(),
				'queries' => Database::$log,
				'post' => $_POST,
				'get' => $_GET,
			);

			$json = json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

			$microtime = explode(' ', $this->_microtime);
			$seconds = $microtime[1];
			$millis = $microtime[0];

			$filename = $seconds.'['.$millis.'].json';

			file_put_contents($this->_cwd.'/profiling/'.$filename, $json);
		}

	}
