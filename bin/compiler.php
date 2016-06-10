#!/usr/bin/env php
<?php

define('HELPERS_DIR', __DIR__.'/../src/helpers');
define('COMPILED_HELPER_FILEPATH', __DIR__.'/../src/helpers.php');

$helperFiles = scandir(HELPERS_DIR);

$compiledContents = 
 '<?php'.PHP_EOL.PHP_EOL.
 '/*'.PHP_EOL.
 ' * COMPILED HELPERS'.PHP_EOL.
 ' */';

foreach ($helperFiles as $file) {
    // Hanlde .php files only
    if (substr($file, -4, 4) == '.php') {
        $contents = file_get_contents(HELPERS_DIR.'/'.$file);
        $contents = str_replace('<?php', '', $contents);

        $compiledContents .= $contents;
    }
}

file_put_contents(COMPILED_HELPER_FILEPATH, $compiledContents);
