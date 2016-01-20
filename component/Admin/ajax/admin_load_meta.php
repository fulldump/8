<?php

$result = array();


// Calculate language
$url = $_POST['url'];
$purl = parse_url($url);

$path = explode('/',$purl['path']);
array_shift($path);

$default_language = Config::get('DEFAULT_LANGUAGE');
$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));

if($path[0] != $default_language && in_array($path[0], $available_languages)) {
	$language = $path[0];
	array_shift($path);
} else {
	$language = $default_language;
}

$result['lang'] = $language;

// Determino el nodo en el que empiezo a buscar:

$node = SystemRoute::ROW(Config::get('DEFAULT_PAGE'));
if (count($path) && $node->getChildByUrl($path[0])===null) {
	$root = SystemRoute::getRoot();
	$root_child = $root->getChildByUrl($path[0]);
	if ($root->getChildByUrl($path[0])!==null && $root_child->getId() != $node->getId()) {
		$node = $root;
	}
}

// Busco las rutas a partir del nodo elegido:
$new_node = $node;
while( count($path) && !is_null($new_node) ) {
	$new_node = $new_node->getChildByUrl($path[0]);
	if (!is_null($new_node)) {
		$node = $new_node;
		array_shift($path);
	}
}

ControllerPhp::$language = $language;

$result['id'] = $node->getId();
$result['title'] = $node->getTitle();
$result['description'] = $node->getDescription();

echo json_encode($result);

?>