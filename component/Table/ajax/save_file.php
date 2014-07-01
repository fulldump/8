<?php

$table = $_POST['table'];
$row = $_POST['row'];
$field = $_POST['field'];
$file = $_FILES['file'];

$response = array();

if ($file['error']) {
	http_response_code(500);
} else {
	$new_file = File::INSERT($file['tmp_name'], $file['mime']);
	$new_file->setName($file['name']);

	$set = 'set'.$field;

	$table::ROW($row)->$set($new_file);
}

echo json_encode($response);

?>