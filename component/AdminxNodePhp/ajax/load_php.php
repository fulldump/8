<?php

$reference = Router::$root->getById($_POST['id'])->getProperty('reference');

echo SystemPhp::get($reference)->getPHP();

?>