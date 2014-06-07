<?php // run

// usleep(500000);

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