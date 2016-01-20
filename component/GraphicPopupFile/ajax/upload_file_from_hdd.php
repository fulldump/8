<?php

$paths = $_FILES['image']['tmp_name'];
$mimes = $_FILES['image']['type'];
$names = $_FILES['image']['name'];

$response = array();
foreach ($names as $i=>$name) {
	$file = File::INSERT($paths[$i], $mimes[$i]);
	$file->setName($name);
	$response[] = $file->getId();
}

echo json_encode($response);

?>