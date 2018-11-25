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

	//TODO: This must be extinguished...
	public static function		check_permissions($_section=null, $_profile=null) {

		//This too is a hack.
		if(null===$_section) {
			$_section=str_replace('/app', '', strtok($_SERVER["REQUEST_URI"],'?'));
		}

		return \app\path_authority::check_fallback($_section, $_profile);
	}

	public static function 		check_permissions_and_redirect($_section=null, $_profile=null) {

		if(!self::check_permissions($_section, $_profile)) {
			header('location: sin_permisos.php');
			die();
		}
	}

	public static function 		build_url($_file=null) {

		return $config=\app\config::get()->get_app_url().$_file;
	}

	public static function 		build_path($_file) {

		return $config=\app\config::get()->get_app_path().$_file;
	}
}
