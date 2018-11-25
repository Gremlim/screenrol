<?php
namespace controller\other;

//!This is the 403 controller.
class forbidden extends \controller\section {

	public function 			__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);
	}

	public function 			execute() {

		return $this->reply_with_view(
			new \view\other\forbidden(),
			array(),
			\app\response::status_code_403_forbidden
		);
	}
}
