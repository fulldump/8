<?php


$entities = array();

foreach (Storm::all() as $storm) {
	$model = $storm->getModel();
	if (!$model['native']) {
		$entities[] = $storm->getName();
	}
}

sort($entities);

echo json_encode($entities);

?>