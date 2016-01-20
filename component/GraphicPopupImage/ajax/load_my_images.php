<?php


$images = Image::SELECT();

$result = array();
foreach ($images as $i)
	$result[] = $i->getId();

echo json_encode($result);

?>