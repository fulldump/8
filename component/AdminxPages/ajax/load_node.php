<?php 

$id = $_POST['id'];

$node = Router::$root->getById($id);


$result = array(
	'id' => $node->id,
	'type' => $node->getProperty('type'),
	'properties' => $node->properties,
	'properties_inherited' => $node->getInheritedProperties(),
	'groups' => json_decode(Session::getUser()->getGroups()),
);


echo json_encode($result, Config::get('JSON_ENCODE_OPTIONS'));

?>