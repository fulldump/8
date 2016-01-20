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
} else {
	$language = $default_language;
}

$result['language'] = $language;


// Calculate redirection
$query = array();
if (array_key_exists('query', $purl)) {
	parse_str($purl['query'], $query);
}

if (!array_key_exists('edit', $query)) {
	$query['edit'] = '';
	$result['redirect'] = $url.'?'.http_build_query($query);
}


// Print result
echo json_encode($result);

?>