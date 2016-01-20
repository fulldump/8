<?php

$redirect = function() {
	if (array_key_exists('state', $_GET)) {
		header('Location: '.$_GET['state']);
	} else {
		header('Location: /');
	}
};

$auth = new GoogleAuth(
	Config::get('GOOGLE_WA_CLIENT_ID'),
	Config::get('GOOGLE_WA_CLIENT_SECRET'),
	Config::get('GOOGLE_OAUTH_REDIRECT_URI'),
	Config::get('GOOGLE_OAUTH_SCOPES')
);

$info = $auth->getUserInfo($_GET['code']);

if (null === $info) {
	// Access denied
	
	$redirect();

} else {
	// Access granted

	$email = $info['email'];
	$nick = $info['name'];
	$picture = $info['picture'];

	$user = Users::getByEmail($email);

	if (null == $user) {
		$user = Users::add($email);
		$user->setNick($nick);
		$user->setPicture(Image::INSERT($picture));
	}

	$user->login();

	$redirect();	
}

?>