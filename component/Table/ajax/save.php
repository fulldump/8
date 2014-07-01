<?php

$table = $_POST['table'];
$row = $_POST['row'];
$field = $_POST['field'];
$value = $_POST['value'];

$set = 'set'.$field;
$get = 'get'.$field;

$table::ROW($row)->$set($value);

echo $table::ROW($row)->$get($value);
?>