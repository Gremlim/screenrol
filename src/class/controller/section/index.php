<?php
namespace controller\section;

class index extends \controller\section {

	public function 			__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);
	}

	public function 			execute() {

		return $this->reply_with_view(
			new \view\section\index(),
			array(),
			\app\response::status_code_200_ok
		);
	}
}
