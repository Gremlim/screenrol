<?php
namespace tools;

class html {

	//!Generates the HTML markup for an array of keys and values where the array
	//!key will be the option value and the array value will be the option
	//!innerHTML. The $_current parameter is used to match a current selection
	//!by its array key. It can also be an array of keys or a callable function
	//!that will receive the current key and value and must return true if
	//!the item must be selected.
	public static function				options_from_array(array $_options, $_current=null) {

		array_walk($_options, function(&$_val, $_key) use ($_current) {

			$selected=null;
			if(null!==$_current) {
				if(is_array($_current)) {
					$selected=in_array($_key, $_current) ? 'selected' : null;
				}
				else if(is_callable($_current)) {
					$selected=$_current($_key, $_val) ? 'selected' : null;
				}
				else {
					$selected=$_key==$_current ? ' selected ' : null;
				}
			}

			$_val='<option value="'.$_key.'"'.$selected.'>'.$_val.'</option>'.PHP_EOL;
		});

		return array_reduce($_options, function($_carry, $_item) {
			$_carry.=$_item;
			return $_carry;
		}, '');
	}
}
