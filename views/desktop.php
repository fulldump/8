<?php 

self::$css = '<?php header(\'Content-Type: text/css; charset=UTF-8\');
 header("Expires: ".date("r", time()+9999999));
?>'.self::$css;

self::$js = '<?php header(\'Content-Type: text/javascript; charset=UTF-8\');
 header("Expires: ".date("r", time()+9999999));
?>'.self::$js;

$hash_css = md5(self::$css);
Cache::add('/cache-css/'.$hash_css, self::$css);

$hash_js = md5(self::$js);
Cache::add('/cache-js/'.$hash_js, self::$js);

$ga = Config::get('GOOGLE_ANALYTICS');
if (strlen($ga)) $ga = "\n\t\t".$ga;

self::$html = '<?php
	header(\'Content-Type: text/html; charset=UTF-8\');

	require_once(\'class/Main.class.php\');

	ob_start();?>'.self::$html.'<?php
	$_HTML = ob_get_clean();
?><!DOCTYPE HTML>
<html lang="'.ControllerAbstract::$language.'">
	<head>
		<meta http-equiv="Content-Type" CONTENT="text/html; charset=UTF-8">
		<title>'.htmlentities(self::$title, ENT_COMPAT, 'UTF-8').'</title>
		<meta name="keywords" content="'.htmlentities(self::$keywords, ENT_COMPAT, 'UTF-8').'">
		<meta name="description" content="'.htmlentities(self::$description, ENT_COMPAT, 'UTF-8').'">
		<meta name="apple-touch-fullscreen" content="YES">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
		<link rel="stylesheet" type="text/css" href="/cache-css/'.$hash_css.'" title="default">
		<script src="/cache-js/'.$hash_js.'" type="text/javascript"></script>
		<link href="/favicon.ico" rel="icon" type="image/x-icon">'.$ga.'
	</head>
	<body>
<?php echo $_HTML; ?> 
	</body>
</html>';
