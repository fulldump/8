
$image = null;
if (!array_key_exists('id', $token['data'])) {
	$image = ImageInstance::INSERT();
	$token['data']['id'] = $image->getId();
}