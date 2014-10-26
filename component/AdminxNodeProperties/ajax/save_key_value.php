<?php 

$id = $_POST['id'];

$node = Router::$root->getById($id);
$node->properties[$_POST['key']] = $_POST['value'];
Router::save();

$result = array(
	'properties' => $node->properties,
	'properties_inherited' => $node->getInheritedProperties(),
);
echo json_encode($result, Config::get('JSON_ENCODE_OPTIONS'));

?>