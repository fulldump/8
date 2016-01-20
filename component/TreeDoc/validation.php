
$doc = null;
if (!array_key_exists('id', $token['data'])) {
	$doc = Document::INSERT();
	$token['data']['id'] = $doc->getId();
}
