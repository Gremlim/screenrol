<?php
namespace tools;

//See use example at the end of the file...

class curl_request_exception extends \Exception {

	public function __construct($_msg) {
		parent::__construct("curl_request error : ".$_msg);
	}
};

//!Shorthand for getting all request related things...
class curl_response {

	public	$status_line;
	public	$body;
	public	$status_code;
	public	$response_headers;

	public function __construct($_rsl, $_b, $_sc, $_rh) {
		$this->status_line=$_rsl;
		$this->body=$_b;
		$this->status_code=$_sc;
		$this->response_headers=$_rh;
	}

	public function as_raw_string() {

		$headers=array_reduce($this->response_headers, function($_carry, $_item) {
			return $_carry.=$_item->as_raw_string().PHP_EOL;
		}, '');

		return <<<R
{$this->status_line}
{$headers}
{$this->body}
R;
	}
};

//!Each of the possible headers.
class response_header {
	public $name;
	public $value;
	public function __construct($_n, $_v) {
		$this->name=$_n;
		$this->value=$_v;
	}

	public function as_raw_string() {

		return $this->name.':'.$this->value;
	}
}

class curl_request {

	const	method_post="POST";
	const	method_get="GET";
	const	method_head="HEAD";
	const	method_put="PUT";
	const	method_delete="DELETE";
	const	method_connect="CONNECT";
	const	method_options="OPTIONS";
	const	method_trace="TRACE";
	const	method_patch="PATCH";

	private $handle=null;
	private $method=null;
	private $response_status_line=null;
	private $response_body=null;
	private $response_headers_raw=null; //TODO: Maybe I don't need you.
	private $response_headers=[];
	private $response_status_code=null;
	private $headers_map=[];
	private $form_data_map=[];
	private $payload=null;
	private $sent=false;				//Making life easier....
	private $url=null;					//Making life easier again.

	public function __construct($_url=null) {
		if(!function_exists('curl_init')) {
			throw new curl_request_exception("The curl_request class needs a working curl implementation");
		}

		$this->handle=curl_init();
		if(!$this->handle) {
			throw new curl_request_exception("Could not init curl (".curl_strerror(curl_errno($ch)).")");
		}

		if(null!==$_url) {
			$this->set_url($_url);
		}

		//Some healthy defaults...
		$this->set_defaults();
	}

	public function __destruct() {
		if($this->handle) {
			curl_close($this->handle);
		}
	}

	public function get_response() {

		if(!$this->is_sent()) {
			throw new curl_request_exception('did not send the request');
		}

		return new curl_response(
			$this->get_response_status_line(),
			$this->get_body(),
			$this->get_status_code(),
			$this->get_response_headers()
		);
	}

	public function get_response_status_line() {return $this->response_status_line;}
	public function get_body() {return $this->response_body;}
	public function get_status_code() {return $this->response_status_code;}
	public function get_response_headers() {return $this->response_headers;}

	public function is_sent() {
		return $this->sent;
	}

	public function set_url($_url) {
		$this->url=$_url;
		curl_setopt($this->handle, CURLOPT_URL, $this->url);
		return $this;
	}

	public function get_url() {
		return $this->url;
	}

