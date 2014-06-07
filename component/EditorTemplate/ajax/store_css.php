<?php

$id = $_POST['id_template'];
$code = $_POST['css'];

SystemTemplate::get($id)->setCSS($code);

?>