<div class="migas">
<nav class="breadcrumb">
<?php

print_r($ControllerAbstract::$language);

$node = ControllerPage::$node;

$migas = '';

while (!$node->isRoot() && !$node->isDefault()) {
	$migas = ' / <a href="'.$node->getPath().'">'.$node->getTitle().'</a>'.$migas;
	$node = $node->getParent();
}

if ($node->isDefault()) {
	echo ' / <a href="'.$node->getPath().'">'.$node->getTitle().'</a>';
}

echo $migas;
?>
</nav>
</div>