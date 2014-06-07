<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class EmailerHistory_auto {

		public static $fields = array (
  'HTML' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Timestamp' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Original' => 
  array (
    'type' => 'EmailerCurrent',
    'native' => false,
  ),
  'User' => 
  array (
    'type' => 'SystemUser',
    'native' => false,
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

			$sql = "SELECT * FROM `EmailerHistory`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = $db->sql($sql);
			while ($result && $row=mysql_fetch_assoc($result)) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new EmailerHistory($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$db = Database::getInstance();
			$sql = "INSERT INTO `EmailerHistory` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
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
				$sql = "DELETE FROM `EmailerHistory` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `EmailerHistory` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
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
			return 'EmailerHistory';
		}

		public function toString() {
			return "EmailerHistory[{$this->id}]";
		}

		// Setters and Getters
public function setHTML($value) { $this->row['HTML'] = $value; $value = mysql_real_escape_string($value); $timestamp = time(); $sql = "UPDATE `EmailerHistory` SET `HTML`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::getInstance()->sql($sql);} public function getHTML() { return $this->row['HTML']; }

public function setTimestamp($value) { $value = str_replace(',', '.', $value); $this->row['Timestamp'] = $value; $value = mysql_real_escape_string($value); $timestamp = time(); $sql = "UPDATE `EmailerHistory` SET `Timestamp`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::getInstance()->sql($sql); } public function getTimestamp() { $value = $this->row['Timestamp']; settype($value, 'float'); return $value; }


		public function setOriginal($value) {
			if (is_object($value) && $value->getClassName() == 'EmailerCurrent') {
				$id = $value->getId();
				$db = Database::getInstance();
				$sql = "UPDATE `EmailerHistory` SET `Original`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				$db->sql($sql);
				$this->row['Original'] = $id;
			} else if ($value === null) {
				$db = Database::getInstance();
				$sql = "UPDATE `EmailerHistory` SET `Original`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				$db->sql($sql);
				$this->row['Original'] = 0;
			}
		}

		public function getOriginal() {
			if ($this->row['Original'] == 0) {
				return null;
			} else {
				return EmailerCurrent::ROW($this->row['Original']);
			}
		}

		public function setUser($value) {
			if (is_object($value) && $value->getClassName() == 'SystemUser') {
				$id = $value->getId();
				$db = Database::getInstance();
				$sql = "UPDATE `EmailerHistory` SET `User`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				$db->sql($sql);
				$this->row['User'] = $id;
			} else if ($value === null) {
				$db = Database::getInstance();
				$sql = "UPDATE `EmailerHistory` SET `User`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				$db->sql($sql);
				$this->row['User'] = 0;
			}
		}

		public function getUser() {
			if ($this->row['User'] == 0) {
				return null;
			} else {
				return SystemUser::ROW($this->row['User']);
			}
		}

	}
