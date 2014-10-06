<?php

header('Content-Type: application/json');

$param = $this->router->parameters;

if (!array_key_exists('{component}', $param)) {
	echo 'show all component list if mode debug on. Else 404';
	return;
}

if (!array_key_exists('{call}', $param)) {
	echo "See all calls for the component '{$parameters['{component}']}' if mode debug on. Else 404";
	return;
}

$component = SystemComponent::getComponentByName($param['{component}']);
if (null === $component) {
	echo "Component does not exist (debug mode). Else 404";
	return;
}

$ajax = $component->getAjax($param['{call}']);
if (null === $ajax) {
	echo "Ajax does not exist (debug mode). Else 404";
	return;
}


// TODO: fix this !!!

// $html = '<?php
// ControllerAbstract::$node = unserialize(\''.serialize(ControllerAbstract::$node).'\');
// ControllerAbstract::$url = '.var_export(ControllerAbstract::$url,true).';
// ControllerAbstract::$language = \''.ControllerAbstract::$language.'\';
// ? >'.$ajax;
eval(' ?>'.$ajax.'<?php ');

// TODO: fix this !!!
// if (Config::get('CACHE_AJAX_ENABLED')) {
// 	Cache::add($path, $html);
// }

?>