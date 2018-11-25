<?php
namespace tools;

class logic {

	//!Returns a pair. The first value will be res_true if check is true,
	//!res_false otherwhise. The second value will be the remanent.
	//!check can also be callable, which actually does not make a lot of sense.

	public static function exclusive_pair($_check, $_res_true, $_res_false=null) {

		$match=null;
		if(is_callable($_check)) {
			$match=$_check();
		}
		else {
			$match=(bool)$_check;
		}

		return $match ? [$_res_true, $_res_false] : [$_res_false, $_res_true];
	}

	//!Reverses the Y-M-D order of a date.

	public static function reverse_date($_date, $_desired_separator, $_present_separator=null) {

		if(null===$_present_separator) {
			$_present_separator=$_desired_separator;
		}

		return implode($_desired_separator , array_reverse(explode($_present_separator, $_date)));
	}
}
