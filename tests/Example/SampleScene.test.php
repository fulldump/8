<?php

// Tests:

$tests = array();

$tests['Check one thing'] = function() {
	$pass = true;

	return $pass;
};

$tests['Check other thing'] = function() {
	$pass = true;

	return $pass;
};

Test::addFunctions($tests);
