<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class SimpleText_auto {

		public static $fields = array (
  'Name' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Text' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
);

		protected static $data = array();

		protected $id;
		protected $timestamp;
		protected $operation;
		public $row;

		public function __construct(&$row) {
			$this->id = $row['id'];
			$this->timestamp = $row['__timestamp__'];
			$this->operation = $row['__operation__'];
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
				$implode = implode(',', $IDs);
				return self::SELECT(" Id IN ($implode) ");
			}
			return array();
		}

		public static function SELECT($where=null) {
			$db = Database::getInstance();

			$sql = "SELECT * FROM `SimpleText`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = $db->sql($sql);
			while ($result && $row=mysql_fetch_assoc($result)) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new SimpleText($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$db = Database::getInstance();
			$sql = "INSERT INTO `SimpleText` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
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
				$rows = self::SELECT("id='".mysql_real_escape_string($id)."'");
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
				$sql = "DELETE FROM `SimpleText` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `SimpleText` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
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
			return 'SimpleText';
		}

		public function toString() {
			return "SimpleText[{$this->id}]";
		}

		// Setters and Getters
public function setName($value) { $this->row['Name'] = $value; $value = mysql_real_escape_string($value); $timestamp = time(); $sql = "UPDATE `SimpleText` SET `Name`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::getInstance()->sql($sql);} public function getName() { return $this->row['Name']; }

public function setText($value) { $this->row['Text'] = $value; $value = mysql_real_escape_string($value); $timestamp = time(); $sql = "UPDATE `SimpleText` SET `Text`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::getInstance()->sql($sql);} public function getText() { return $this->row['Text']; }


	}
