<?php

$table = $_POST['table'];

$fields = $table::$fields;


function combo($table) {
	$list = $table::SELECT('1 ORDER BY id');
	
	$result = array();
	foreach ($list as &$l) {
		$result[$l->ID()] = $l->toString();
		
	}
	return $result;
}

foreach ($fields as $F=>&$f) {
	if (!$f['native']) {
		$f['combo'] = combo($f['type']);
	}
	$f['name'] = $F;
}

function rows($table) {
	$rows = $table::SELECT('1 ORDER BY id');
	$fields = $table::$fields;
	
	$result = array();
	foreach ($rows as &$r) {
		$row = array();
		$row['id'] = $r->ID();
		foreach ($fields as $F=>$f) {
			$method = 'get'.$F;
			if ($f['native']) {
				$row[$F] = $r->$method();
			} else {
				$row[$F] = intval($r->row[$F]);
			}
		}
		$result[$r->ID()] = $row;
	}
	
	return $result;
}

$result = array(
	'fields' => $fields,
	'rows' => rows($table),
);

echo json_encode($result);

?>