	public function set_method($_method) {

		if(!in_array($_method, $this->get_valid_methods())) {
			throw new curl_request_exception("Invalid method '".$_method."'");
		}
		$this->method=$_method;
		curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $this->method);
		return $this;
	}

	//!Methods are not validated against the regular http verbs.
	public function set_custom_method($_method) {

		$this->method=$_method;
		curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $this->method);
		return $this;
	}

	public function set_follow_location($_v) {
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, (bool)$_v);
		return $this;
	}

	public function	set_ssl_verify_peer($_val) {

		curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, $_val);
		return $this;
	}

	public function set_user_pwd($_user,$_pass){
		curl_setopt($this->handle, CURLOPT_USERPWD, $_user.':'.$_pass);
		return $this;
	}

	public function	set_ssl_verify_host($_val) {

		curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, $_val);
		return $this;
	}

	public function set_connect_timeout($_val) {
		curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, $_val);
		return $this;
	}

	public function set_execution_timeout($_val) {

		curl_setopt($this->handle, CURLOPT_TIMEOUT, $_val);
		return $this;
	}

	public function set_interface($_val) {
		curl_setopt($this->handle, CURLOPT_INTERFACE, $_val);
		return $this;
	}

	//!Must receive CURL_IPRESOLVE_V4, CURL_IPRESOLVE_V6 or CURL_IPRESOLVE_WHATEVER.
	public function set_ip_resolution($_val) {

		if(!defined('CURLOPT_IPRESOLVE')) {
			throw new curl_request_exception("curl_request::set_ip_resolution is not supported");
		}

		switch($_val) {
			case CURL_IPRESOLVE_WHATEVER:
			case CURL_IPRESOLVE_V4:
			case CURL_IPRESOLVE_V6:
				curl_setopt($this->handle, CURLOPT_IPRESOLVE, $_val);
				return $this;
			break;
		}

		throw new curl_request_exception("Invalid argument to curl_request::set_ip_resolution");
	}

	//!By default curl will try to reuse existing connections for the same hostnames.
	public function set_force_fresh_connection($_val) {

		curl_setopt($this->handle, CURLOPT_FRESH_CONNECT, $_val);
		return $this;
	}

	//!By default, curl sets a 120 seconds DNS cache for repeated hostnames.
	public function disable_dns_cache() {

		if(!defined('CURLOPT_DNS_CACHE_TIMEOUT')) {
			throw new curl_request_exception("curl_request::disable_dns_cache is not supported");
		}

		curl_setopt($this->handle, CURLOPT_DNS_CACHE_TIMEOUT, 0);
		return $this;
	}

	public function set_verbosity($_val) {

		curl_setopt($this->handle, CURLOPT_VERBOSE, $_val);
		return $this;
	}


	public function add_payload($_data) {

		//TODO: Check it is a string!!!
		if(!$this->supports_payload()) {
			throw new curl_request_exception("The method does not support a payload");
		}

		if(count($this->form_data_map)) {
			throw new curl_request_exception("Cannot set payload when add_form_data() has been called.");
		}

		$this->payload=$_data;
		return $this;
	}

	public function add_form_data($_n, $_v) {

		if(!$this->supports_payload()) {
			throw new curl_request_exception("The method does not support a payload");
		}

		if(strlen($this->payload)) {
			throw new curl_request_exception("Cannot add_form_data when set_payload() has been called.");
		}

		if(isset($this->form_data_map[$_n])) {
			throw new curl_request_exception("The payload key '".$_n."' already exists");
		}

		$this->form_data_map[$_n]=$_v;
		return $this;
	}

	public function add_header($_n, $_v) {

		if(isset($this->headers_map[$_n])) {
			throw new curl_request_exception("The header key '".$_n."' already exists");
		}

		$this->headers_map[$_n]=$_v;
		return $this;
	}

	public function reset() {
		curl_reset($this->handle);
		$this->set_defaults();
		return $this;
	}

	public function execute() {

		if(null!==$this->response_status_code) {
			throw new curl_request_exception("Cannot use the same curl_request twice without calling reset().");
		}

		if(count($this->headers_map)) {
			curl_setopt($this->handle, CURLOPT_HTTPHEADER, $this->compose_header_array());
		}

		if(count($this->form_data_map)) {
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, http_build_query($this->form_data_map));
		}
		else if(strlen($this->payload)) {
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->payload);
		}

		$response=curl_exec($this->handle);

		if(curl_errno($this->handle)) {
			$error=curl_error($this->handle);
			throw new curl_request_exception("Could not execute request [".curl_errno($this->handle).":".$error."]");
		}

		$header_length=curl_getinfo($this->handle, CURLINFO_HEADER_SIZE);

		$this->response_headers_raw=substr($response, 0, $header_length);
		$this->response_body=substr($response, $header_length);
		$this->response_status_code=curl_getinfo($this->handle, CURLINFO_HTTP_CODE);

		//Boom... explode the raw response by new line, remove the empty items.
		$header_array=array_filter(explode(PHP_EOL, $this->response_headers_raw), function($_item) {return strlen(trim($_item));});
		$this->response_status_line=array_shift($header_array);
		$this->response_headers=array_map(function($_item) {

			if(strpos($_item, ':')!==false) {
				$d=explode(':', $_item, 2);
				return new response_header(trim($d[0]), trim($d[1]));
			}
			else {
				return new response_header($_item, '');
			}
		}, $header_array);

		$this->sent=true;

		return $this;
	}

	////////////////////////////////////////////////////////////////////////

	private function supports_payload() {
		return $this->method!==self::method_get;
	}

	private function compose_header_array() {
		$result=[];
		foreach($this->headers_map as $k => $v) {
			//TODO: Escape or remove colons.
			$result[]=$k.':'.$v;
		}
		return $result;
	}

	private function set_defaults() {
		$this->set_method(self::method_get);
		$this->set_follow_location(true);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLOPT_HEADER, true);
		//TODO: Add a method for this.
		$this->response_status_line=null;
		$this->response_body=null;
		$this->response_status_code=null;
		$this->response_headers=[];
		$this->response_headers_raw=null;
		$this->headers_map=[];
		//Workaround for when the server returns "100 continue"...
		$this->add_header("Expect", "");
		$this->form_data_map=[];
		$this->payload=null;
		$this->sent=false;
		$this->url=null;
	}

	private function get_valid_methods() {
		return [self::method_post, 	self::method_get,
			self::method_head, 		self::method_put,
			self::method_delete,	self::method_connect,
			self::method_options,	self::method_trace,
			self::method_patch];
	}
}
