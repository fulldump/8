<nav class="menu">
<?php
if (!isset($data['type']))
	$data['type'] = 1;

if (!isset($data['root']))
	$data['root'] = 'default';

if (!isset($data['showroot']))
	$data['showroot'] = true;


if ($data['showroot'] == 'false')
	$data['showroot'] = false;


if ($data['root'] == 'default') {
	$node = SystemRoute::ROW(Config::get('DEFAULT_PAGE'));
} else if ($data['root'] == 'page' ) {
	$node = ControllerPage::$node;
} else {
	$node = SystemRoute::ROW($data['root']);
}




if ($data['type']==1)
	Lib::menu1($node, $data['showroot']);

if ($data['type']==2)
	Lib::menu2(ControllerPage::$node, $node->getId(), $data['showroot']);


?>
</nav>