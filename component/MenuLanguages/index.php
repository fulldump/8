<ul component="MenuLanguages">
<?php

$default_language = Config::get('DEFAULT_LANGUAGE');
$languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));

// Cargo info extra de idiomas
$extra = array();
$table_languages = Languages::SELECT();
foreach ($table_languages as $l) {
	$extra[$l->getCode()] = $l;
}

Image::PREFETCH('Image', $table_languages);

foreach ($languages as $language) {
	$path = Router::getNodeUrl(Router::$node, $language);
	
	$class = '';
	if ($language == Router::$language)
		$class = ' class="selected"';


	$a_text = $language;
	if (array_key_exists($language, $extra)) {
		$image = $extra[$language]->getImage();
		if (null !== $image) {
			$a_text = '<img src="/img/'.$extra[$language]->getImage()->ID().'/w:32;h:22;">';
		}
	}

	echo "<li><a$class href='$path' hreflang='$language' title='$language'>$a_text</a></li>";
}

?>
</ul>