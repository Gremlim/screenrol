<?php
namespace app;

class email_list_checker_result {

	const							type_skip=0;
	const							type_domain=1;
	const							type_email=2;

	public							$data;
	private							$type;

	public function 				is_skip() {return self::type_skip===$this->type;}
	public function 				is_domain() {return self::type_domain===$this->type;}
	public function					is_email() {return self::type_email===$this->type;}

	public static function			from_skip() {return new email_list_checker_result(self::type_skip);}
	public static function			from_domain($_domain) {return new email_list_checker_result(self::type_domain, $_domain);}
	public static function			from_email($_email) {return new email_list_checker_result(self::type_email, $_email);}

	private function				__construct($_t, $_d=null) {
		$this->type=$_t;
		$this->data=$_d;
	}
};

class email_list_checker_exception extends \Exception {
	public function __construct($_msg) {
		parent::__construct($_msg);
	}
};

class email_list_checker {

		public static function			process_line($_line) {

		if(!strlen($_line)) {
			return email_list_checker_result::from_skip();
		}

		if('#'==substr($_line, 0, 1)) { //A comment.
			return email_list_checker_result::from_skip();
		}

		if(1!==substr_count($_line, '@') ) { //Invalid data.
			throw new email_list_checker_exception("Invalid line found at ".$_line);
		}

		if('*@'==substr($_line, 0, 2)) { //Whitelisted domain.
			return email_list_checker_result::from_domain(substr($_line, 2));
		}

		if(false===filter_var($_line, FILTER_VALIDATE_EMAIL)) {
			throw new email_list_checker_exception("Invalid email found at ".$_line);
		}

		return email_list_checker_result::from_email($_line);
	}
};
