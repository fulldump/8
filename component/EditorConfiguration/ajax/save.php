<?php

$key = $_POST['key'];
$value = $_POST['value'];

Config::set($key, $value);

?>