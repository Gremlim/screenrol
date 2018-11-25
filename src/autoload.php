<?php
spl_autoload_register(function($_class) {
    	$path=__DIR__.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.strtolower(str_replace("\\", DIRECTORY_SEPARATOR, $_class)).'.php';

    	if(file_exists($path) && is_file($path)) {
    		require($path);
    	}
});

require("class/external/autoload.php");
