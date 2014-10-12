<?php

if(1 != count(Router::$parts)) {
	return;
}

$id = Router::$parts[0];
if ($id == Router::$node->id) {
	// Avoid circular reference
	return;
}

$node = Router::$root->getById($id);
if (null === $node) {
	// Id must exists
	return;
}

header('Location: '.Router::getNodeUrl($node, true));

?>