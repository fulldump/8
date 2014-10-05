<?php
    header('Content-Type: text/xml; charset=UTF-8');
	/*
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    */
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
		if ('page' != type($node)) {
			return;
		}


		$url = "http://{$_SERVER['HTTP_HOST']}/{$node->id}";
?><url>
        <loc><?=$url?></loc>
        <lastmod><?=date('Y-m-d', time())?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
<?
	}

	Router::load();
	traverse(Router::$root);


?>
</urlset>