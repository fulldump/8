<?php

public function set__field_name__($value) {
	$value = str_replace(',', '.', $value);
	$this->row['__field_name__'] = $value;

	$value = mysql_real_escape_string($value);
	$timestamp = time();
	$sql = "UPDATE `__table_name__` SET `__field_name__`='$value', `__timestamp__` = $timestamp, `__operation__` = 'UPDATE'  WHERE `id`='{$this->id}'";
	Database::getInstance()->sql($sql);
}

public function get__field_name__() {
	$value = $this->row['__field_name__'];
	settype($value, 'float');
	return $value;
}
