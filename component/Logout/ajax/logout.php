<pre>
<?php 

Users::logout();

$callback = urldecode($_GET['callback']);

header("Location: {$callback}");

?>