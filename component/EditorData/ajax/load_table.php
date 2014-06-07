<?php

	$entity = $_POST['entity'];
	$query = $_POST['query'];

	$fields = $entity::$fields;

	
	$query_words = explode(' ', $query);
	$where = 'TRUE ';
	foreach ($query_words as $qw) 
		if ($qw != '') {
			$where .= ' AND ( FALSE ';
			foreach ($fields as $F=>$f) {
				$where .= " OR ( `".$F."` LIKE '%".$qw."%' ) ";
			}
			$where .= ') ';
		}
	
	$where .= ' ORDER BY id LIMIT 1000';

	$list =  $entity::SELECT($where);// eval('$list = '.$entity.'::SELECT($where); ');

	$data = array();

	foreach ($list as $l) {
		$row = array();
		$row['Id'] = $l->ID();
		foreach ($fields as $F=>$f) {
			$method = 'get'.$F;
			if ($f['native']) {
				$row[$F] = $l->$method();
			} else {
				$object = $l->$method();
				if ($object === null) {
					$row[$F] = '< vacio >';
				} else {
					$row[$F] = $object->toString();
				}
			}
		}
		
		$data[] = $row;
	}
	
	$response = array(
		'fields'=>$fields,
		'data'=>$data
	);
	
	echo json_encode($response);

?>