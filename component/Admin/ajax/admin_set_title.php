<?php

$id = $_POST['id'];
$value = $_POST['value'];
$lang = $_POST['lang'];

ControllerPhp::$language = $lang;

SystemRoute::ROW($id)->setTitle($value);

?>