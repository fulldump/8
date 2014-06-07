<?php

	class Test {

		private static $_current_dir = '';
		private static $_current_file = '';
		private static $_results = array();
		private static $_functions = array();

		public static function clear() {
			self::$_current_dir = '';
			self::$_current_file = '';
			self::$_results = array();
		}

		public static function addFunctions($functions) {
			self::$_functions = $functions;
		}

		public static function find($dir) {
			if (file_exists($dir)) {
				$children_dir = array();
				$children_file = array();

				$d = dir($dir);
				while(false !== ($entry = $d->read())) {
					$path = "$dir/$entry";
					if (is_dir($path)) {
						if ('.' != $entry[0]) {
							$subtree = self::find($path);
							if (null !== $subtree) {
								$subtree['name'] = $entry;
								$children_dir[$entry] = $subtree;
							}
						}
					} else {
						$extension = '.test.php';
						$extension_len = strlen($extension);
						if (substr($entry, -$extension_len) == $extension) {
							$result = self::findTest($path);
							if (count($result)) {
								$children_file[$entry] = array(
									'name' => $entry,
									'type' => 'file',
									'children' => $result,
								);
							}
						}
					}
				}
				$d->close();

				ksort($children_dir);
				ksort($children_file);

				$children = array_merge($children_dir, $children_file);

				if (0 != count($children) || 0 != count($tests)) {
					return array(
						'name' => $dir,
						'type' => 'dir',
						'children' => $children,
					);
				}
			}
			return null;
		}

		public static function findTest($file) {
			self::$_functions = array();
			if (file_exists($file)) {
				include($file);
			}
			$names = array_keys(self::$_functions);

			$tests = array();
			foreach ($names as $name) {
				$tests[$name] = array(
					'name' => $name,
					'type' => 'test',
					'children' => array(),
				);
			}
			// ksort($tests);

			return $tests;
		}

		public static function run($file, $test) {
			if (file_exists($file)) {
				include($file);
				if (array_key_exists($test, self::$_functions)) {
					$function = self::$_functions[$test];
					return $function() == true;
				}
			}

			return null;
		}

	}