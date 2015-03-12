<?php

$state = urlencode( Lib::getCurrentUrl() );

$auth = new GoogleAuth(
	Config::get('GOOGLE_WA_CLIENT_ID'),
	Config::get('GOOGLE_WA_CLIENT_SECRET'),
	Config::get('GOOGLE_OAUTH_REDIRECT_URI'),
	Config::get('GOOGLE_OAUTH_SCOPES'),
	$state
);

?>
<a href="<?php echo $auth->getAuthLink(); ?>" component="LoginWithGoogle">
	<span class="icon"></span>
	<span class="text">[[COMPONENT name=Label text='Login with Google' id=login-with-google]]</span>
</a>