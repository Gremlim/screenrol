<?php
namespace app;

class response {

	private $status_code;
	private $body;
	private $headers;

	public function __construct($_b, $_h, $_sc) {

		$this->body=$_b;
		$this->headers=$_h;
		$this->status_code=$_sc;
	}

	public function output() {

		header('HTTP/1.1 '.$this->status_code.' '.self::translate_status_code($this->status_code));
		foreach($this->headers as $k => $v) {
			header($k.':'.$v);
		}

		echo $this->body;
	}

	const status_code_100_continue=100;
	const status_code_101_switching_protocols=101;
	const status_code_200_ok=200;
	const status_code_201_created=201;
	const status_code_202_accepted=202;
	const status_code_203_non_authoritative_information=203;
	const status_code_204_no_content=204;
	const status_code_205_reset_content=205;
	const status_code_206_partial_content=206;
	const status_code_300_multiple_choices=300;
	const status_code_302_found=302;
	const status_code_303_see_other=303;
	const status_code_304_not_modified=304;
	const status_code_305_use_proxy=305;
	const status_code_400_bad_request=400;
	const status_code_401_unauthorized=401;
	const status_code_402_payment_required=402;
	const status_code_403_forbidden=403;
	const status_code_404_not_found=404;
	const status_code_405_method_not_allowed=405;
	const status_code_406_not_acceptable=406;
	const status_code_407_proxy_authentication_required=407;
	const status_code_408_request_timeout=408;
	const status_code_409_conflict=409;
	const status_code_410_gone=410;
	const status_code_411_length_required=411;
	const status_code_412_precondition_failed=412;
	const status_code_413_request_entity_too_large=413;
	const status_code_414_request_uri_too_long=414;
	const status_code_415_unsupported_media_type=415;
	const status_code_416_requested_range_not_satisfiable=416;
	const status_code_417_expectation_failed=417;
	const status_code_418_i_am_a_teapot=418;
	const status_code_500_internal_server_error=500;
	const status_code_501_not_implemented=501;
	const status_code_502_bad_gateway=502;
	const status_code_503_service_unavailable=503;
	const status_code_504_gateway_timeout=504;
	const status_code_505_http_version_not_supported=505;

	private static function translate_status_code($_code = 0) {

		switch($_code) {
			case self::status_code_100_continue: 							return 'Continue';
			case self::status_code_101_switching_protocols:					return 'Switching Protocols';
			case self::status_code_200_ok:									return 'OK';
			case self::status_code_201_created:								return 'Created';
			case self::status_code_202_accepted:							return 'Accepted';
			case self::status_code_203_non_authoritative_information:		return 'Non-Authoritative Information';
			case self::status_code_204_no_content:							return 'No Content';
			case self::status_code_205_reset_content:						return 'Reset Content';
			case self::status_code_206_partial_content:						return 'Partial Content';
			case self::status_code_300_multiple_choices:					return 'Multiple Choices';
			case self::status_code_302_found:								return 'Found';
			case self::status_code_303_see_other:							return 'See Other';
			case self::status_code_304_not_modified:						return 'Not Modified';
			case self::status_code_305_use_proxy: 							return 'Use Proxy';
			case self::status_code_400_bad_request: 						return 'Bad Request';
			case self::status_code_401_unauthorized:						return 'Unauthorized';
			case self::status_code_402_payment_required:					return 'Payment Required';
			case self::status_code_403_forbidden:							return 'Forbidden';
			case self::status_code_404_not_found:							return 'Not Found';
			case self::status_code_405_method_not_allowed:					return 'Method Not Allowed';
			case self::status_code_406_not_acceptable:						return 'Not Acceptable';
			case self::status_code_407_proxy_authentication_required:		return 'Proxy Authentication Required';
			case self::status_code_408_request_timeout:						return 'Request Timeout';
			case self::status_code_409_conflict:							return 'Conflict';
			case self::status_code_410_gone:								return 'Gone';
			case self::status_code_411_length_required:						return 'Length Required';
			case self::status_code_412_precondition_failed:					return 'Precondition Failed';
			case self::status_code_413_request_entity_too_large:			return 'Request Entity Too Large';
			case self::status_code_414_request_uri_too_long:				return 'Request-URI Too Long';
			case self::status_code_415_unsupported_media_type:				return 'Unsupported Media Type';
			case self::status_code_416_requested_range_not_satisfiable:		return 'Requested Range Not Satisfiable';
			case self::status_code_417_expectation_failed:					return 'Expectation Failed';
			case self::status_code_418_i_am_a_teapot:						return 'I am a teapot';
			case self::status_code_500_internal_server_error:				return 'Internal Server Error';
			case self::status_code_501_not_implemented:						return 'Not Implemented';
			case self::status_code_502_bad_gateway:							return 'Bad Gateway';
			case self::status_code_503_service_unavailable:					return 'Service Unavailable';
			case self::status_code_504_gateway_timeout:						return 'Gateway Timeout';
			case self::status_code_505_http_version_not_supported:			return 'HTTP Version Not Supported';
			default:														return $_code;
		}
	}
};
