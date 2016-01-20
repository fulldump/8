<?php

	/**
	 * # CAUTION #
	 *
	 * Autogenerated code. Any changes in this file will be lost.
	 * To add extra behaviour, please, edit the extended class (extended.class.php)
	 *
	*/

	class DocumentPart_auto {

		public static $fields = array (
  'Document' => 
  array (
    'type' => 'Document',
    'native' => false,
  ),
  'PreviousPart' => 
  array (
    'type' => 'DocumentPart',
    'native' => false,
  ),
  'NextPart' => 
  array (
    'type' => 'DocumentPart',
    'native' => false,
  ),
  'Data' => 
  array (
    'type' => 'Text',
    'native' => true,
  ),
  'Type' => 
  array (
    'type' => 'DocumentPartType',
    'native' => false,
  ),
  'Image' => 
  array (
    'type' => 'Image',
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
			$sql = "SELECT * FROM `DocumentPart`";
			if ($where !== null)
				$sql .= " WHERE ".$where;

			$select = array();
			$result = Database::sql($sql);
			while ($result && $row=$result->fetch_assoc()) {
				$id = $row['id'];
				if (!array_key_exists($id, self::$data))
					self::$data[$id] = new DocumentPart($row);
				$select[] = &self::$data[$id];
			}
			return $select;
		}
		
		public static function INSERT() {
			$sql = "INSERT INTO `DocumentPart` (`id`, `__timestamp__`, `__operation__`) VALUES (NULL, ".time().", 'INSERT')";
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
				$sql = "DELETE FROM `DocumentPart` WHERE id='".$this->id."'";
				unset(self::$data[$this->id]);
			} else {
				$sql = "UPDATE `DocumentPart` SET `__timestamp__` = ".time().", `__operation__` = 'DELETE' WHERE `id`='".$this->id."'";
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
			return 'DocumentPart';
		}

		public function toString() {
			return "DocumentPart[{$this->id}]";
		}

		// Setters and Getters

		public function setDocument($value) {
			if (is_object($value) && $value->getClassName() == 'Document') {
				$id = $value->getId();
				$sql = "UPDATE `DocumentPart` SET `Document`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Document'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `DocumentPart` SET `Document`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Document'] = 0;
			}
		}

		public function getDocument() {
			if ($this->row['Document'] == 0) {
				return null;
			} else {
				return Document::ROW($this->row['Document']);
			}
		}

		public function setPreviousPart($value) {
			if (is_object($value) && $value->getClassName() == 'DocumentPart') {
				$id = $value->getId();
				$sql = "UPDATE `DocumentPart` SET `PreviousPart`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['PreviousPart'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `DocumentPart` SET `PreviousPart`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['PreviousPart'] = 0;
			}
		}

		public function getPreviousPart() {
			if ($this->row['PreviousPart'] == 0) {
				return null;
			} else {
				return DocumentPart::ROW($this->row['PreviousPart']);
			}
		}

		public function setNextPart($value) {
			if (is_object($value) && $value->getClassName() == 'DocumentPart') {
				$id = $value->getId();
				$sql = "UPDATE `DocumentPart` SET `NextPart`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['NextPart'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `DocumentPart` SET `NextPart`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['NextPart'] = 0;
			}
		}

		public function getNextPart() {
			if ($this->row['NextPart'] == 0) {
				return null;
			} else {
				return DocumentPart::ROW($this->row['NextPart']);
			}
		}
public function setData($value) { $this->row['Data'] = $value; $value = Database::escape($value); $timestamp = time(); $sql = "UPDATE `DocumentPart` SET `Data`='$value',`__timestamp__` = $timestamp, `__operation__` = 'UPDATE' WHERE `id`='{$this->id}'"; Database::sql($sql);} public function getData() { return $this->row['Data']; }


		public function setType($value) {
			if (is_object($value) && $value->getClassName() == 'DocumentPartType') {
				$id = $value->getId();
				$sql = "UPDATE `DocumentPart` SET `Type`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Type'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `DocumentPart` SET `Type`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Type'] = 0;
			}
		}

		public function getType() {
			if ($this->row['Type'] == 0) {
				return null;
			} else {
				return DocumentPartType::ROW($this->row['Type']);
			}
		}

		public function setImage($value) {
			if (is_object($value) && $value->getClassName() == 'Image') {
				$id = $value->getId();
				$sql = "UPDATE `DocumentPart` SET `Image`='".$id."',	`__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Image'] = $id;
			} else if ($value === null) {
				$sql = "UPDATE `DocumentPart` SET `Image`='0', `__timestamp__` = ".time()." WHERE `id`='".$this->id."'";
				Database::sql($sql);
				$this->row['Image'] = 0;
			}
		}

		public function getImage() {
			if ($this->row['Image'] == 0) {
				return null;
			} else {
				return Image::ROW($this->row['Image']);
			}
		}

	}