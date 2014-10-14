<?php

function render404() {
	Router::setUrl(time());
}

if (!Config::get('DOC_ENABLED')) {
	render404();
	return;
}

$path = 'doc/';
$entry = urldecode(Router::$parameters['{item}']);
$filename = $path.$entry;
if (!file_exists($filename)) {
	render404();
	return;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Documentation - <?=$title?></title>
		<script src="http://docuss.treeweb.es/docuss.js" type="text/javascript"></script>
	</head>
	<body>
		<?php echo file_get_contents($filename); ?>
	</body>
</html>
