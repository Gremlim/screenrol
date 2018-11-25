<?php
namespace app;

class tools {

	public static function		controller_url($_type) {

		//TODO: Check type is not resolved!!
		return self::build_url('entrypoints/controller.php?type='.$_type);
	}

	const 						resolver_separator='::';
	public static function 		classname_resolver($_path) {

		//TODO: check if we begin with separator...
		return "\\".str_replace(self::resolver_separator, "\\", $_path);
	}

	//!Removes /app from any script path, so anything depending on it
	//!can be used in different installations.

	public static function		normalize_script_path($_path) {

			return substr($_path, strlen(\app\config::get()->get_app_url())-1);
	}

	public static function 		build_url($_file=null) {

		return $config=\app\config::get()->get_app_url().$_file;
	}

	public static function 		build_path($_file) {

		return $config=\app\config::get()->get_app_path().$_file;
	}
}
