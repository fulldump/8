<?php

$key = $_POST['key'];

$result = array(
	'key' => $key,
	'description' => Config::getDescription($key),
	'type' => Config::getType($key),
	'default_value' => Config::getDefault($key),
	'value' => Config::get($key),
);

echo json_encode($result);


?>