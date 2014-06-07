<?php
	
	/**
	 * Class: Storm
	 * Location: class/Storm.class.php
	 * Date: Sun, 4 Mar 2014 22:35:03 +0200
	 * Author: gerardooscarjt@gmail.com
	 * Typical use:
	 *     $entity = Storm::get('Products'); // If it does not exists is created
	 *     $entity->add('Name', 'Text'); // Add a field (type text)
	 *     $entity->add('Category', 'ProductCategory'); // If needed, 'ProductCategory' is created
	*/
		
	class Storm {

		protected static $dir_base = 'storm/';
		protected static $data = array();
		protected static $protected_fields = array('id', '__timestamp__', '__operation__');
		private static $collate = 'utf8_unicode_ci';

		protected $name = '';
		protected $model = null;
		protected $alter = null;
		
		/* OK */
		public function __construct($name) {
			$this->name = $name;
			$this->model = json_decode(file_get_contents(self::$dir_base.$name.'/model.json'), true);

			$filename_alter = self::$dir_base.$name.'/alter.json';
			if (file_exists($filename_alter)) {
				$this->alter = json_decode(file_get_contents($filename_alter), true);
			}
		}

		/* OK */
		public function export() {
			$this->model['data'] = array();
			$table = $this->name;
			foreach ($table::SELECT("1 ORDER BY Id") as $r) {
				$this->model['data'][] = $r->row;
			}
			self::store_model($this->name, $this->model);
		}


		/* OK */
		public function install() {

			if ($this->model['native']) {
				return false;
			}

			$is_installed = null !== $this->alter;

			// Check if installed:
			if (!$is_installed) {
				// Create database table:
				self::dbDropTable($this->name);
				self::dbCreateTable($this->name);

				$this->alter = array(
					'fields' => array(),
					'index' => array(),
				);
			}

			// Create new columns in database
			foreach($this->model['fields'] as $field=>$type) {
				if (!array_key_exists($field, $this->alter['fields'])) {
					$this->alter['fields'][$field] = $type;

					$the_type = self::get($type);

					if ($the_type->model['native']) {
						self::dbAddField($this->name, $field, $the_type->model['type']);
					} else {
						self::dbAddField($this->name, $field, 'INT NOT NULL');
					}
				}
			}

			// Remove old columns in database
			foreach($this->alter['fields'] as $field=>$type) {
				if (!array_key_exists($field, $this->model['fields'])) {
					unset($this->alter['fields'][$field]);

					echo "Borro la columna '$field'";

					self::dbRemoveField($this->name, $field);
				}
			}

			self::store_alter($this->name, $this->alter);
			self::store_model($this->name, $this->model);

			// Load the data
			if (!$is_installed) {
				if (array_key_exists('data', $this->model)) {
					foreach($this->model['data'] as $data) {
						$fields = array();
						$values = array();
						foreach($data as $f=>$v) {
							$fields[] = '`'.mysql_real_escape_string($f).'`';
							$values[] = "'".mysql_real_escape_string($v)."'";
						}
						$fields = implode(',', $fields);
						$values = implode(',', $values);

						$sql = "INSERT INTO `{$this->name}` ($fields) VALUES ($values)";
						Database::getInstance()->sql($sql);
					}
				}
			}

			$this->regenerate();

			return false;
		}

		/* OK */
		public static function all() {
			$select = array();

			$path = self::$dir_base;
			$d = dir($path);
			while (false !== ($entry = $d->read())) {
				$path_entry = $path.$entry.'/';
				if ('.' !== $entry && '..' !== $entry && is_dir($path_entry)) {
					self::get($entry);
					$select[] = &self::$data[$entry];
				}
			}
			$d->close();

			return $select;
		}

		/* OK */
		public function drop() {
			if ($this->model['native'])
				return false;

			$dependences = array();
			foreach (self::all() as $storm) {
				if ($storm->name == $this->name) {
					continue;
				}

				$model = $storm->getModel();
				foreach ($model['fields'] as $field=>$type) {
					if ($type == $this->name) {
						$dependences[$storm->name][] = $field;
					}
				}
			}

			if (count ($dependences))
				return $dependences;

			$path = self::$dir_base.$this->name.'/';
			unlink($path.'model.json');
			unlink($path.'alter.json');
			unlink($path.'auto.class.php');
			unlink($path.'extended.class.php');
			rmdir($path);

			self::dbDropTable($this->name);

			unset(self::$data[$this->name]);

			return true;
		}

		/* OK */
		public function getName() {
			return $this->name;
		}

		/* OK */
		public static function get($name) {
			// If it does not exists -> create
			if (!self::exists($name)) {
				self::create($name);
				$storm = new Storm($name);
				$storm->regenerate();
			}

			// If it is not loaded -> load
			if (!array_key_exists($name, self::$data)) {
				self::$data[$name] = new Storm($name);
			}

			return self::$data[$name];
		}

		/* TEST STAGE */
		public function remove($field) {

			// Check field exists
			if (!array_key_exists($field, $this->model['fields'])) {
				echo "The field '$field' does not exist";
				return null;
			}

			// Unset the field
			unset($this->model['fields'][$field]);
			self::store_model($this->name, $this->model);
			if (null != $this->alter) {
				unset($this->alter['fields'][$field]);
				self::store_alter($this->name, $this->alter);
			}

			// Remove column in database
			self::dbRemoveField($this->name, $field);

			$this->regenerate();

			return $this;
		}

		/* OK */
		public function add($field, $type) {

			// Clean
			$field = trim($field);

			// Validation
			if ($field == '') {
				echo 'nombre de campo vacío';
				return null;
			}

			// Check restricted field name
			if (in_array(strtolower($field), self::$protected_fields)) {
				echo 'nombre de campo restringido';
				return null;
			}

			// Check this entity is native
			if ($this->model['native']) {
				echo 'tipo nativo, no se pueden añadir campos';
				return null;
			}

			// Check field already exists
			if (array_key_exists($field, $this->model['fields'])) {
				echo 'el campo ya existe';
				return null;
			}

			// Get the type
			$the_type = self::get($type);

			// Add the field
			$this->model['fields'][$field] = $type;
			self::store_model($this->name, $this->model);
			if (null != $this->alter) {
				$this->alter['fields'][$field] = $type;
				self::store_alter($this->name, $this->alter);
			}

			// Create column in database
			if ($the_type->model['native']) {
				self::dbAddField($this->name, $field, $the_type->model['type']);
			} else {
				self::dbAddField($this->name, $field, 'INT NOT NULL');
			}

			$this->regenerate();

			return $this;
		}

		/* OK */
		public function getModel() {
			return $this->model;
		}

		public function isNative() {
			return $this->model['native'];
		}

		// PRIVATE METHODS ////////////////////////////////////////////////////

		/* OK */
		private static function ensure_dir($name) {
			$path = self::$dir_base.$name.'/';
			if (!file_exists($path)) {
				mkdir($path);
				chmod($path, 0777);
			}
		}

		/* OK */
		private static function store_model($name, &$model) {
			self::ensure_dir($name);

			$filename = self::$dir_base.$name.'/model.json';

			file_put_contents(
				$filename,
				json_encode($model, JSON_PRETTY_PRINT),
				LOCK_EX
			);

			chmod($filename, 0777);
		}

		/* OK */
		private static function store_alter($name, &$model) {
			self::ensure_dir($name);

			$filename = self::$dir_base.$name.'/alter.json';
			file_put_contents(
				$filename,
				json_encode($model, JSON_PRETTY_PRINT),
				LOCK_EX
			);
			chmod($filename, 0777);
		}

		/* OK */
		/**
		 * VULNERABILITY: User could create files outside the folder 'storm/'
		*/

		public static function exists($name) {
			$path = self::$dir_base.$name.'/';
			return file_exists($path);
		}

		/**
		 * VULNERABILITY: User could create files outside the folder 'storm/'
		*/
		/* OK */
		private static function create($name) {
			if (!self::exists($name)) {
				// It can be created
				$model = array(
					'name' => $name,
					'native' => false,
					'fields' => array(),
					'data' => array(),
				);
				self::store_model($name, $model);
				self::store_alter($name, $model);
				self::dbDropTable($name);
				self::dbCreateTable($name);
				return true;
			}
			return false;
		}

		/* OK */
		public function regenerate() {
			// Native entities can't be regenerated
			if ($this->model['native']) {
				return false;
			}

			$filename_extended = self::$dir_base.$this->name.'/extended.class.php';
			$filename_auto = self::$dir_base.$this->name.'/auto.class.php';

			if (!file_exists($filename_extended)) {
				$code = '<?php

	/**
	 * Class: '.$this->name.'
	 * Created on: '.date('r').'
	*/

	class '.$this->name.' extends '.$this->name.'_auto {

	}
';
				file_put_contents($filename_extended, $code);
				chmod($filename_extended, 0777);
			}


			$fields = array();
			foreach ($this->model['fields'] as $name=>$type) {
				$fields[$name] = array(
					'type'=>$type,
					'native'=>Storm::get($type)->isNative(),
				);
			}

			$code = '<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class '.$this->name.'_auto {

		public static $fields = '.var_export($fields, true).';

		protected static $data = array();

		protected $id;
		protected $timestamp;
		protected $operation;
		public $row;

		public function __construct(&$row) {
			$this->id = $row[\'id\'];
			$this->timestamp = $row[\'__timestamp__\'];
			$this->operation = $row[\'__operation__\'];
			$this->row = $row;
		}

		public function RAW($field) {
			if (array_key_exists($field, $this->row)) {
				return $this->row[$field];
			}
			return null;
		}

		public static function PREFETCH($field, $collection) {
			$IDs = array();
			foreach ($collection as $item) {
				$IDs[] = $item->RAW($field);
			}
			$IDs = array_unique($IDs);
			if (count($IDs)) {
				$implode = implode(\',\', $IDs);
				return self::SELECT(" Id IN ($implode) ");
			}
			return array();
		}

		public static function SELECT($where=null) {
			$db = Database::getInstance();

			$sql = "SELECT * FROM `'.mysql_real_escape_string($this->name).'`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = $db->sql($sql);
			while ($result && $row=mysql_fetch_assoc($result)) {
				$id = $row[\'id\'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new '.$this->name.'($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$db = Database::getInstance();
			$sql = "INSERT INTO `'.$this->name.'` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", \'INSERT\')";
			$result = $db->sql($sql);
			$id = mysql_insert_id();
			return self::ROW($id);
		}

		public static function ROW($id) {
			$id = intval($id);
			if (array_key_exists($id, self::$data)) {
				return self::$data[$id];
			} else {
				$db = Database::getInstance();
				$rows = self::SELECT("id=\'".mysql_real_escape_string($id)."\'");
				if (count($rows)) {
					return $rows[0];
				} else {
					return null;
				}
			}
		}

		public function DELETE($physical=true) {
			$db = Database::getInstance();
			if ($physical) {
				$sql = "DELETE FROM `'.$this->name.'` WHERE id=\'".$this->id."\'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `'.$this->name.'` SET `__timestamp__` = ".time().", `__operation__` = \'DELETE\' WHERE `id`=\'".$this->id."\'";
			}
			$db->sql($sql);
		}

		/* Deprecated */
		public final function getId() {
			return intval($this->id);
		}

		public final function ID() {
			return intval($this->id);
		}

		public final function TIMESTAMP() {
			return intval($this->timestamp);
		}

		public final function OPERATION() {
			return $this->operation;
		}

		public final function getClassName() {
			return \''.$this->name.'\';
		}

		public function toString() {
			return "'.$this->name.'[{$this->id}]";
		}

		// Setters and Getters
';

			foreach ($this->model['fields'] as $field_name=>$field_type) {
				$type = Storm::get($field_type);

				if ($type->model['native']) {
					$code .= str_replace(
						array('__table_name__', '__field_name__'),
						array($this->getName(), $field_name),
						$type->model['methods']
					)."\n\n";
				} else {
					$code .= '
		public function set'.$field_name.'($value) {
			if (is_object($value) && $value->getClassName() == \''.$field_type.'\') {
				$id = $value->getId();
				$db = Database::getInstance();
				$sql = "UPDATE `'.$this->name.'` SET `'.$field_name.'`=\'".$id."\',	`__timestamp__` = ".time()." WHERE `id`=\'".$this->id."\'";
				$db->sql($sql);
				$this->row[\''.$field_name.'\'] = $id;
			} else if ($value === null) {
				$db = Database::getInstance();
				$sql = "UPDATE `'.$this->name.'` SET `'.$field_name.'`=\'0\', `__timestamp__` = ".time()." WHERE `id`=\'".$this->id."\'";
				$db->sql($sql);
				$this->row[\''.$field_name.'\'] = 0;
			}
		}

		public function get'.$field_name.'() {
			if ($this->row[\''.$field_name.'\'] == 0) {
				return null;
			} else {
				return '.$field_type.'::ROW($this->row[\''.$field_name.'\']);
			}
		}
';
				}
			}

			$code .= '
	}
';

			file_put_contents($filename_auto, $code);
			chmod($filename_auto, 0777);

			return true;
		}

		private static function dbCreateTable($name) {
			$name = mysql_real_escape_string($name);
			$collate = self::$collate;
			$sql = "
				CREATE TABLE `$name` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`__timestamp__` INT UNSIGNED NOT NULL,
				`__operation__` enum('INSERT','UPDATE','DELETE') default 'INSERT'
				) ENGINE = MYISAM CHARACTER SET utf8 COLLATE {$collate}";
			Database::getInstance()->sql($sql);
		}

		private static function dbDropTable($name) {
			$name = mysql_real_escape_string($name);
			$sql = "DROP TABLE IF EXISTS `$name`";
			Database::getInstance()->sql($sql);
		}

		private static function dbAddField($name, $field, $type) {
			$name = mysql_real_escape_string($name);
			$field = mysql_real_escape_string($field);
			$sql = "ALTER TABLE `$name` ADD `$field` $type";
			Database::getInstance()->sql($sql);
		}

		private static function dbRemoveField($name, $field) {
			$name = mysql_real_escape_string($name);
			$field = mysql_real_escape_string($field);
			$sql = "ALTER TABLE `$name` DROP `$field`";
			Database::getInstance()->sql($sql);
		}


	}
