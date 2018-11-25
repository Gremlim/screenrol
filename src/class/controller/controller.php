<?php
namespace controller;

abstract class controller {

	private $di;

	public function				__construct(\app\dependency_injector $_di) {

		$this->di=$_di;
	}

	public function				requires_authenticated_user() {

		return true;
	}

	protected function			get_di() {

		return $this->di;
	}

	protected function			reply_with_redirection($_url, $_status_code=\app\response::status_code_303_see_other, array $_other_headers=[]) {

		$headers=array_merge(array('Location' => $_url), $_other_headers);
		return new \app\response(null, $headers, $_status_code);
	}

	protected function			reply_with_api_result(\app\api_result $_res, $_headers, $_status_code) {

		$headers=array_merge(['Content-Type' => 'text/json'], $_headers);
		return new \app\response($_res->get_result_string(), $headers, $_status_code);
	}

	protected function			reply_with_file_download($_path, $_filename) {

		if(!file_exists($_path) || !is_file($_path)) {
			return new \app\response("File ".$_path." not found", [], \app\response::status_code_404_not_found);
		}

		$headers=['Content-Description' => 'File Transfer',
			'Content-type' => 'application/force-download',
			'Content-Disposition' => 'attachment; filename='.$_filename,
			'Content-Length' => filesize($_path)];

		return new \app\response(file_get_contents($_path), $headers, \app\response::status_code_200_ok);
	}

	protected function				reply_as_file_download($_contents, $_filename) {

		$headers=['Content-Description' => 'File Transfer',
			'Content-type' => 'application/force-download',
			'Content-Disposition' => 'attachment; filename='.$_filename,
			'Content-Length' => strlen($_contents)];

		return new \app\response($_contents, $headers, \app\response::status_code_200_ok);
	}

	protected function			reply_with_raw_text($_text, $_headers, $_status_code) {
		return new \app\response($_text, $_headers, $_status_code);
	}

	//!This replies with a full webpage view.
	protected function 			reply_with_view(\view\view $_view, array $_headers, $_status_code) {

		//TODO: H-h-h-ack.
		$head=new \view\templates\header($this->get_di());
		$head->set_section_js($_view->get_js_array());
		$head->set_section_css($_view->get_css_array());
		$footer=new \view\templates\footer;

		$head_view=$head->create_view();
		$myview=$_view->create_view();
		$footer_view=$footer->create_view();

		return new \app\response($head_view.$myview.$footer_view, $_headers, $_status_code);
	}

	//!This replies with whatever view was done, with no header or footer.
	protected function 			reply_with_template_view(\view\view $_view, array $_headers, $_status_code) {

		return new \app\response($_view->create_view(), $_headers, $_status_code);
	}

	protected function			reply_with_modal(\view\view $_view,array $_headers,$_status_code){

		$myview=$_view->create_view();
		$head=new \view\templates\modal_header($_view->get_js_array(),$_view->get_css_array());
		$head_view=$head->create_view();
		return new \app\response($head_view.$myview, $_headers, $_status_code);
	}

	protected function			reply_with_document_view(\view\view $_view, array $_headers, $_status_code) {

		$container=new \view\templates\minimal_document($_view);
		return new \app\response($container->create_view(), $_headers, $_status_code);
	}
}
