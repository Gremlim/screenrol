<?php
namespace controller\other;

//!This is the 404 controller.
class not_found extends \controller\unauthenticated_controller {

	public function 			__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);
	}

	public function 			execute() {

		return $this->reply_with_document_view(
			new \view\other\not_found(),
			array(),
			\app\response::status_code_404_not_found
		);
	}
}
