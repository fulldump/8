<?php
	header('Content-Type: text/xml; charset=UTF-8');
	
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	
?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?php

	function type($node) {
		return $node->getProperty('type');
	}

	function path($node) {

	}

	function traverse($node) {
		draw($node);
		foreach ($node->children as $child) {
			traverse($child);
		}
	}

	function draw($node) {
		if (null === $node->parent) {
			return;
		}

		if (false === $node->getProperty('sitemap')) {
			return;
		}

		$url = "http://{$_SERVER['HTTP_HOST']}".Router::getNodeUrl($node);
		$date = date('Y-m-d', time());
		echo <<<heredoc
	<url>
		<loc>$url</loc>
		<lastmod>$date</lastmod>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>

heredoc;
	}

	Router::load();
	traverse(Router::$root);
?>
</urlset>