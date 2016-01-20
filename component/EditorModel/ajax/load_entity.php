<?php

	$model = Storm::get($_POST['entity'])->getModel();
	
	$entities = array();
	foreach (Storm::all() as $storm) {
		$entities[] = $storm->getName();
	}

	echo json_encode(array(
		'model' => $model,
		'entities'=>$entities
	));

?>