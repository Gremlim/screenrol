<?php
namespace view\templates;

//TODO: Why??
require_once(\app\tools::build_path('lib/funciones.php'));

//!This is the class that encapsulates the header of the site.
class header extends \view\view {

	private $menu=null;
	private $current_section=null;
	private $section_css=[];
	private $section_js=[];
	private $di;
	private	$is_router=true;

	public function 				__construct(\app\dependency_injector $_di, $_current_section=null) {
		$this->di=$_di;
		$request=$_di->get_request();
		//Hack.
		$this->current_section=null !== $_current_section ? $_current_section : \app\tools::normalize_script_path($request->get_uri_without_query_string());
		$this->menu=new \app\menu();
	}

	public function					set_no_router() {
		$this->is_router=false;
	}

	//!Sets an array of absolute paths as the section JS.
	public function					set_section_js(array $_d) {

		$this->section_js=$_d;
	}

	//!Sets an array of absolute paths as the section css.
	public function					set_section_css(array $_d) {

		$this->section_css=$_d;
	}

	public function 				create_view() {

		$username=$this->di->get_user() ? $this->di->get_user()->get_usuario() : null;
		$current_profile=$this->di->get_user() ? $this->di->get_user()->get_permisos() : null;

		$menu_view_desktop=new \view\templates\menu_desktop($this->menu->get_categories(), $this->current_section, $current_profile);
		$menu_view_mobile=new \view\templates\menu_mobile($this->menu->get_categories(), $this->current_section, $current_profile);


		$view_menu_desktop=$menu_view_desktop->create_view();
		$view_menu_mobile=$menu_view_mobile->create_view();

		$movil=detectar_dispositivo();

		$view_navbar=$this->build_navbar($movil, $username);
		$base=\app\tools::build_url();

		$view_script_tags=$this->build_script_tags();
		$view_style_tags=$this->build_style_tags();
		$view_modal=$this->build_modal();

		$invert_logo_no_router=$this->is_router ? null :
<<<R
<style type="text/css">
#logo {moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); filter: FlipH; -ms-filter: "FlipH";}
</style>
R;

		return <<<R
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> <!-- Hace que no coja el navegador un tamaño optimo, si no el que tiene. User-scalable no permite hacer zoom -->
		<base id="document_base_tag" href="{$base}" />
		<title>Screenrol</title>
		<link rel="icon" type="image/png" href="img/favicon16.png" />

		{$view_style_tags}
		{$invert_logo_no_router}

		{$view_script_tags}


		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="bodyDash" class="not_madness">
		<div id="not_madness"></div>
		<div id="dash-container">
		{$view_modal}
		{$view_navbar}

	  	<!--LATERAL GRANDE IZQUIERDA-->
		<div id="menLateral" class="menLateral col-sm-2 col-xs-9" >
			<div class="row">
				<div class="col-sm-11 menUp listmenLateral">
					<h4 class="pull-right">
						<a href="index.php"><span class="fa fa-home pointer"></span></a>
					</h4>
				</div>
			</div>

			<div class="row" style="margin-top:-20px;">
				<div class="col-xs-3 col-xs-offset-2 col-sm-offset-2">
					<img src="img/logo.jpg" class="img-responsive img-circle" id="logo">
				</div>
				<h3 class="col-xs-6 col-sm-5" style="color:white;">App</h3>
			</div>
			<br>
			<div class="row" style="height:58.5%;padding-top:0;" >
				<div class="listmenLateral" style="height:100%;padding-top:0;margin-top:0;">
					<div style="position:relative;overflow:hidden;height:100%;margin-top:0;padding-top:0;font-size:12px;" class="scrollbar">&nbsp;
					{$view_menu_desktop}
					</div>
				</div>
			</div>
		</div>

	  	<!--LATERAL CHICO-->
		<div id="menLateral2" align="center" class="menLateral2 hidden-xs oculto" >
			<div style="height:60%;overflow:hidden;" class="scrollbar">
				<div class="listmenLateral">
					{$view_menu_mobile}
					<br>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-2" id="contenedorTotal">
R;

	}

