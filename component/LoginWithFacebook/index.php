<?php

$state = urlencode( Lib::getCurrentUrl() );

$auth = new FacebookAuth(
	Config::get('FACEBOOK_APP_ID'),
	Config::get('FACEBOOK_APP_SECRET'),
	Config::get('FACEBOOK_REDIRECT_URI'),
	Config::get('FACEBOOK_SCOPES'),
	$state
);

?>
<a href="<?php echo $auth->getAuthLink(); ?>" component="LoginWithFacebook">
	<span class="icon"></span>
	<span class="text">[[COMPONENT name=Label text='Login with Facebook' id=login-with-facebook]]</span>
</a>
