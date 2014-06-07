<ul>
<?php

$url = ControllerPage::$path;
$url = explode('/',$url); array_shift($url);

$default_language = Config::get('DEFAULT_LANGUAGE');
$languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));

// Determino el idioma

if(in_array($url[0], $languages) && $url[0] !=$language) {
	$language = $url[0];
	array_shift($url);
} else {
	$language = $default_language;
}

foreach ($languages as $l) {
	$path = '/'.implode('/', $url);
	
	if ($l != $default_language)
		$path = '/'.$l.$path;

	if (array_key_exists('edit', $_GET))
		$path.='?edit';

	$class = '';
	if ($l == $language)
		$class = ' class="selected"';

	echo '<li><a'.$class.' href="'.$path.'" hreflang="'.$l.'">'.$l.'</a></li>';
}



?>
</ul>