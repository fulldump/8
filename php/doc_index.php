<?php

if (!Config::get('DOC_ENABLED')) {
	Router::setUrl(time());
	return;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Documentation</title>
		<script src="http://docuss.treeweb.es/docuss.js" type="text/javascript"></script>
	</head>
	<body>
		<h1>Index</h1>
		<ul>
<?php
	$path = 'doc/';

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
		echo "<li><a href='/$path$s'>$S</a></li>";
	}
?>
		</ul>
	</body>
</html>