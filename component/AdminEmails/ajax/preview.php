<!DOCTYPE HTML>
<html lang="ES">
	<head>
		<meta http-equiv="Content-Type" CONTENT="text/html; charset=UTF-8">
		<title>Email preview</title>
		<meta name="keywords" content="">
		<meta name="description" content="">
	</head>
	<body>
<?php

$email_id = $_GET['email_id'];
$history_id = $_GET['history'];


$email = EmailerCurrent::ROW($email_id);
if (null != $email) {
	echo $email->getHTML();	
}


?>
	</body>
</html>