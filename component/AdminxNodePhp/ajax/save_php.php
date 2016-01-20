<?php

$reference = Router::$root->getById($_POST['id'])->getProperty('reference');

SystemPhp::get($reference)->setPHP($_POST['value']);

?>