<?php

$name = $_POST['id_component'];

$list = SystemComponent::get($name)->getAjaxNames();

$result = array();
foreach ($list as $l)
	$result[$l] = array(
		'id'=>$l,
		'name'=>$l
	);

sort($result);

echo json_encode($result);

?>