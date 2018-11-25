<?php
namespace view;

abstract class view {

	public abstract function 	create_view();

	//!Returns an array of css full paths for inclusion in the view.
	public function				get_css_array() {
		return [];
	}

	//!Returns an array of JS full paths for inclusion in the view.
	public function				get_js_array() {
		return [];
	}
}
