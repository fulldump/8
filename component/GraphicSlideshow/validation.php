
$slideshow = null;
if (!array_key_exists('id', $token['data'])) {
	$slideshow = GraphicSlideshow::INSERT();
	$token['data']['id'] = $slideshow->getId();
} else if (is_numeric($token['data']['id'])) {
	$slideshow = GraphicSlideshow::ROW($token['data']['id']);
	if ($slideshow === null) {
		$slideshow = GraphicSlideshow::INSERT();
		$token['data']['id'] = $slideshow->getId();
	}
}
