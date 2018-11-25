<?php
namespace app;

class email_list_exception extends \Exception {
	public function __construct($_msg) {
		parent::__construct($_msg);
	}
};

//!This class encapsulates the functionality of the kayako rgpd whitelist:
//!a list of rules/emails that won't get the kayako treatment (that is, won't
//!have their tickets frozen and their accounts closed until the accept the
//!RGPD.

abstract class email_list {

	const					file_path="src/conf/";
	const					default_contents="#Por dominio: *@domain.name. Por email: email@domain.com. Comentarios con #, lineas en blanco son válidas, cualquier otra cosa no";

	private					$filename;

	protected				$domains=[];
	protected				$emails=[];

	public function			__construct($_fn) {

		$this->filename=self::get_file_path().$_fn;
	}

	//!Checks if the config file exists, creates it if not. Static, because
	//!just trying to instance this class will try to load its contents.
	public function	check_or_create_file() {

		if(!file_exists($this->filename)) {
			touch($this->filename);
			chmod($this->filename, 0775);
			$this->save_file_contents(self::default_contents);
		}
	}


	//!Saves the full contents into the file, removing all existing data.
	//!Static because just creating an instance will try to load the
	//!file contents. Returns the number of bytes written.
	public function	save_file_contents($_data) {

		return file_put_contents($this->filename, $_data);
	}

	//!Returns the full contents of the file, if exists. Null if there is
	//!no file.
	public function	get_file_contents() {

		if(!file_exists($this->filename)) {
			return null;
		}

		return file_get_contents($this->filename);
	}

	public function	get_file_path() {

		$filename=\app\config::get()->get_app_path().self::file_path;
		return $filename;
	}

	//!Returns true is the email is whitelisted. First checks for a whitelisted
	//!domain, next for the email itself... Assumes the loaded data is correct
	//!and is indeed an email.
	public function			is_listed($_data) {

		$data=trim($_data);
		if($this->is_listed_domain($data)) {
			return true;
		}

		return $this->is_listed_email($data);
	}
	//!Checks if the email belongs to a whitelisted domain. Assumes input is
	//!already trimmed.
	private function		is_listed_domain($_email) {

		$pos=strpos($_email, '@');
		//Check the @ exists and is not in the last position...
		if(false===$pos || strlen($_email)-1===$pos) {
			return false;
		}

		return in_array(substr($_email, $pos+1), $this->domains);
	}

	//!Checks if the email is whitelisted. Assumes input is already trimmed.
	private function		is_listed_email($_email) {
		return in_array($_email, $this->emails);
	}
	//!Loads the data. Will fail with an exception if invalid data is present.
	//!Loads the data. Will fail with an exception if invalid data is present.
	protected function		load_data() {

		if(!file_exists($this->filename)) {
			throw new email_whitelist_exception("RGPD whitelist configuration file not found in ".$this->filename);
		}

		$file=new \SplFileObject($this->filename);
		$checker=new email_list_checker;

		while (!$file->eof()) {

			$data=$checker->process_line(trim($file->fgets()));

			if($data->is_skip()) {continue;}
			else if($data->is_domain()) {$this->domains[]=$data->data;}
			else if($data->is_email()) {$this->emails[]=$data->data;}
		}
	}


};
