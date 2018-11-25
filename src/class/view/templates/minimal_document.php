<?php
namespace view\templates;

class minimal_document extends \view\view {

	private							$view=null;

	public function 				__construct(\view\view $_view) {

		$this->view=$_view;
	}

	public function 				create_view() {

		$view_script_tags=$this->build_script_tags($this->view->get_js_array());
		$view_style_tags=$this->build_style_tags($this->view->get_css_array());
		$base=\app\tools::build_url();

		return <<<R
<!DOCTYPE html>
<html lang="es">
<head>
	<base id="document_base_tag" href="{$base}" />
	<meta charset="utf-8">
{$view_style_tags}
{$view_script_tags}
	<title></title>
<body>
{$this->view->create_view()}
</body>
</html>
R;
	}

	//TODO: This is already repeated many times over.
	private function 				build_script_tags(array $_data) {

		return array_reduce($_data, function($_acum, $_item) {
			return $_acum.=<<<R
		<script type="text/javascript" src="{$_item}"></script>
R;
		}, '');
	}

	//TODO: This is already repeated many times over.
	private function 				build_style_tags(array $_data) {

		return array_reduce($_data, function($_acum, $_item) {
			return $_acum.=<<<R
		<link rel="stylesheet" title="estilos" type="text/css" href="{$_item}" media="screen" />
R;
}, '');
	}
}
