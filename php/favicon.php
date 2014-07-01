<?php

$path = 'resources/favicon.ico';

header('Content-Type: image/x-icon');
header("Content-Length: ". filesize($path));
readfile($path);
