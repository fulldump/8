<?php

$id = $_POST['id_template'];
$code = $_POST['js'];

SystemTemplate::get($id)->setJS($code);

?>