	private function 				build_script_tags() {

		//TODO: This is data... Should go somewhere else.
		$load=array(
			"assets/js/jquery-2.1.0.min.js",
			"assets/js/jquery-ui-1.11.0.custom.min.js",
			"assets/js/bootstrap.min.js",
			"assets/js/bootstrap-switch.min.js",
			"assets/js/bootstrap-dialog.min.js",
			"assets/js/perfect-scrollbar.min.js",
			"assets/js/jquery.uploadify.js",
			"assets/js/moment.js",
			"assets/js/moment-with-locales.js",
			"assets/js/bootstrap-datepicker.js",
			"assets/js/bootstrap-datetimepicker.min.js",
			"assets/js/locales/bootstrap-datepicker.es.min.js",
			"assets/js/highcharts.js",
			"assets/js/themes/grid.js",
			"assets/js/modules/exporting.js",
			"assets/js/export-csv.js",
			"assets/js/app/modal.js",
			"assets/js/app/madness.js",
			"assets/js/app/common.js?t=".time(),
			"assets/js/app/fetch.js"
		);

		return array_reduce(array_unique(array_merge($load, $this->section_js)), function($_acum, $_item) {
			$_acum.=<<<R
		<script type="text/javascript" src="{$_item}"></script>

R;
			return $_acum;
		}, '');
	}

	private function 				build_style_tags() {

		//TODO: This is data... Should go somewhere else.
		$load=array(
			"assets/css/font-awesome.min.css",
			"assets/css/bootstrap.min.css",
			"assets/css/bootstrap-switch.min.css",
			"assets/css/bootstrap-dialog.min.css",
			"assets/css/bootstrap-theme.min.css",
			"assets/css/awesome-bootstrap-checkbox.css",
			"assets/css/jquery-ui-1.11.0.custom.min.css",
			"assets/css/style.css?t=".time(),
			"assets/css/madness.css?t=".time(),
			"assets/css/datepicker.css",
			"assets/css/perfect-scrollbar.min.css",
			"assets/css/font-awesome.min.css",
			"assets/css/bootstrap.min.css",
			"assets/css/bootstrap-switch.min.css",
			"assets/css/bootstrap-dialog.min.css",
			"assets/css/bootstrap-theme.min.css",
			"assets/css/awesome-bootstrap-checkbox.css",
			"assets/css/jquery-ui-1.11.0.custom.min.css",
			"assets/css/style.css",
			"assets/css/dist.css",
			"assets/css/datepicker.css",
			"assets/css/perfect-scrollbar.min.css");


		return array_reduce(array_unique(array_merge($load, $this->section_css)), function($_acum, $_item) {
			$_acum.=<<<R
		<link rel="stylesheet" title="estilos" type="text/css" href="{$_item}" media="screen" />

R;
			return $_acum;
}, '');
	}

	private function 				get_fa_bars_mobile($_mobil) {

		return !$_mobil ? null : <<<R

			<div class="menLateral navbar-header pull-right" >
				<a class="navbar-brand colapsaLateral">
					<span class="fa fa-bars pointer"></span>
				</a>
			</div>
R;
	}

	private function 				build_modal() {

		return <<<R
		<!-- Modal -->
		<div class="modal fade" id="dialog-modal" tabindex="-1" role="dialog" aria-labelledby="dialog-modal-title" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="dialog-modal-title"></h4>
			  </div>
			  <div class="modal-body" id="dialog-modal-body"></div>
			</div>
		  </div>
		</div>
R;
	}

	private function 				build_navbar($movil, $username) {

		$btnsup_class=$movil ? 'menLateral2' : 'col-sm-5';
		$btnsup_class_2=$movil ? 'menLateral2' : '';

		$style_form=$movil ? 'width:50%;' : '';

		$view_fa_bars_mobile=$this->get_fa_bars_mobile($movil);

		return <<<R
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" >
			<div id="botSup" class="{$btnsup_class} navbar-header pull-left" style="padding-right:0px;margin-right:0px;">
				<div class="menLateral col-sm-5 col-xs-5" >&nbsp;</div>
				<a class="navbar-brand colapsaLateral">
					<span class="fa fa-bars pointer"></span>
				</a>

				<form class="form-inline" method="get" action="buscador" style="white-space:nowrap;margin-top:5px;">
					<input type="text" name="search" class="form-control pull-left" style="{$style_form}" placeholder="Busqueda">
					<button type="submit" class="btn btn-default pull-left fa fa-search" style="margin-right:0px;"></button>
				</form>
			</div>
			{$view_fa_bars_mobile}
			<div id="botSup2" class="{$btnsup_class_2} navbar-header pull-right">
				<ul class="nav pull-left">
					<li class="dropdown pull-right">
						<a href="#" data-toggle="dropdown" style="color:#777; margin-top: 5px;" class="dropdown-toggle">
							<span class="hidden-xs">
								{$username}&nbsp;
							</span>
							<span class="fa fa-user"></span><b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="perfil.php"><span class="fa fa-cog"></span> Perfil</a>
							</li>
							<li>
								<a href="logout" title="Logout"><span class="fa fa-power-off desconectar"></span> Salir</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>


		</nav>
R;
	}
}
