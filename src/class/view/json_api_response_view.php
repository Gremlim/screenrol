<?php
namespace view;

class json_api_response_view {

	private $result;

	public function __construct(\app\api_result $_res) {

		$this->result=$_res;
	}

	//TODO: This class will fall short, as it does not allow extra headers...
	public function create_view() {

		$this->result->output_headers();
		return $this->result->get_result_string();
	}
}
