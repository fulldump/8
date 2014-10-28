
if (!array_key_exists('id', $token['data'])) {
	$token['data']['id'] = md5(microtime());
}
