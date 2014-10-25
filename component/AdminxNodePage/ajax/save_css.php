<?php

$reference = Router::$root->getById($_POST['id'])->getProperty('reference');

echo SystemPage::ROW($reference)->setCSS($_POST['value']);

?>