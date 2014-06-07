<?php

$search = $_POST['search'];

$entities = array();

foreach (Storm::all() as $storm) {
	$model = $storm->getModel();
	$name = $storm->getName();
	if (!$model['native'] && ('' == $search xor false !== stripos($name, $search)) ) {
		$entities[] = $name;
	}
}

sort($entities);

echo json_encode($entities);

?>