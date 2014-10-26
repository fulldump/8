<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class EmailerLog_auto {

		public static $fields = array (
  'From' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'To' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Timestamp' => 
  array (
    'type' => 'Number',
    'native' => true,
  ),
  'Email' => 
  array (
    'type' => 'EmailerHistory',
    'native' => false,
  ),
  'Parameters' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Result' => 
  array (
    'type' => 'Number',
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
			$sql = "SELECT * FROM `EmailerLog`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = Database::sql($sql);
			while ($result && $row=$result->fetch_assoc()) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new EmailerLog($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$sql = "INSERT INTO `EmailerLog` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
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
				$sql = "DELETE FROM `EmailerLog` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `EmailerLog` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
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
			return 'EmailerLog';
		}

		public function toString() {
			return "EmailerLog[{$this->id}]";
		}

		// Setters and Getters
public function setFrom($value) { $this->row['From'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `EmailerLog` SET `From`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getFrom() { return $this->row['From']; }

public function setTo($value) { $this->row['To'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `EmailerLog` SET `To`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getTo() { return $this->row['To']; }

public function setTimestamp($value) { $value = str_replace(',', '.', $value); $this->row['Timestamp'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `EmailerLog` SET `Timestamp`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getTimestamp() { $value = $this->row['Timestamp']; settype($value, 'float'); return $value; }


		public function setEmail($value) {
			if (is_object($value) && $value->getClassName() == 'EmailerHistory') {
				$id = $value->getId();
				$sql = "UPDATE `EmailerLog` SET `Email`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Email'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `EmailerLog` SET `Email`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Email'] = 0;
			}
		}

		public function getEmail() {
			if ($this->row['Email'] == 0) {
				return null;
			} else {
				return EmailerHistory::ROW($this->row['Email']);
			}
		}
public function setParameters($value) { $this->row['Parameters'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `EmailerLog` SET `Parameters`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getParameters() { return $this->row['Parameters']; }

public function setResult($value) { $value = str_replace(',', '.', $value); $this->row['Result'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `EmailerLog` SET `Result`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'"; Database::sql($sql); } public function getResult() { $value = $this->row['Result']; settype($value, 'float'); return $value; }


	}
