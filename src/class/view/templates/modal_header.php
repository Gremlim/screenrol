<?php
namespace view\templates;

//TODO: Why??
require_once(\app\tools::build_path('lib/funciones.php'));

//!This is the class that encapsulates the header of the site.
class modal_header extends \view\view {

	private $menu=null;
	private $current_section=null;
	private $section_css=[];
	private $section_js=[];

	public function 				__construct(array $_section_js, array $_section_css) {
		$this->section_js=$_section_js;
        $this->section_css=$_section_css;
	}
	public function 				create_view() {
		$view_script_tags=$this->build_script_tags();
		$view_style_tags=$this->build_style_tags();

		return <<<R
		{$view_style_tags}
		{$view_script_tags}
R;
	}

	private function 				build_script_tags() {

		return array_reduce($this->section_js, function($_acum, $_item) {
			$_acum.=<<<R
		<script type="text/javascript" src="{$_item}"></script>
R;
			return $_acum;
		}, '');
	}

	private function 				build_style_tags() {

		return array_reduce($this->section_css, function($_acum, $_item) {
			$_acum.=<<<R
		<link rel="stylesheet" title="estilos" type="text/css" href="{$_item}" media="screen" />
R;
			return $_acum;
}, '');
	}
}
