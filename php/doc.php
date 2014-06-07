<?php


function render404() {
	ControllerAbstract::setUrl('/__no_exists__/'.md5(microtime()));
}

if (true || Config::get('DOC_ENABLED')) {

	$path = 'doc/';

	$url = &ControllerAbstract::$url;
	$title = '';
	$body = '';

	if (1 == count($url)) {
		$entry = urldecode($url[0]);
		$filename = $path.$entry;
		if (file_exists($filename)) {
			$title .= " - $entry";
			$body = file_get_contents($filename);
		} else {
			render404();
		}
	} else if (0 == count($url)) {
		$body = '<h1>Index</h1><ul>';

		$d = dir($path);
		$select = array();
		while (false !== ($entry = $d->read())) {
			$entry = pathinfo ($entry);
			if ('html' == $entry['extension']) {
				$select[$entry['filename']] = $entry['basename'];
			}
		}
		$d->close();

		ksort($select);

		foreach($select as $S=>$s) {
			$body.= "<li><a href='/$path$s'>$S</a></li>";
		}

		$body .= '</ul>';
	} else {
		render404();
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Documentation<?=$title?></title>
		<script src="http://docuss.treeweb.es/docuss.js" type="text/javascript"></script>
	</head>
	<body>
<?=$body?>
	</body>
</html>
<?php
} else {
	render404();
}
?>