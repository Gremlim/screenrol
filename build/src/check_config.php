<?php
//Include path is set at the root of the application.
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.".."));
require_once('src/autoload.php');

if($argc < 3 || $argc > 4) {
	if(is_verbose()) {
		echo 'Error: invalid argument count'.PHP_EOL;
	}

	exit(1);
}

function is_verbose($_argv=null) {

	static $verbose=false;

	if(null!==$_argv) {
		if(isset($_argv[3]) && $_argv[3]=='-v') {
			$verbose=true;
		}
	}

	return $verbose;
}

function get_file_as_json($_filename, $_path) {

	$fullpath=$_path.$_filename;
	return \tools\json::from_file($fullpath);
}

function traverse_json($_json, array &$result, $curpath='') {

	foreach($_json as $key => $val) {
		$result[]=$curpath.$key;
		if(is_object($val) || is_array($val)) {
			traverse_json($val, $result, $curpath.$key.'::');
		}
		else {
			//TODO: Could also check values???
		}
	}
}

function get_data($_filename) {

	$file_path=realpath(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."conf").DIRECTORY_SEPARATOR;
	$result=[];
	$json_data=get_file_as_json($_filename, $file_path);
	traverse_json($json_data, $result);
	return $result;
}

//Set verbosity status.
is_verbose($argv);

//Locate files...
$json_a_keys=get_data($argv[1]);
$json_b_keys=get_data($argv[2]);

$diff_a_to_b=array_diff($json_a_keys, $json_b_keys);
$diff_b_to_a=array_diff($json_b_keys, $json_a_keys);

if(count($diff_a_to_b) || count($diff_b_to_a)) {

	if(is_verbose()) {
		echo "Warning: differences between the dist and configuration file have been detected!".PHP_EOL;

		if(count($diff_a_to_b)) {
			echo "The following entries exist in $argv[1] and are missing in $argv[2]:".PHP_EOL;
			array_walk($diff_a_to_b, function($_item) {echo " - $_item".PHP_EOL;});
		}

		if(count($diff_b_to_a)) {
			echo "The following entries exist in $argv[2] and are missing in $argv[1]:".PHP_EOL;
			array_walk($diff_b_to_a, function($_item) {echo " - $_item".PHP_EOL;});
		}
	}
	exit(2);
}
else {

	if(is_verbose()) {
		echo 'Ok: Check successful'.PHP_EOL;
	}

	exit(0);
}
