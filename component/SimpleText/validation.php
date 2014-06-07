
$st = null;
if (!array_key_exists('id', $token['data'])) {
	$st = SimpleText::INSERT();
	$token['data']['id'] = $st->getId();
	if (array_key_exists('text', $token['data']) ) {
		$st->setText($token['data']['text']);
	}
} else if (is_numeric($token['data']['id'])) {
	$st = SimpleText::ROW($token['data']['id']);
	if ($st === null) {
		$st = SimpleText::INSERT();
		$token['data']['id'] = $st->getId();
		if (array_key_exists('text', $token['data']) ) {
			$st->setText($token['data']['text']);
		}
	}
}

