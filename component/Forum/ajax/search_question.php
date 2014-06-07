<?php

	$text = $_POST['text'];
	
	$words = explode(' ', $text);
	
	$where = " ResponseTo = 0 ";
	
	foreach ($words as $w)
		if (trim($w) != '')
			$where .= " AND Text LIKE '%".mysql_real_escape_string($w)."%'";
	
	
	$where .= ' ORDER BY Timestamp DESC ';
	
	$questions = Forum::SELECT($where);
	
	foreach ($questions as $q)
		$q->drawQuestion();

?>