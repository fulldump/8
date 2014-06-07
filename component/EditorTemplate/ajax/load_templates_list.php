<?php


$search = $_POST['search'];


$templates = SystemTemplate::SELECT($search);

$result = array();

foreach ($templates as $t) {
	$result[] = array(
		'id'=>$t->getName(),
		'name'=>$t->getName()
	);
}

echo json_encode($result);


?>