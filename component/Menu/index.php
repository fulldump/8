<?php
if (!isset($data['type']))
	$data['type'] = 1;

if (!isset($data['root']))
	$data['root'] = 'default';


$showroot = true;
if (isset($data['showroot']) && $data['showroot'] == 'false')
	$showroot = false;

$class = 'menu';
if (isset($data['class']))
	$class = $data['class'];


if ($data['root'] == 'default') {
	$node = SystemRoute::ROW(Config::get('DEFAULT_PAGE'));
} else if ($data['root'] == 'page' ) {
	$node = ControllerPage::$node;
} else {
	$node = SystemRoute::ROW($data['root']);
}
?>
<nav class="<?=$class?>">
<?php

if ($data['type']==1)
	Lib::menu1($node, $showroot);

if ($data['type']==2)
	Lib::menu2(ControllerPage::$node, $node->getId(), $showroot);


?>
</nav>