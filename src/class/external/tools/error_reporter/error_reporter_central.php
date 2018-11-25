<?php
namespace tools\error_reporter;

abstract class error_reporter_central {

	private static 				$reporter=null;
	private static				$level=-1;

	public static function 		init(error_reporter $_er=null, $_level=-1, $_display=1) {

		if(null===$_er) {
			self::$reporter=new default_reporter;
		}
		else {
			self::$reporter=$_er;
		}

		\set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
				if(error_reporter_central::report_error($err_severity, $err_msg, $err_file, $err_line)) {
					exit();
				}
		});

		\register_shutdown_function(function () {
			$error=error_get_last();
			if($error) {
				error_reporter_central::report_error($error['type'], $error['message'], $error['file'], $error['line']);
			}
		});

		self::$level=$_level;
		\ini_set('error_reporting', self::$level);
		\ini_set('display_errors', $_display);
	}

	public static function 		report_error($_s, $_m, $_f, $_l){

		if(null===self::$reporter) {
			throw new \Exception("error_reporter_central::init must be called before using the error_reporting tool");
		}

		if($_s & self::$level) {

			$backtraces=[];
			foreach(debug_backtrace() as $k => $v) {
				$backtraces[]=backtrace::from_array_and_key($v, $k);
			}

			self::$reporter->report(new error($_s, $_m, $_f, $_l, $backtraces));
			return true;
		}

		return false;
	}
};
