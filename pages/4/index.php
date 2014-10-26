<div class="margen">
<ul id="tools">
<?php

foreach(Router::$node->children as $child) {
	echo '<li><a component="TrunkButton" href="'.Router::getNodeUrl($child).'">'.$child->getProperty('title').'</a></li>';
}

?>
</ul>
</div>

[[COMPONENT name=TrunkDoc]]