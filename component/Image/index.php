<?php

$id=0;	
eval ('$id='.$data['id'].';');
$image = ImageInstance::ROW($id);

if (array_key_exists('style', $data)) {
	$options = "/{$data['style']}";
}

if (array_key_exists('edit', $_GET) && Session::isLoggedIn()) { 
	echo '<img id="Image'.$id.'" edit_id="'.$id.'" src="/img/'.$image->getImage()->getId().$options.'" alt="'.$image->getDescription().'">';
} else {
	echo '<img src="/img/'.$image->getImage()->getId().$options.'" alt="'.$image->getDescription().'">';
}

?>