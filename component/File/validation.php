$file = null;
if (!array_key_exists('id', $token['data'])) {
	$file = FileInstance::INSERT();
	$token['data']['id'] = $file->getId();
}
