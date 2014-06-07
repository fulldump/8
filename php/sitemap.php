<?php
    header('Content-Type: text/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?php



	$root_children = SystemRoute::getRoot()->getChildren();
	foreach ($root_children as $rc)
		sitemap($rc);



	function sitemap($node) {
		if ($node->getController() == 'page' && $node->getTitle() != 'admin' && $node->getTitle() != 'adminx'  && $node->getTitle() != 'profile'  && $node->getTitle() != 'Error-404') {
			$url = ControllerAbstract::$scheme.'://'.$_SERVER['SERVER_NAME'].$node->getPath();
			
?>    <url>
        <loc><?=$url?></loc>
        <lastmod><?=date('Y-m-d', time())?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
<?php
			$children = $node->getChildren();
			foreach ($children as $c)
				sitemap($c);
		}
	}

?>
</urlset>