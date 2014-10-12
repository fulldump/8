<?php


function pages_tree ($node) {
	$result = array();
	$children = $node->getChildren();
	foreach ($children as $c) {
		//if ($c->getController() == 'page' && $c->getTitle() != 'adminx' && $c->getTitle() != '_admin' && $c->getTitle() != 'profile')
		$result[] = array(
			'id'=>$c->getId(),
			'title'=>$c->getTitle(),
			'children'=>pages_tree($c)
		);
	}
	return $result;
}


$st = pages_tree(SystemRoute::ROW(1));

$result = array(
	array(
		'id'=>1,
		'title'=>$_SERVER['SERVER_NAME'],
		'children'=>$st
	)
);




echo json_encode($result);

?>