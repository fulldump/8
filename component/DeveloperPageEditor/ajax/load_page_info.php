<?php

$route = SystemRoute::ROW($_POST['id']);

$id_page = $route->getReference();
$page = SystemPage::ROW($id_page);

$result = array(
	'html'=>$page->getHTML(),
	'css'=>$page->getCSS(),
	'js'=>$page->getJS()
);


$templates = array();
$templates[] = array(
	'name'=>'&lt; Default &gt; ',
	'value'=>''
);

foreach (SystemTemplate::SELECT() as $t) {
	$templates[] = array(
		'name'=>$t->getName(),
		'value'=>$t->getName(),
	);
}

$result = array(
	'title'=>$route->getTitle(),
	'keywords'=>$route->getKeywords(),
	'description'=>$route->getDescription(),
	'templates'=>$templates,
	'template'=>$page->getTemplate()
);

echo json_encode($result);

?>