<?php

define('HELPERS_DIR', __DIR__);

$helperFiles = scandir(HELPERS_DIR);

foreach ($helperFiles as $file) {
    // Require .php files only
    if (substr($file, -4, 4) == '.php') {
        require_once HELPERS_DIR.'/'.$file;
    }
}
