<?php


$entities = array();

foreach (Storm::all() as $storm) {
	$entities[] = $storm->getName();
}

sort($entities);

echo json_encode($entities);

?>