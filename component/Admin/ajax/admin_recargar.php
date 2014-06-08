<?php

$url = $_POST['url'];
$purl = parse_url($url);

$path = explode('/',$purl['path']);
array_shift($path);

// Determino el idioma
$language = SystemLanguage::getLanguageByCode($path[0]);
if(is_null($language) || $path[0]==Configuration::get('DEFAULT_LANGUAGE')) {
	$language = SystemLanguage::getLanguageByCode(Configuration::get('DEFAULT_LANGUAGE'));
} else {
	array_shift($path);
}






$result = array(
	'language'=>$language->getName(),
);

if ($purl['query'] != edit) {
	$result['redirect'] = $url.'?edit';
}


echo json_encode($result);

?>