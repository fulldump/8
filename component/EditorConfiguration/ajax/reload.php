<?php

$query = $_POST['query'];

$result = array();

foreach (Config::getKeys() as $c) {
	$name = $c;
	if ('' == $query || false !== stripos($name, $query)) {
		$result[]=$name;
	}
}

sort($result, SORT_NATURAL | SORT_FLAG_CASE);

echo json_encode($result);

?>