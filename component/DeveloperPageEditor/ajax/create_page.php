<?php

$id = $_POST['id'];

if ($id==0) {
	$parent = SystemRoute::getRoot();
} else {
	$parent = SystemRoute::ROW($id);
}

$page = SystemPage::INSERT();
$page->setHTML('[[COMPONENT name=SimpleText text:"rellenar este texto"]]');


$node = SystemRoute::INSERT();
$node->setTitle('Página nueva');
$node->setParent($parent);
$node->setController('page');
$node->setReference($page->getId());


print_r($parent);



?>