<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class FileInstance_auto {

		public static $fields = array (
  'Label' => 
  array (
    'type' => 'Label',
    'native' => false,
  ),
  'Type' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Url' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'File' => 
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
			$sql = "SELECT * FROM `FileInstance`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = Database::sql($sql);
			while ($result && $row=$result->fetch_assoc()) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new FileInstance($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$sql = "INSERT INTO `FileInstance` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
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
				$sql = "DELETE FROM `FileInstance` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `FileInstance` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
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
			return 'FileInstance';
		}

		public function toString() {
			return "FileInstance[{$this->id}]";
		}

		// Setters and Getters

		public function setLabel($value) {
			if (is_object($value) && $value->getClassName() == 'Label') {
				$id = $value->getId();
				$sql = "UPDATE `FileInstance` SET `Label`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Label'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `FileInstance` SET `Label`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Label'] = 0;
			}
		}

		public function getLabel() {
			if ($this->row['Label'] == 0) {
				return null;
			} else {
				return Label::ROW($this->row['Label']);
			}
		}
public function setType($value) { $value = str_replace(',', '.', $value); $this->row['Type'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `FileInstance` SET `Type`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getType() { $value = $this->row['Type']; settype($value, 'float'); return $value; }

public function setUrl($value) { $this->row['Url'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `FileInstance` SET `Url`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getUrl() { return $this->row['Url']; }

public function setFile($value) { $this->row['File'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `FileInstance` SET `File`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getFile() { return $this->row['File']; }


	}