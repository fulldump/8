<div class="migas">
	<nav class="breadcrumb">
<?php

$node = Router::$node;

$migas = '';

while (null != $node->parent && $node->id != Config::get('DEFAULT_PAGE')) {
	$migas = ' / <a href="'.Router::getNodeUrl($node).'">'.$node->getProperty('title').'</a>'.$migas;
	$node = $node->parent;
}

if (null != $node->parent) {
	echo '<a href="'.Router::getNodeUrl($node).'">'.$node->getProperty('title').'</a>';
}

echo $migas;
?>
	</nav>
</div>