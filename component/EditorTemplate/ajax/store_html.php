<?php

$id = $_POST['id_template'];
$code = $_POST['html'];

SystemTemplate::get($id)->setPHP($code);

?>