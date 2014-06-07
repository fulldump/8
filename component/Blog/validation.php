
$blog = null;
if (array_key_exists('id', $token['data']))
	$blog = Blog::ROW($token['data']['id']);

if ($blog === null) {
	$blog = Blog::INSERT();
	$token['data']['id'] = $blog->getId();
}
