<?php
if(php_sapi_name()!='cli') {

	die('This tool is only accessible from the command line');
}

set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/../../'));
require_once('src/autoload.php');

if($argc!=3) {
	die('Use: php create_controller.php controller_name full::namespace

- controller_name : controller name, will be the filename without php.
- full::namespace : namespace separated by ::, will always start at \controller.
');
}

$controller_name=$argv[1];
$namespace=$argv[2];

$dir_path=str_replace('::', DIRECTORY_SEPARATOR, $namespace);

//Solve the file path...
$filename=\app\config::get()->get_app_path().
	'src'.DIRECTORY_SEPARATOR.
	'class'.DIRECTORY_SEPARATOR.
	'controller'.DIRECTORY_SEPARATOR.
	$dir_path.DIRECTORY_SEPARATOR.$controller_name.'.php';

if(file_exists($filename) && is_file($filename)) {
	die('Error: could not create the directory / the controller or its file already exists!'.PHP_EOL);
}

$template=str_replace(
	['##FULL_NAMESPACE##', '##CLASSNAME##'],
	[$namespace, $controller_name],
	file_get_contents('data/controller_template.tpl'));

file_put_contents($filename, $template);

// CREATE LANGUAGE FILE
$filename=\app\config::get()->get_app_path().
'src'.DIRECTORY_SEPARATOR.
'lang'.DIRECTORY_SEPARATOR.
'es'.DIRECTORY_SEPARATOR.$controller_name.'.json';

if(file_exists($filename) && is_file($filename)) {
	die('Error: could not create the pattern or its file already exists!'.PHP_EOL);
}

file_put_contents($filename, "{}");

// CREATE PATTERN FOR ROUTER
$filename=\app\config::get()->get_app_path().
	'src'.DIRECTORY_SEPARATOR.
	'conf'.DIRECTORY_SEPARATOR.
	'patterns'.DIRECTORY_SEPARATOR.$controller_name.'.json';

if(file_exists($filename) && is_file($filename)) {
	die('Error: could not create the pattern or its file already exists!'.PHP_EOL);
}
$template=str_replace(
	['##FULL_NAMESPACE##', '##CLASSNAME##'],
	[$namespace, $controller_name],
	file_get_contents('data/pattern.tpl'));
	
file_put_contents($filename, $template);
	



die('Done'.PHP_EOL);
