<?php
namespace app;

class app_error_reporter implements \tools\error_reporter\error_reporter {

	private					$view;

	public function 		__construct(\app\dependency_injector $_di) {

		//TODO: Sessions are hacks...
		$this->view=new \view\error\error($_di->get_request(), \app\tools::build_url());
	}

	public function			report(\tools\error_reporter\error $_err) {

		$this->view->inject_error($_err);
		$body=$this->view->create_view();

		$headers=[];
		$status_code=\app\response::status_code_500_internal_server_error;

		$response=new \app\response($body, $headers, $status_code);
		die($response->output());
	}
}
