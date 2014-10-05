<?php

$origin_id = $_POST['origin'];
$destination_id = $_POST['destination'];
$place = $_POST['place'];



$origin = Router::$root->getById($origin_id);
$destination = Router::$root->getById($destination_id);



if ('insert_before' == $place) {
	$key = $origin->getKey();
	if ($origin->parent->id == $destination->parent->id) {
		$origin->remove();
	}
	$origin->insertBefore($key, $destination);
	Router::save();
}

if ('append' == $place) {
	$destination->append($origin->getKey(), $origin);
	Router::save();
}


if ('insert_after' == $place) {
	$key = $origin->getKey();
	if ($origin->parent->id == $destination->parent->id) {
		$origin->remove();
	}
	$origin->insertAfter($key, $destination);
	Router::save();
}

?>