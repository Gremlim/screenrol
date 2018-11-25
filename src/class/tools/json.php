<?php
namespace tools;

class json {

	//!Loads the json file in $_path and returns it as an object (or array
	//!is $_as_array is true. The rest of the parameters pertain to
	//!http://php.net/manual/es/function.json-decode.php
	//!Will throw \tools\json_exception if the file does not exist or
	//!the json is malformed.
	public static function		from_file($_path, $_as_array=false, $_depth=512, $_options=0) {

		if(!file_exists($_path) || !is_file($_path)) {
			throw json_exception::from_does_not_exist($_path);
		}

		//el parametro option se metio en php5.4 , comparamos versiones para hacerlo retrocompatible		
		if (version_compare(phpversion(), "5.4.0", ">=")) {
  			$json_data=json_decode(file_get_contents($_path), $_as_array, $_depth, $_options);
		}else{
			$json_data=json_decode(file_get_contents($_path), $_as_array, $_depth);
		}

		if(null===$json_data) {
			throw json_exception::from_invalid_syntax($_path);
		}

		return $json_data;
	}
};

class json_exception extends \Exception{

	const 				file_does_not_exist=0;
	const				invalid_syntax=1;

	public static function		from_does_not_exist($_filename) {
		return new json_exception("$_filename does not exist", self::file_does_not_exist);
	}

	public static function		from_invalid_syntax($_filename) {

		$error=json_last_error();
		$msg=json_last_error_msg();

		return new json_exception("$_filename contains invalid json syntax ([$error] : $msg)", self::invalid_syntax);
	}

	//Should be private, but PHP is stupid.
	public function				__construct($_msg, $_code) {
		parent::__construct($_msg, $_code);
	}
};
