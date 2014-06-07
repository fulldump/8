
$notes = null;
if (array_key_exists('id', $token['data']))
	$notes = Notes::ROW($token['data']['id']);

if ($notes === null) {
	$notes = Notes::INSERT();
	$token['data']['id'] = $notes->getId();
}
