<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class File_auto {

		public static $fields = array (
  'Hash' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Size' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Mime' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Counter' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Name' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'User' => 
  array (
    'type' => 'SystemUser',
    'native' => false,
  ),
  'Timestamp' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Description' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'SearchIndex' => 
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
			$sql = "SELECT * FROM `File`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = Database::sql($sql);
			while ($result && $row=$result->fetch_assoc()) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new File($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$sql = "INSERT INTO `File` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
			$result = Database::sql($sql);
			$id = Database::getInsertId();
			return self::ROW($id);
		}

		public static function ROW($id) {
			$id = intval($id);
			if (array_key_exists($id, self::$data)) {
				return self::$data[$id];
			} else {
				$rows = self::SELECT("id='".Database::escape($id)."'");
				if (count($rows)) {
					return $rows[0];
				} else {
					return null;
				}
			}
		}

		public function DELETE($physical=true) {
			if ($physical) {
				$sql = "DELETE FROM `File` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `File` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
			}
			Database::sql($sql);
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
			return 'File';
		}

		public function toString() {
			return "File[{$this->id}]";
		}

		// Setters and Getters
public function setHash($value) { $this->row['Hash'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Hash`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getHash() { return $this->row['Hash']; }

public function setSize($value) { $value = str_replace(',', '.', $value); $this->row['Size'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Size`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getSize() { $value = $this->row['Size']; settype($value, 'float'); return $value; }

public function setMime($value) { $this->row['Mime'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Mime`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getMime() { return $this->row['Mime']; }

public function setCounter($value) { $value = str_replace(',', '.', $value); $this->row['Counter'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Counter`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getCounter() { $value = $this->row['Counter']; settype($value, 'float'); return $value; }

public function setName($value) { $this->row['Name'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Name`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getName() { return $this->row['Name']; }


		public function setUser($value) {
			if (is_object($value) && $value->getClassName() == 'SystemUser') {
				$id = $value->getId();
				$sql = "UPDATE `File` SET `User`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['User'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `File` SET `User`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
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
public function setTimestamp($value) { $value = str_replace(',', '.', $value); $this->row['Timestamp'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Timestamp`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getTimestamp() { $value = $this->row['Timestamp']; settype($value, 'float'); return $value; }

public function setDescription($value) { $this->row['Description'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `Description`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getDescription() { return $this->row['Description']; }

public function setSearchIndex($value) { $this->row['SearchIndex'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `File` SET `SearchIndex`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getSearchIndex() { return $this->row['SearchIndex']; }


	}