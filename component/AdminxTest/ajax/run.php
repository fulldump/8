<?php // run

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);

$file = $_POST['file'];
$test = $_POST['test'];

ob_start();
$test = Test::run($file, $test);
$output = ob_get_clean();


echo json_encode(array(
	'pass' => $test,
	'output' => $output,
));

?>