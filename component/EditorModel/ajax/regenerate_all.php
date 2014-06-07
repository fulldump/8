<?php

foreach (Storm::all() as $item) {
	$item->regenerate();
}

?>