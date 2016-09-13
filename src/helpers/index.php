<?php

$helperFiles = scandir(__DIR__);

foreach ($helperFiles as $file) {
    // Require .php files only
    if (substr($file, -4, 4) == '.php') {
        require_once __DIR__.'/'.$file;
    }
}
