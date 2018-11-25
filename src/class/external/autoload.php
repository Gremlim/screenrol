<?php

//This hook should catch classes here.
spl_autoload_register(function($_class) {
    	$path=__DIR__.DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR, $_class).'.php';

    	if(file_exists($path) && is_file($path)) {
    		require($path);
    	}
});

