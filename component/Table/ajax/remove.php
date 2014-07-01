<?php

$table = $_POST['table'];
$row = $_POST['row'];

$table::ROW($row)->DELETE();

?>