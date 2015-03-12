<?php

$id='';	
eval ('$id='.var_export($data['id'], true).';');

$edit = !in_array('noedit', $flags);

$image = ImageInstance::getByName($id);
if (null === $image) {
	$image = ImageInstance::INSERT();
	$image->setName($id);
}
$options = '';
if (array_key_exists('style', $data)) {
	$options = "/{$data['style']}";
}

$url = "/img/{$image->getImage()->getId()}{$options}";

if (in_array('background', $flags)) {
	if (array_key_exists('edit', $_GET) && Session::isLoggedIn() && $edit) { 
		echo "<div style=\"background-image:url('$url')\" edit_id='$id' edit_options='$options' component='Image' class='background'></div>";
	} else {
		echo "<div style=\"background-image:url('$url')\" component='Image' class='background'></div>";
	}
} else {
	if (array_key_exists('edit', $_GET) && Session::isLoggedIn() && $edit) { 
		echo "<img src='$url' alt='{$image->getDescription()}' edit_id='$id' edit_options='$options' component='Image' editable>";
	} else {
		echo "<img src='$url' alt='{$image->getDescription()}'>";
	}
}


?>