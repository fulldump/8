<?php

header('Content-Type: application/json');

$url = &ControllerPage::$url;

$path = ControllerPage::$path;

if (count($url) == 2) {
	$component = SystemComponent::getComponentByName($url[0]);
	if ($component != null) {
		$ajax = $component->getAjax($url[1]);
		if ($ajax != null) {
			$html = '<?php
	ControllerAbstract::$node = unserialize(\''.serialize(ControllerAbstract::$node).'\');
	ControllerAbstract::$url = '.var_export(ControllerAbstract::$url,true).';
	ControllerAbstract::$language = \''.ControllerAbstract::$language.'\';
?>'.$ajax;
			eval(' ?>'.$ajax.'<?php ');
			if (Config::get('CACHE_AJAX_ENABLED')) {
				SystemCache::INSERT($path, $html);
			}
		}
	}
}

?>