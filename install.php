<?php

// Parse params
parse_str(implode('&', array_slice($argv, 1)), $argv);

// Functions
function command($message) {
	echo "$message";
	$stdin = fopen ("php://stdin","r");
	$line = trim(fgets($stdin));
	fclose($stdin);
	return $line;
}

// Selection
if (isset($argv['--install'])) {	// Installs/updates the thing
	require('class/Main.class.php');
	Main::goCli();

	foreach(Storm::all() as $s){
		$s->install();
	}

} else if (isset($argv['--local'])) {	

	echo "\n# Configuring database\n";

	$host = command('Host: ');
	$db = command('Database: ');
	$user = command('User: ');
	$pass = command('Pass: ');

	require('class/Main.class.php');
	Main::goCli();
	Database::configure(
		$host,
		$db,
		$user,
		$pass
	);

	echo "\n# Installing TreeWeb...\n";
	foreach(Storm::all() as $s){
		$s->install();
	}

} else if (
		isset($argv['host']) &&
		isset($argv['db']) &&
		isset($argv['user']) &&
		isset($argv['pass'])
	) {

	require('class/Main.class.php');
	Main::goCli();

	Database::configure(
		$argv['host'],
		$argv['db'],
		$argv['user'],
		$argv['pass']
	);

} else if (isset($argv['h'])) {
	echo <<<FINDELACITA

TreeWeb deployer
================

Mode of use:
php install.php --install
php install.php host=localhost db=shop user=john pass=1234

	-h    Display this help

	host      Configure database host
	db        Configure database name
	user      Configure database user
	pass      Configure database pass

	--install Install or update

	-git      Returns post_receive hook for git


FINDELACITA;

} else {


	$cwd = getcwd();

	echo "\n\n
TreeWeb deployer - interactive mode
===================================
\n";

	echo "\n# Initializing git repo...\n";
	echo shell_exec("git init --bare .git");

	echo "\n# Adding git hook 'post-receive'...\n";
	$hook_file = $cwd.'/.git/hooks/post-receive';
	$hook_data = "#!/bin/sh
GIT_WORK_TREE=$cwd git checkout -f
cd $cwd
";
	file_put_contents($hook_file, $hook_data);
	chmod($hook_file, 0755);
	echo shell_exec("ls -la $hook_file");

	echo "\n# Upload your repo\n\n";

	echo "= Execute this commands in your local machine:================================\n\n";

	echo "git remote add REMOTE_NAME ssh://USER@SERVER$cwd/.git\n";
	echo "git push REMOTE_NAME YOUR_PREFERRED_BRANCH:master\n\n";

	command('When the repo will be uploaded, press ENTER:');

	echo "\n# Configuring database\n";

	$host = command('Host: ');
	$db = command('Database: ');
	$user = command('User: ');
	$pass = command('Pass: ');

	require('class/Main.class.php');
	Main::goCli();
	Database::configure(
		$host,
		$db,
		$user,
		$pass
	);

	echo "\n# Installing TreeWeb...\n";
	foreach(Storm::all() as $s){
		$s->install();
	}

	echo "\n# Adding post-receive line...\n";
	file_put_contents($hook_file, "php.ORIG.5_4 -n install.php --install\n", FILE_APPEND);

	echo "\n\n TreeWeb is now installed\n\n";

}
