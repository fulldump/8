<?php

$reference = Router::$root->getById($_POST['id'])->getProperty('reference');

echo SystemPage::get($reference)->setPHP($_POST['value']);

?>