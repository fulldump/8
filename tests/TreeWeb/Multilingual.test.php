<?php

function performance_json_serialize($n, $data) {
	// JSON
	$start = microtime(true);
	for ($i=0; $i<$n; $i++) {
		json_encode($data);
	}
	$end = microtime(true);
	$time_json = $end - $start;
	echo "time json_encode: $time_json\n";

	// SERIALIZE
	$start = microtime(true);
	for ($i=0; $i<$n; $i++) {
		serialize($data);
	}
	$end = microtime(true);
	$time_serialize = $end - $start;
	echo "time serialize: $time_serialize\n";

	// RATIO
	$ratio = $time_json / $time_serialize;
	echo "Ratio: $ratio\n";
}

function performance_json_unserialize($n, $data) {
	$json = json_encode($data);
	$serial = serialize($data);

	// JSON
	$start = microtime(true);
	for ($i=0; $i<$n; $i++) {
		$h = json_decode($json);
	}
	$end = microtime(true);
	$time_json = $end - $start;
	echo "time json_decode: $time_json\n";

	// SERIALIZE
	$start = microtime(true);
	for ($i=0; $i<$n; $i++) {
		$h = unserialize($serial);
	}
	$end = microtime(true);
	$time_serialize = $end - $start;
	echo "time unserialize: $time_serialize\n";

	// RATIO
	$ratio = $time_json / $time_serialize;
	echo "Ratio: $ratio\n";
}





// Tests:

$tests = array();

$tests['Config'] = function() {
	$pass = true;

	// Initialize:
	Multilingual::$default_language = null;
	Multilingual::$available_languages = null;
	Multilingual::config();

	// Check default language
	$default_language = Config::get('DEFAULT_LANGUAGE');
	if ($default_language != Multilingual::$default_language) {
		echo "Default language does not match with configuration\n";
		$pass = false;
	}

	// Check available languages
	$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));
	if (count(array_diff($available_languages, Multilingual::$available_languages))) {
		echo "Available languages does not match with configuration\n";
		$pass = false;
	}

	echo "Default language: ".Multilingual::$default_language."\n";
	echo "Available languages: ".implode(', ',Multilingual::$available_languages)."\n";

	return $pass;
};

$tests['Get empty'] = function() {
	$pass = true;

	$raw = '';
	$result = Multilingual::get($raw);

	if (null !== $result) {
		echo "Get must return null\n";
		$pass = false;
	}

	return $pass;
};

$tests['Get existing'] = function() {
	$pass = true;

	// Preparing input
	$default_language = Config::get('DEFAULT_LANGUAGE');
	$message = md5(microtime());
	$structure = array(
		$default_language => $message,
	);
	$raw = serialize($structure);

	echo "$raw\n";

	// Result
	$result = Multilingual::get($raw);
	echo "$result\n";

	if ($result !== $message) {
		echo "Returned message does not match\n";
		$pass = false;
	}

	return $pass;
};


$tests['Get unexisting'] = function() {
	$pass = true;

	// Preparing input
	$language = '-'.Config::get('DEFAULT_LANGUAGE').'-';
	$message = md5(microtime());
	$structure = array(
		$language => $message,
	);
	$raw = serialize($structure);

	echo "$raw\n";

	// Result
	$result = Multilingual::get($raw);
	echo "$result\n";

	if ($result !== $message) {
		echo "Returned message does not match\n";
		$pass = false;
	}

	return $pass;
};

$tests['Set empty'] = function() {
	$pass = true;

	// Preparing input
	$language = Config::get('DEFAULT_LANGUAGE');
	$message = md5(microtime());
	$structure = array(
		$language => $message,
	);
	$raw = serialize($structure);

	echo "$raw\n";

	// Result
	$result = Multilingual::set($raw, $message);
	echo "$result\n";

	if ($result !== $raw) {
		echo "Returned serial does not match\n";
		$pass = false;
	}

	return $pass;
};

$tests['Set unexisting'] = function() {
	$pass = true;

	// Preparing input
	$input_raw = 'a:1:{s:2:"ww";s:32:"de4dab5809790f70612c24d9745bc19a";}';
	$output =    'a:2:{s:2:"ww";s:32:"de4dab5809790f70612c24d9745bc19a";s:2:"es";s:32:"a1fcff8ab87048ff440b78810749d381";}';
	$input_message =                                                                  "a1fcff8ab87048ff440b78810749d381";

	echo "$input_raw\n";

	// Result
	$result = Multilingual::set($input_raw, $input_message);
	echo $result;

	if ($result !== $output) {
		echo "Returned serial does not match\n";
		$pass = false;
	}

	return $pass;
};


$tests['Set existing'] = function() {
	$pass = true;

	// Preparing input
	$dl = Config::get('DEFAULT_LANGUAGE');
	$input = array(
		'w' => md5(microtime()),
		$dl => md5(microtime()),
	);
	$input_raw = serialize($input);
	$input_message = md5(microtime());

	$output = serialize(array(
		'w' => $input['w'],
		$dl => $input_message,
	));


	// Result
	$result = Multilingual::set($input_raw, $input_message);

	// Print
	
	echo "$input_raw\n";
	echo "$result\n";
	echo "input message:                                                      $input_message\n";

	if ($result !== $output) {
		echo "Returned serial does not match\n";
		$pass = false;
	}

	return $pass;
};

$tests['Type check'] = function() {
	$pass = true;


	$c = new stdClass();
	$c->my = 'MY';
	$c->std = 'STD';
	$c->class = 'CLASS';

	$battery = array(
		null,
		true,
		false,
		-1,
		0,
		1,
		-1.0,
		0.0,
		1.0,
		"text string",
		array("this", "is", "an", "array"),
		$c,
	);


	foreach ($battery as $input) {
		$raw = Multilingual::set('',$input);
		$output = Multilingual::get($raw);

		$type_ok = gettype($input) === gettype($output);

		if ($type_ok) {
			echo gettype($input)."\tOK\n";
		} else {
			echo gettype($input)."\tERR\n";
		}

		if (!$type_ok) {
			echo "^^^^ Type does not match \n";
			$pass = false;
		}
	}


	return $pass;
};











$tests['Performance json_encode vs serialize'] = function() {

	// Small arrays
	$n = 10000;
	$data = array(
		"a0" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a1" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a2" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a3" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a4" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a5" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a6" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a7" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a8" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a9" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
	);

	echo "TEST: $n small arrays\n";
	performance_json_serialize($n, $data);


	// Big arrays
	$n = 1000;
	$data = array();
	for ($i = 0; $i<10; $i++) {
		$s = '';
		for ($j = 0; $j<100; $j++) {
			$s .= md5(microtime());
		}
		$data[] = $s;
	}

	echo "\n\nTEST: $n big arrays\n";
	performance_json_serialize($n, $data);



	return true;
};

$tests['Performance json_decode vs unserialize'] = function() {

	// Small arrays
	$n = 10000;
	$data = array(
		"a0" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a1" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a2" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a3" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a4" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a5" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a6" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a7" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a8" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
		"a9" => "qwertyuiopàsdfghjklñźxcvbnm, 1234567890'¡",
	);

	echo "TEST: $n small arrays\n";
	performance_json_unserialize($n, $data);


	// Big arrays
	$n = 1000;
	$data = array();
	for ($i = 0; $i<10; $i++) {
		$s = '';
		for ($j = 0; $j<100; $j++) {
			$s .= md5(microtime());
		}
		$data[] = $s;
	}

	echo "\n\nTEST: $n big arrays\n";
	performance_json_unserialize($n, $data);

	return true;
};


Test::addFunctions($tests);
