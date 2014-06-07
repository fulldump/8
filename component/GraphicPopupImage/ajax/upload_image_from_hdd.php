<?php

header('Content-Type: text/html');

$path = $_FILES['image']['tmp_name'];

$image = Image::INSERT($path);

$response = array(
	'id'=>$image->getId(),
	'width'=>$image->getWidth(),
	'height'=>$image->getHeight(),);

echo json_encode($response);

?>