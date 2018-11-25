<?php
namespace app;

class api_result {

	const		_RES_FAIL=0;
	const		_RES_SUCCESS=1;
	const		_DEFAULT_MESSAGE_OK='Ok';
	const		_DEFAULT_MESSAGE_ERROR='Invalid method';

	private		$result;
	private		$message;
	private		$body;

	public static function	from_defaults() {
		return new api_result("{}", self::_DEFAULT_MESSAGE_ERROR, self::_RES_FAIL);
	}

	public static function	from_error($_err) {
		return new api_result("{}", $_err, self::_RES_FAIL);
	}

	public static function	from_data(array $_data, $_msg=self::_DEFAULT_MESSAGE_OK) {

		if(!is_array($_data)) {
			throw new \Exception("invalid input to api_result:".gettype($_data).' given');
		}

		$encoded=json_encode($_data);
		if(false===$encoded) {
			throw new \Exception("api_result could not encode input data to JSON : ".json_last_error_msg());
		}

		return new api_result($encoded, $_msg, self::_RES_SUCCESS);
	}

	public function		is_fail() {
		return self::_RES_FAIL===$this->result;
	}

	public function		is_success() {
		return self::_RES_SUCCESS===$this->result;
	}

	public function		get_message() {
		return $this->message;
	}

	public function		get_body() {
		return $this->body;
	}

	public function		get_json_body() {
		return json_decode($this->body);
	}

	public function 	show() {

		$this->output_headers();
		echo $this->get_result_string();
	}

	public function output_headers() {

		header('content-type: text/json; charset=utf-8');
	}

	public function	get_result_string() {

		return <<<R
{
	"result":{$this->result},
	"message":"{$this->message}",
	"body":{$this->body}
}
R;
	}

	private function __construct($_b, $_m, $_r) {

		$this->body=$_b;
		$this->message=$_m;
		$this->result=$_r;
	}
}
