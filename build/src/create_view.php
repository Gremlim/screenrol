<?php
if(php_sapi_name()!='cli') {

	die('This tool is only accessible from the command line');
}

set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/../../'));
require_once('src/autoload.php');

if($argc!=3) {
	die('Use: php create_view.php view_name full::namespace

- view_name : view name, will be the filename without php.
- full::namespace : namespace separated by ::, will always start at \view.
');
}

$view_name=$argv[1];
$namespace=$argv[2];

$dir_path=str_replace('::', DIRECTORY_SEPARATOR, $namespace);

//Solve the file path...
$filename=\app\config::get()->get_app_path().
	'src'.DIRECTORY_SEPARATOR.
	'class'.DIRECTORY_SEPARATOR.
	'view'.DIRECTORY_SEPARATOR.
	$dir_path.DIRECTORY_SEPARATOR.$view_name.'.php';

if(file_exists($filename) && is_file($filename)) {
	die('Error: could not create the directory / the view or its file already exists!'.PHP_EOL);
}

$template=str_replace(
	['##FULL_NAMESPACE##', '##CLASSNAME##'],
	[$namespace, $view_name],
	file_get_contents('data/view_template.tpl'));

file_put_contents($filename, $template);

//CREATE JS SCRIPTS
$filename=\app\config::get()->get_app_path().
	'assets'.DIRECTORY_SEPARATOR.
	'js'.DIRECTORY_SEPARATOR.
	$dir_path.DIRECTORY_SEPARATOR.$view_name.'.js';

if(file_exists($filename) && is_file($filename)) {
	die('Error: could not create the js file or its file already exists!'.PHP_EOL);
}

$template=file_get_contents('data/javascript.tpl');

file_put_contents($filename, $template);
die('Done'.PHP_EOL);
