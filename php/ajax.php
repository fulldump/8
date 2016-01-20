<?php

$param = Router::$parameters;

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

eval('?>'.$ajax);

// TODO: fix this !!!
if (Config::get('CACHE_AJAX_ENABLED')) {
	$cached = Router::export().$ajax;

	Cache::add(Router::$url, $cached);

	$filename = 'cache/'.md5(Router::$url);

	file_put_contents($filename, php_strip_whitespace($filename));

}

?>