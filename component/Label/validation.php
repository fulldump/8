
$label = null;
if (!array_key_exists('id', $token['data'])) {
	$label = Label::INSERT();
	$token['data']['id'] = $label->getId();
	if (array_key_exists('text', $token['data']) ) {
		$label->setText($token['data']['text']);
	}
} else if (is_numeric($token['data']['id'])) {
	$label = Label::ROW($token['data']['id']);
	if ($label === null) {
		$label = Label::INSERT();
		$token['data']['id'] = $label->getId();
		if (array_key_exists('text', $token['data']) ) {
			$label->setText($token['data']['text']);
		}
	}
}
