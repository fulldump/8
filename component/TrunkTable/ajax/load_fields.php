<?php

	$name = $_POST['name'];

	$result = array(
		'id' => array(
			'type' => 'PrimaryKey',
			'native' => true,
		),
	);
	if (Storm::exists($name)) {
		$result = array_merge($result, $name::$fields);
	}

	echo json_encode($result);

?>