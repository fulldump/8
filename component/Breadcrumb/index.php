<div class="migas">
	<nav class="breadcrumb">
<?php

$node = Router::$node;

$migas = '';

while (null != $node->parent && $node->id != Config::get('DEFAULT_PAGE')) {
	$migas = ' / <a href="'.'$node->getPath()'.'">'.'$node->getTitle()'.'</a>'.$migas;
	$node = $node->parent;
}

if (null != $node->parent) {
	echo '<a href="'.'$node->getPath()'.'">'.'$node->getTitle()'.'</a>';
}

echo $migas;
?>
	</nav>
</div>