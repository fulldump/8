<?php

$id_image_instance = $_POST['id_image_instance'];
$id_image = $_POST['id_image'];

if ( Session::isLoggedIn())
	ImageInstance::ROW($id_image_instance)->setImage(Image::ROW($id_image));

?>