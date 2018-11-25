<?php
namespace view\##FULL_NAMESPACE##;

class ##CLASSNAME## extends \view\section {

	public function 				__construct() {

	}

	public function					get_js_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/js/section/##CLASSNAME##.js']);
	}

	public function					get_css_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/css/section/##CLASSNAME##.css']);
	}

	public function create_view(\view\view_bundle $_vb) {
		$l=$_vb->get_lang()->path('##CLASSNAME##');
		return <<<R
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="grid simple ">
					<div class="grid-title no-border">
						<h4>Section > Title</h4>
						<div class="tools"> <a href="javascript:;" class="collapse"></a></div>
					</div>
					<div class="grid-body no-border">

						<h3>{$l->g('title')}</h3>
						<p>Lorem ullamco do nostrud veniam ipsum mollit nostrud irure id voluptate irure.</p>
						<br>
						
					</div>
				</div>
			</div>
		</div>
R;
	}
}
