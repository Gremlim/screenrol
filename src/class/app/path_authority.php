<?php
namespace app;

//!This class controls the access on the pair profile-section. All the
//!information is stored in a json file. Access is granted on a "per_url"
//!basis.
class path_authority {

	const 						file_path='src/conf/section_auth.json';
	private static 				$section_data=null;

	//!Checks the path agains the profile. Will return true when the profile
	//!is authorized, false if otherwise. On the first call will load the
	//!profile data from disk and subsequently may throw if the data is
	//!not present or malformed.
	//!WARNING: This method allows access by default when the path is not
	//!defined in the data file.
	public static function 		check($_controller, $_profile) {
		// die('aaaa');
		if(null===self::$section_data) {
			self::load_section_data();
		}

		$section=self::get_section_by_controller($_controller);
		if(null===$section) {
			//TODO: Issue warning.
			return true;
		}
		return $section->check_profile($_profile);
	}

	//!Checks agains the path instead of controller. Fallback for older sections.
	public static function 		check_fallback($_path, $_profile) {

		if(null===self::$section_data) {
			self::load_section_data();
		}

		$section=self::get_section_by_path($_path);
		if(null===$section) {
			//TODO: Issue warning.
			return true;
		}

		return $section->check_profile($_profile);
	}

	//!Returns a section given its controller.
	private static function		get_section_by_controller($_controller) {

		$result=array_filter(self::$section_data, function(path_authority_section $_item) use ($_controller) {

			return $_controller==$_item->controller;
		});

		return count($result) ? array_shift($result) : null;
	}

	//!Returns a section given its path.
	private static function		get_section_by_path($_path) {

		$result=array_filter(self::$section_data, function(path_authority_section $_item) use ($_path) {
			return $_path==$_item->path;
		});

		return count($result) ? array_shift($result) : null;
	}

	//!Loads the pairs of path-profile.
	private static function 	load_section_data() {

		try {
			$filename=\app\tools::build_path(self::file_path);
			$json_data=\tools\json::from_file($filename);

			if(!property_exists($json_data, 'sections')) {
				throw new \Exception("path authority json file is malformed");
			}

			self::$section_data=array();
			foreach($json_data->sections as $v) {
				self::$section_data[]=path_authority_section::from_json_node($v);
			}
		}
		catch(\Exception $e) {
			throw new Exception('Error loading path authority: '.$e->getMessage());
		}
	}
}

//!Represents a pair of path and profile.
class path_authority_section {

	public						$path;
	public						$controller;
	public						$profile;

	//!Checks the profile string agains section data. Automatically authorises
	//!DV and G profiles.
	public function				check_profile($_profile) {

		if($_profile==='DV' || $_profile==='G') {
			return true;
		}

		return false!==strpos($this->profile, $_profile);
	}

	//!Named constructor.
	public static function 		from_json_node($_node) {

		$result=new path_authority_section($_node->controller, $_node->path, $_node->profile);
		return $result;
	}

	private function			__construct($_c, $_p, $_pr) {

		$this->controller=$_c;
		$this->path=$_p;
		$this->profile=$_pr;
	}
}
