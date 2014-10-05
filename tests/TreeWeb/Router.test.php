<?php

/**
 *  Root
 *		A
 *			B
 *				C
 *				D
 *			E
 *				F
 *				G
 */
function build_basic_hierarchy() {
	$root = new Node();

	$a = new Node();	$root->append('a', $a);
		$b = new Node();	$a->append('b', $b);
			$c = new Node();	$b->append('c', $c);
			$d = new Node();	$b->append('d', $d);
		$e = new Node();	$a->append('e', $e);
			$f = new Node();	$e->append('f', $f);
			$g = new Node();	$e->append('g', $g);

	return $root;
}

function get_default_language() {
	$default_language = Config::get('DEFAULT_LANGUAGE');
	return $default_language;
}

function get_not_default_language() {
	$default_language = get_default_language();
	$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));

	// Find other language different from the default one
	$other_language = '';
	foreach ($available_languages as $al) {
		if ($al != $default_language) {
			$other_language = $al;
		}
	}
	return $other_language;	
}

function get_not_existing_language() {
	$available_languages = explode(',', Config::get('AVAILABLE_LANGUAGES'));
	return implode('', $available_languages);
}

// Tests:

$tests = array();

$tests['Get default language'] = function() {
	$pass = true;

	// Prepare
	Router::$root = build_basic_hierarchy();
	$default_language = get_default_language();

	// Run
	$router = new Router("/$default_language/example/path");

	// Check
	if ($default_language != $router->language) {
		$pass = false;
		echo "Default language does not match\n";
	}

	if ($router->parts[0] != $default_language) {
		$pass = false;
		echo "Url must not be consumed\n";
	}

	// Print
	print_r($router);

	return $pass;
};

$tests['Get not default language'] = function() {
	$pass = true;

	// Prepare
	Router::$root = build_basic_hierarchy();
	$not_default_language = get_not_default_language();

	// Run
	$router = new Router("/$not_default_language/example/path");

	// Check
	if ($not_default_language != $router->language) {
		$pass = false;
		echo "Language does not match\n";
	}

	if ($router->parts[0] != 'example') {
		$pass = false;
		echo "Url must be consumed\n";
	}

	// Print
	print_r($router);

	return $pass;
};

$tests['Get not existing language'] = function() {
	$pass = true;

	// Prepare
	Router::$root = build_basic_hierarchy();
	$default_language = get_default_language();
	$not_existing_language = get_not_existing_language();

	// Run
	$router = new Router("/$not_existing_language/example/path");

	// Check
	if ($default_language != $router->language) {
		$pass = false;
		echo "Language does not match\n";
	}

	if ($router->parts[0] != $not_existing_language) {
		$pass = false;
		echo "Url must NOT be consumed\n";
	}

	// Print
	print_r($router);

	return $pass;
};

$tests['Get existing node'] = function() {
	$pass = true;

	// Prepare
	Router::$root = build_basic_hierarchy();

	// Run
	$router = new Router('/a/b/c');

	// Check
	if (0 != count($router->parts)) {
		$pass = false;
		echo "All parts must be consumed\n";
	}

	if ($router->node->id != Router::$root->get('a/b/c')->id) {
		$pass = false;
		echo "Returned node is not the correct one\n";
	}

	// Print
	$router->node->print_r();
	print_r($router);

	return $pass;
};

$tests['Combined test - get language and node'] = function() {
	$pass = true;

	// Prepare
	Router::$root = build_basic_hierarchy();
	$not_default_language = get_not_default_language();

	// Run
	$router = new Router("/$not_default_language/a/b");

	// Check
	if (0 != count($router->parts)) {
		$pass = false;
		echo "All parts must be consumed\n";
	}

	if ($router->node->id != Router::$root->get('a/b')->id) {
		$pass = false;
		echo "Returned node is not the correct one\n";
	}

	if ($not_default_language != $router->language) {
		$pass = false;
		echo "Language does not match\n";
	}


	// Print
	print_r($router);

	return $pass;
};

$tests['Get parametrized'] = function() {
	$pass = true;

	// Prepare
	Router::$root = $root = build_basic_hierarchy();
	$e = $root->get('a/e');
	$e->insertBefore('{parameter}', $e);

	// Run
	$router = new Router("/a/my-parameter/f");

	// Check
	if (0 != count($router->parts)) {
		$pass = false;
		echo "All parts must be consumed\n";
	}

	if (1 != count($router->parameters)) {
		$pass = false;
		echo "Must be 1 parameter\n";
	}

	if ($router->parameters['{parameter}'] !== 'my-parameter') {
		$pass = false;
		echo "parameters['{parameter}'] MUST BE 'my-parameter'\n";
	}


	// Print
	$root->print_r();
	print_r($router);

	return $pass;
};

Test::addFunctions($tests);
