<?php

$id = $_POST['id'];
$key = $_POST['key'];
$type = $_POST['type'];


$parent_node = Router::$root->getById($id);

if (null !== $parent_node) {
	$new_node = new Node();
	$new_node->properties['type'] = $type;
	if ($parent_node->append($key, $new_node) ) {
		echo json_encode($new_node->toArray());
		Router::save();
	}
}

?>