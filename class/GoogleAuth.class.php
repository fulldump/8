<?php

class GoogleAuth {

	private $config = array();

	public function __construct($client_id, $client_secret, $redirect_uri, $scopes, $state=null) {
		$this->config = array(
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'redirect_uri' => $redirect_uri,
			'scopes' => $scopes,
			'state' => $state,
		);
	}

	public function getAuthLink() {
		$url =
		"https://accounts.google.com/o/oauth2/auth?".
		"redirect_uri=".urlencode($this->config['redirect_uri']).
		"&response_type=code".
		"&client_id=".urlencode($this->config['client_id']).
		"&scope=".urlencode($this->config['scopes']).
		"&approval_prompt=force".
		"&access_type=offline";

		if (null != $this->config['state']) {
			$url .= "&state=".$this->config['state'];
		}

		return $url;
	}

	public function getUserInfo($code) {

		// Exchange token
		$result = Lib::doPostForm(
			'https://accounts.google.com/o/oauth2/token',
			array(),
			array(
				'code' => $_GET['code'],
				'redirect_uri' => $this->config['redirect_uri'],
				'client_id' => $this->config['client_id'],
				'scope' => $scopes,
				'client_secret' => $this->config['client_secret'],
				'grant_type' => 'authorization_code',
			)
		);

		if (null == $result) {
			return null;
		}

		$tokens = json_decode($result, true);


		// Get User Info
		$result = Lib::doRequest(
			'GET',
			'https://www.googleapis.com/oauth2/v2/userinfo',
			array(
				'Authorization: '.$tokens['token_type'].' '.$tokens['access_token'],
			),
			array()
		);

		if (null == $result) {
			return null;
		}

		return json_decode($result, true);

	}

}
