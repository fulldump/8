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
function buildBasicHierarchy() {
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

function checkLinked($a, $b) {
	$key = md5(microtime());
	$value = 'CHECK_LINKED';
	$a->properties[$key] = $value;
	return $b->properties[$key] == $value;
}

function checkFatherhood($node) {
	foreach ($node->children as $C=>$child) {
		if ($child->parent->id != $node->id || !checkFatherhood($child)) {
			return false;
		}
	}
	return true;
}

function checkHasParent(&$pass, $expected, $a, $b, $name_a='a', $name_b='b') {
	if ($expected === $a->hasParent($b)) {
		$result = 'OK';
	} else {
		$pass = false;
		$result = 'ERROR';
	}

	if ($expected === true) {
		echo "$name_a has parent $name_b\t\t$result\n";
	} else {
		echo "$name_a has NOT parent $name_b\t$result\n";
	}
}

// Tests:

$tests = array();

$tests['Remove root node'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();

	// Run
	$result = $root->remove();

	// Check
	if ($result !== false) {
		$pass = false;
		echo "Result must be 'false'\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Remove existing node'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$b = $root->get('a/b');

	// Run
	$result = $b->remove();

	// Check
	if ($result !== true) {
		$pass = false;
		echo "Remove must return 'true' if success\n";
	}

	if ($b->parent != null) {
		$pass = false;
		echo "Removed-node's parent must be null\n";
	}

	if (!checkFatherhood($b)) {
		$pass = false;
		echo "Removed-node's fatherhood fails\n";
	}

	if (!checkFatherhood($root)) {
		$pass = false;
		echo "Root-node's fatherhood fails\n";
	}

	// Print
	$b->print_r();
	$root->print_r();

	return $pass;
};

$tests['Check basic-hierarchy fatherhood '] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();

	// Run & check
	if (!checkFatherhood($root)) {
		$pass = false;
		echo "BasicHierarachy has incoherences\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Get node'] = function() {
	$pass = true;

	// Prepare environment
	$root = buildBasicHierarchy();

	// Run test
	$e = $root->get('a/e');
	
	// Check
	if ($e->id != $root->children['a']->children['e']->id) {
		$pass = false;
		echo "The node is not the same\n";
	}

	if (!checkLinked($e, $root->children['a']->children['e'])) {
		$pass = false;
		echo "Nodes not linked\n";
	}

	// Print result
	$e->print_r();
	$root->print_r();

	return $pass;
};

$tests['Append new node'] = function() {
	$pass = true;

	// Prepare environment
	$root = new Node();
	$page1 = new Node();

	// Run test
	$root->append('page1', $page1);

	// Check
	$pass = $root->children['page1'] == $page1;

	// Print result
	$root->print_r();

	return $pass;
};

$tests['Append new deep node'] = function() {
	$pass = true;

	// Prepare environment
	$root = new Node();

	$page1 = new Node();
	$root->append('page1', $page1);

	$page2 = new Node();
	$page1->append('page2', $page2);

	// Run test
	$pageN = new Node();
	$root->append('page1/page2/pageN', $pageN);

	// Print
	$root->print_r();


	return $pass;
};

$tests['Append cyclic node'] = function() {
	$pass = true;

	// Prepare environment
	$root = buildBasicHierarchy();
	$b = $root->get('a/b');

	// Run
	$result = $b->append('root', $root);

	// Check
	if (false !== $result) {
		$pass = false;
		echo "append() must return false, because the node to insert is a parent\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Append existing node'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$c = $root->get('a/b/c');
	$e = $root->get('a/e');

	// Run
	$c->append('e', $e);

	// Check
	if (!checkLinked($e, $c->get('e'))) {
		$pass = false;
		echo "Error inserting node 'e' below 'b'\n";
	}

	if (!checkLinked($e->parent, $c)) {
		$pass = false;
		echo "Parent of node 'e' MUST be 'c'\n";
	}

	if (null !== $root->get('a/e')) {
		$pass = false;
		echo "Node 'e' was NOT removed from 'a'\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Has parent'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$a = $root->get('a');
	$b = $root->get('a/b');
	$c = $root->get('a/b/c');
	$e = $root->get('a/e');


	// Check

	// Must return true
	checkHasParent($pass, true, $root, $root, '?', '?');

	checkHasParent($pass, true, $a, $a, 'a', 'a');
	checkHasParent($pass, true, $a, $root, 'a', '?');

	checkHasParent($pass, true, $b, $b, 'b', 'b');
	checkHasParent($pass, true, $b, $a, 'b', 'a');
	checkHasParent($pass, true, $b, $root, 'b', '?');

	checkHasParent($pass, true, $c, $c, 'c', 'c');
	checkHasParent($pass, true, $c, $b, 'c', 'b');
	checkHasParent($pass, true, $c, $a, 'c', 'a');
	checkHasParent($pass, true, $c, $root, 'c', '?');

	checkHasParent($pass, true, $e, $e, 'e', 'e');
	checkHasParent($pass, true, $e, $a, 'e', 'a');
	checkHasParent($pass, true, $e, $root, 'e', '?');

	// Must return false

	checkHasParent($pass, false, $root, $a, '?', 'a');
	checkHasParent($pass, false, $root, $b, '?', 'b');
	checkHasParent($pass, false, $root, $c, '?', 'c');
	checkHasParent($pass, false, $root, $e, '?', 'e');

	checkHasParent($pass, false, $a, $b, 'a', 'b');
	checkHasParent($pass, false, $a, $c, 'a', 'c');
	checkHasParent($pass, false, $a, $e, 'a', 'e');

	checkHasParent($pass, false, $b, $c, 'b', 'c');
	checkHasParent($pass, false, $b, $e, 'b', 'e');

	checkHasParent($pass, false, $c, $e, 'c', 'e');

	checkHasParent($pass, false, $e, $b, 'e', 'b');
	checkHasParent($pass, false, $e, $c, 'e', 'c');

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert before null node'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();

	// Run
	$result = $root->insertBefore('my-key', null);

	// Check
	if (false !== $result) {
		$pass = false;
		echo "result must be false\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert before null parent node'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$z = new Node();

	// Run
	$result = $root->insertBefore('my-key', $z);

	// Check
	if (false !== $result) {
		$pass = false;
		echo "result must be false -> z does not have brother\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert before existing key'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$a = $root->get('a');
	$z = new Node();

	// Run
	$result = $a->insertBefore('a', $a);

	// Check
	if (false !== $result) {
		$pass = false;
		echo "result must be false -> key already exists\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert before OK'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$a = $root->get('a');
	$z = new Node();

	// Run
	$result = $z->insertBefore('z', $a);

	// Check
	if ($z->parent->id != $a->parent->id) {
		$pass = false;
		echo "Parent is not correct\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert before the same'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$a = $root->get('a');

	// Run
	$result = $a->insertBefore('a2', $a);

	// Check
	if ($a->parent->id != $root->id) {
		$pass = false;
		echo "Parent is not correct\n";
	}

	// Check
	if ($root->get('a') !== null) {
		$pass = false;
		echo "Key 'a' must NOT exist\n";
	}

	if ($root->get('a2') === null) {
		$pass = false;
		echo "Key 'a2' must exist\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert e before d'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$e = $root->get('a/e');
	$d = $root->get('a/b/d');

	// Run
	$e->insertBefore('e', $d);

	// Check
	if (!checkLinked($e->parent, $d->parent)) {
		$pass = false;
		echo "The parent of 'e' is not correct\n";
	}

	if (!checkLinked($e, $root->get('a/b/e'))) {
		$pass = false;
		echo "'e' has not been inserted correctly\n";
	}

	if (null !== $root->get('a/e')) {
		$pass = false;
		echo "'e' has not been removed from origin\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['Insert e after d'] = function() {
	$pass = true;

	// Prepare
	$root = buildBasicHierarchy();
	$e = $root->get('a/e');
	$d = $root->get('a/b/d');

	// Run
	$e->insertAfter('e', $d);

	// Check
	if (!checkLinked($e->parent, $d->parent)) {
		$pass = false;
		echo "The parent of 'e' is not correct\n";
	}

	if (!checkLinked($e, $root->get('a/b/e'))) {
		$pass = false;
		echo "'e' has not been inserted correctly\n";
	}

	if (null !== $root->get('a/e')) {
		$pass = false;
		echo "'e' has not been removed from origin\n";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['fromArray toArray'] = function() {
	$pass = true;

	// Prepare
	$json = '{"id":"c170ad596fb6f987f0b34bf58099ca63","properties":[],"children":{"a":{"id":"2d63f222710222663373b0692b273103","properties":[],"children":{"b":{"id":"dc3a2e4652f068bad6f78e71920483f8","properties":[],"children":{"c":{"id":"63f5f8032383f518381541e2053b9213","properties":[],"children":[]},"d":{"id":"1f3b9cf313b126f9ed03739730d58eed","properties":[],"children":[]}}},"e":{"id":"5049e43d9fd9576aa0e715d9bd8dea20","properties":[],"children":{"f":{"id":"f8a45a93ed68521a7d4c23b7918b13b3","properties":[],"children":[]},"g":{"id":"a95c67d9ebdbc4f3fb175242fb1307eb","properties":[],"children":[]}}}}}}}';
	$array = json_decode($json, true);

	// Run
	$root = new Node();
	$root->fromArray($array);
	$result = $root->toArray();

	$result_json = json_encode($result);

	// Check
	if ($json !== $result_json) {
		$pass = false;
		echo "Serialization fail\n";
	}

	// Print
	echo "$json\n$result_json";

	return $pass;
};

$tests['get property'] = function() {
	$pass = true;

	// Prepare
	$hash_red = md5(microtime());
	$hash_blue = md5(microtime());
	$hash_pink = md5(microtime());

	$root = buildBasicHierarchy();
	$a = $root->get('a');
	$b = $root->get('a/b');
	$c = $root->get('a/b/c');
	$d = $root->get('a/b/d');
	$e = $root->get('a/e');
	$f = $root->get('a/e/f');
	$g = $root->get('a/e/g');

	$root->properties['red'] = $hash_red;
	$b->properties['blue'] = $hash_red;
	$e->properties['red'] = $hash_pink;

	// Run & check
	if (false
		|| $root->getProperty('red') !== $hash_red
		|| $a->getProperty('red') !== $hash_red
		|| $b->getProperty('red') !== $hash_red
		|| $c->getProperty('red') !== $hash_red
		|| $d->getProperty('red') !== $hash_red
	) {
		$pass = false;
		echo "red must be hash_red in nodes root, a, b, c, d";
	}

	if (false
		|| $e->getProperty('red') !== $hash_pink
		|| $f->getProperty('red') !== $hash_pink
		|| $g->getProperty('red') !== $hash_pink
	) {
		$pass = false;
		echo "red must be hash_pink in nodes e, f, g";
	}

	if (false
		|| $root->getProperty('blue') !== null
		|| $a->getProperty('blue') !== null
		|| $e->getProperty('blue') !== null
		|| $f->getProperty('blue') !== null
		|| $g->getProperty('blue') !== null
	) {
		$pass = false;
		echo "blue must be null in nodes root, a, e, f, g";
	}

	if (false
		|| $a->getProperty('blue') !== null
		|| $e->getProperty('blue') !== null
		|| $f->getProperty('blue') !== null
		|| $g->getProperty('blue') !== null
	) {
		$pass = false;
		echo "blue must be hash_blue in nodes b,c,d";
	}

	// Print
	$root->print_r();

	return $pass;
};

$tests['get inherited properties'] = function() {
	$pass = true;

	// Prepare
	$hash_red = md5(microtime());
	$hash_green = md5(microtime());
	$hash_blue = md5(microtime());
	$hash_black = md5(microtime());

	$root = buildBasicHierarchy();
	$a = $root->get('a');
	$b = $root->get('a/b');
	$c = $root->get('a/b/c');

	$root->properties['red'] = $hash_red;
	$a->properties['green'] = $hash_green;
	$b->properties['blue'] = $hash_blue;
	$c->properties['red'] = $hash_black;

	// Run
	$result = $c->getInheritedProperties();

	// Check
	if (array_key_exists('red', $result)) {
		$pass = false;
		echo "'red' is not an inherited property because has been redefined\n";
	}

	if ($result['blue'] !== $hash_blue) {
		$pass = false;
		echo "'blue' must be an inherited property\n";
	}

	if ($result['green'] !== $hash_green) {
		$pass = false;
		echo "'green' must be an inherited property\n";
	}

	// Print
	print_r($result);
	$root->print_r();

	return $pass;
};

Test::addFunctions($tests);
