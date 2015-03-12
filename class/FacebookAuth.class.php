<?php

class FacebookAuth {

	private $config = array();

	public function __construct($app_id, $app_secret, $redirect_uri, $scopes, $state=null) {
		$this->config = array(
			'app_id' => $app_id,
			'app_secret' => $app_secret,
			'redirect_uri' => $redirect_uri,
			'scopes' => $scopes,
			'state' => $state,
		);
	}

	public function getAuthLink() {
		$url =
		'https://www.facebook.com/dialog/oauth?'.
		'&scope=email'.
		'&redirect_uri='.urlencode($this->config['redirect_uri']).
		'&app_id='.urlencode($this->config['app_id']);

		if (null != $this->config['state']) {
			$url .= "&state=".$this->config['state'];
		}

		return $url;
	}

	public function getUserInfo($code) {

		// Exchange token
		$result = Lib::doRequest(
			'GET',
			'https://graph.facebook.com/oauth/access_token?'.
			'client_id='.urlencode($this->config['app_id']).
			'&client_secret='.urlencode($this->config['app_secret']).
			'&redirect_uri='.urlencode($this->config['redirect_uri']).
			'&code='.urlencode($code),
			array(),
			array()
		);

		if (null == $result) {
			return null;
		}

		$tokens = array();
		parse_str($result, $tokens);

		// Get User Info
		$result = Lib::doRequest(
			'GET',
			'https://graph.facebook.com/me?'.
			'access_token='.urlencode($tokens['access_token']),
			array(),
			array()
		);

		if (null == $result) {
			return null;
		}

		$user_info = json_decode($result, true);

		if (!in_array(array('id', 'email', 'name'), $user_info)) {
			return null;
		}

		$user_info['picture'] = "http://graph.facebook.com/{$user_info['id']}/picture";

		return $user_info;

	}

}
