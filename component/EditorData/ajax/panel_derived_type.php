<?php

	$entity = $_POST['entity'];
	$id = $_POST['id'];
	$field = $_POST['field'];
	
	
	// Obtengo el registro del que me están hablando
	$reg = null;
	eval('$reg='.$entity.'::ROW($id);');
	if ($reg != null) {
		// Busco el tipo del campo que me piden:
		$fields = LDM::getFields($entity);
		$type = $fields[$field]['type'];
		
		// Busco los campos del tipo que me piden:
		$fields = LDM::getFields($type);
		
		// Obtengo el objeto que me piden:
		$obj = null;
		eval ('$obj=$reg->get'.$field.'();');
		if ($obj===null) {
			eval('$obj='.$type.'::INSERT();');
			eval('$reg->set'.$field.'($obj);');
		}
		
		$data = array();
		foreach ($fields as $F=>$f) {
			if (LDM::getNative($f['type'])) {
				eval('$row[] = $obj->get'.$F.'();');
			} else {
				eval('$object = $obj->get'.$F.'();');
				if ($object === null) {
					$row[] = '< vacio >';
				} else {
					$row[] = $object->toString();
				}
			}
		}
		$data[] = $row;
		
		
		$response = array(
			'fields'=>$fields,
			'data'=>$data
		);
		
		echo json_encode($response);
		
	}

?>