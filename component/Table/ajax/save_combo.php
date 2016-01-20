<?php

$table = $_POST['table'];
$row = $_POST['row'];
$field = $_POST['field'];
$value = $_POST['value'];

$fields = $table::$fields;
$subtable = $fields[$field]['type'];

$set = 'set'.$field;
$get = 'get'.$field;

$table::ROW($row)->$set($subtable::ROW($value));

?>