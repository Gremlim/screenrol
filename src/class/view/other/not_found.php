<?php
namespace view\other;

//!The view for the 404 page.
//TODO: This should not be a section, but rather a regular view we can control.
class not_found extends \view\view {

	public function				get_js_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['js/external/js-marquee.js',
			'js/jquery-2.1.0.min.js',
			'js/jquery-ui-1.11.0.custom.min.js',
			'js/sections/not_found.js']);
	}

	public function				get_css_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/css/sections/not_found.css']);
	}

	public function create_view() {

//TODO: Markup is yet another failure to plan ahead.

		return <<<R
<div id="title_container" class="oculto">
	<h2>Esto es un 404</h2>
	<p>Parece que no podemos encontrar lo que estás buscando... ¿Cómo has llegado hasta aquí?</p>
</div>
<div id="animation_container"></div>
R;
	}
}
