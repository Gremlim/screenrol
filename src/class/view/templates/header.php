<?php
namespace view\templates;

//!This is the class that encapsulates the header of the site.
class header extends \view\view {

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

		$username=$this->di->get_user() ? $this->di->get_user()->get_user() : null;

		$view_navbar=$this->build_navbar($username);
		$base=\app\tools::build_url();

		$view_script_tags=$this->build_script_tags();
		$view_style_tags=$this->build_style_tags();
		

		return <<<R
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> <!-- Hace que no coja el navegador un tamaño optimo, si no el que tiene. User-scalable no permite hacer zoom -->
		<base id="document_base_tag" href="{$base}" />
		<title>Screenrol</title>
		<link rel="icon" type="image/ico" href="favicon.ico" />

		{$view_style_tags}
		{$view_script_tags}

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="bodyDash" class="not_madness">
		{$view_navbar}
		<div class="container">
			<div class="row">
				<div class="col-12">
R;

	}

	private function 				build_script_tags() {

		//TODO: This is data... Should go somewhere else.
		$load=array(
			"assets/js/jquery/jquery-3.3.1.min.js",
			"assets/js/bootstrap/bootstrap.min.js",
			// "assets/js/fontawesome/all.min.js",
			"assets/js/app/fetch.js",
			"assets/js/app/modal.js",
			"assets/js/app/common.js"
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
			"assets/css/bootstrap/bootstrap-grid.min.css",
			"assets/css/bootstrap/bootstrap-reboot.min.css",
			"assets/css/bootstrap/bootstrap.min.css",
			"assets/css/fontawesome/all.min.css",
			"assets/css/style.css"
		);


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

	
	private function 				build_navbar($username) {


		return <<<R
		<!-- NAVBAR -->
		<nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #FAEEE7;">
			<a class="navbar-brand" href="#">Navbar</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Link</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Dropdown
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="#">Action</a>
					<a class="dropdown-item" href="#">Another action</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">Something else here</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" href="#">Disabled</a>
				</li>
				</ul>
				<form class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
				</form>
			</div>
		</nav>
R;
	}
}
