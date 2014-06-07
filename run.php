<?php



	require('class/Main.class.php');
	Main::goCli();


	Storm::get('SystemRoute')->export();
	Storm::get('SystemPage')->export();


