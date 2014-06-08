<?php

$id = $_POST['id'];
$value = $_POST['value'];
$lang = $_POST['lang'];

ControllerPhp::$language = SystemLanguage::getLanguageByCode($lang);

SystemRoute::ROW($id)->setTitle($value);

?>