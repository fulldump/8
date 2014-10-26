<?php

$reference = Router::$root->getById($_POST['id'])->getProperty('reference');

echo SystemPage::get($reference)->setJS($_POST['value']);

?>