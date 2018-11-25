<?php
namespace tools\error_reporter;

class tools {

	public static function		translate_error_code($_code) {

		switch($_code) {
			case E_ERROR:				return "ERROR";
			case E_WARNING:				return "WARNING";
			case E_PARSE:				return "PARSE";
			case E_NOTICE:				return "NOTICE";
			case E_CORE_ERROR:			return "CORE_ERROR";
			case E_CORE_WARNING:		return "CORE_WARNING";
			case E_COMPILE_ERROR:		return "COMPILE_ERROR";
			case E_COMPILE_WARNING:		return "COMPILE_WARNING";
			case E_USER_ERROR:			return "USER_ERROR";
			case E_USER_WARNING:		return "USER_WARNING";
			case E_USER_NOTICE:			return "USER_NOTICE";
			case E_STRICT:				return "STRICT";
			case E_RECOVERABLE_ERROR:	return "RECOVERABLE_ERROR";
			case E_DEPRECATED:			return "DEPRECATED";
			case E_USER_DEPRECATED:		return "USER_DEPRECATED";
			default:					return '???';
		}
	}
}
