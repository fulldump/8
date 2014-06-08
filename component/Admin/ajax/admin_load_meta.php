<?php

$url = $_POST['url'];
$purl = parse_url($url);


$url = explode('/',$purl['path']); array_shift($url);

// Determino el idioma
$language = SystemLanguage::getLanguageByCode($url[0]);
if(is_null($language) || $url[0]==Configuration::get('DEFAULT_LANGUAGE')) {
	$language = SystemLanguage::getLanguageByCode(Configuration::get('DEFAULT_LANGUAGE'));
} else {
	array_shift($url);
}

// Determino la vista
$view = SystemView::getViewByCode($url[0]);
if(is_null($view) || $url[0]==Configuration::get('DEFAULT_VIEW')) {
	$view = SystemView::getViewByCode(Configuration::get('DEFAULT_VIEW'));
} else {
	array_shift($url);
}

// Determino el nodo en el que empiezo a buscar:
$node = SystemRoute::ROW(Configuration::get('DEFAULT_PAGE'));
if ($node->getChildByUrl($url[0])===null) {
	$root = SystemRoute::getRoot();
	$root_child = $root->getChildByUrl($url[0]);
	if ($root->getChildByUrl($url[0])!==null
		&& $root_child->getId() != $node->getId()) {
		$node = $root;
	}
}


// Busco las rutas a partir del nodo elegido:
$new_node = $node;
while( count($url) && !is_null($new_node) ) {
	$new_node = $new_node->getChildByUrl($url[0]);
	if (!is_null($new_node)) {
		$node = $new_node;
		array_shift($url);
	}
}

ControllerPhp::$language = $language;

$result = array(
	'id'=>$node->getId(),
	'lang'=>$language->getUrl(),
	'title'=>$node->getTitle(),
	'description'=>$node->getDescription()
);

echo json_encode($result);

?>