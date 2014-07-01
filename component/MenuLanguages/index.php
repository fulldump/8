<ul component="MenuLanguages">
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

// Cargo info extra de idiomas
$extra = array();
$table_languages = Languages::SELECT();
foreach ($table_languages as $l) {
	$extra[$l->getCode()] = $l;
}

Image::PREFETCH('Image', $table_languages);

foreach ($languages as $l) {
	$path = '/'.implode('/', $url);
	
	if ($l != $default_language)
		$path = '/'.$l.$path;

	if (array_key_exists('edit', $_GET))
		$path.='?edit';

	$class = '';
	if ($l == $language)
		$class = ' class="selected"';


	$a_text = $l;
	if (array_key_exists($l, $extra)) {
		$image = $extra[$l]->getImage();
		if (null !== $image) {
			$a_text = '<img src="/img/'.$extra[$l]->getImage()->ID().'/w:16;h:11;">';
		}
	}

	echo '<li><a'.$class.' href="'.$path.'" hreflang="'.$l.'" title="'.$l.'">'.$a_text.'</a></li>';
}

?>
</ul>