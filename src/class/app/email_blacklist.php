<?php
namespace app;

class email_blacklist_exception extends \Exception {
	public function __construct($_msg) {
		parent::__construct($_msg);
	}
};

//!This class encapsulates the functionality of the kayako rgpd blacklist:
//!a list of rules/emails that won't get the kayako treatment (that is, won't
//!have their tickets frozen and their accounts closed until the accept the
//!RGPD.

class email_blacklist extends \app\email_list{

	const					file_ends='_blacklist.txt';


	public function			__construct($_namefile) {
			parent::__construct($_namefile.self::file_ends);

			$this->check_or_create_file();
			parent::load_data();

	}
};
