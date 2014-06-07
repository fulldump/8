<?php

$id = $_POST['id'];

$route = SystemRoute::ROW($id);
$id_page = $route->getReference();
$page = SystemPage::ROW($id_page);

$page->DELETE();
$route->DELETE();


?>