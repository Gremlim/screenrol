<?php
namespace view\error;

class error extends \view\view {

	private $url;
	private $request;
	private $error;

	public function 			__construct(\request\request $_r, $_u) {

		$this->request=$_r;
		$this->url=$_u;
	}

	public function 			inject_error(\tools\error_reporter\error $_err) {

		$this->error=$_err;
	}

	public function 			create_view() {

		$stack_trace=array_reduce($this->error->get_backtrace(), function($_carry, $_item) {

			$_carry.=<<<R
				<li>
					<span class="index">{$_item->get_index()}</span>
					<span class="file">{$_item->get_file()}</span>
					<span class="line">{$_item->get_line()}</span>
					<span class="function">{$_item->get_function()}</span>
				</li>
R;

			return $_carry;

		}, '');


		$request_plain=$this->request->to_string();
		$trans=\tools\error_reporter\tools::translate_error_code($this->error->get_severity());

		return <<<R
<!DOCTYPE html>
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">

html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, font, img, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td {margin: 0;padding: 0;border: 0;outline: 0;font-size: 100%;vertical-align: baseline;background: transparent;}
body {line-height: 1;}
ol, ul {list-style: none;}
blockquote, q {quotes: none;}
blockquote:before, blockquote:after,q:before, q:after {content: "";content: none;}

@-webkit-keyframes spin1 {
0% { -webkit-transform: rotate(0deg);}
100% { -webkit-transform: rotate(360deg);}
}
@-moz-keyframes spin1 {
0% { -moz-transform: rotate(0deg);}
100% { -moz-transform: rotate(360deg);}
}
@-o-keyframes spin1 {
0% { -o-transform: rotate(0deg);}
100% { -o-transform: rotate(360deg);}
}
@-ms-keyframes spin1 {
0% { -ms-transform: rotate(0deg);}
100% { -ms-transform: rotate(360deg);}
}
@-keyframes spin1 {
0% { transform: rotate(0deg);}
100% { transform: rotate(360deg);}
}

html {background: #000;}
#main {width: 1024px; margin: 1em auto; background: #CCC; position: relative; color: #222; padding: 1em;}
.logo {position: absolute; display: block;margin: 10px;}
.logo img.img-logo {position: absolute; z-index: 10; width: 80px; height: 80px;}
.logo img.img-cog {
	position: absolute; z-index: 5; /*width:241px; height:241px;*/
	width: 80px; height: 80px;
	-webkit-animation: spin1 5s infinite linear;
	-moz-animation: spin1 5s infinite linear;
	-o-animation: spin1 5s infinite linear;
	-ms-animation: spin1 5s infinite linear;
	animation: spin1 5s infinite linear;
}
h1, h2 {text-align: center; margin-bottom: 1em;}
/* First */
.body { margin:8px 0px;}
.body .info {margin-bottom:5px;font-weight:bold;}
.body .mensaje { width:99%;border:1px solid gray;background-color: #ECBC62;padding:5px;line-height:1.5em;}
.body .mensaje .severity {font-weight:bold;}
.body .mensaje .message {}
.body .mensaje .file {font-weight:bold;}
.body .mensaje .line {font-weight:bold;}

/* Second */
.trace {margin: 8px 0px;}
.trace p {margin:8px 0px;font-weight:bold;}
/* .trace ul {width:100%;max-height:50px;overflow:auto;} */
.trace ul {background-color:#DDDDDD;width:99%;border:1px solid gray;padding:5px;line-height:1em;}
.trace ul li {font-family: "Courier New";font-size:12px;}

/* Thirth */
.request p {margin:8px 0px;font-weight:bold;}
.request pre {font-family: "Courier New";background-color:#DDDDDD;font-size:12px;width:99%;border:1px solid gray;padding:5px;}

	</style>
</head>
<body>
	<div class="logo" onclick="this.style.display='none'">
		<img src="{$this->url}assets/img/d20.png" class="img-logo">
		<img src="{$this->url}assets/img/cog.png" class="img-cog">
		&nbsp;
	</div>
	<div id="main">

		<h1>Ha ocurrido un error:</h1>

		<h2>Por favor, informa al equipo de desarrollo y adjunta la información de esta página.</h2>

		<div class="body">
			<p class="info">Se ha detectado un error en la ejecución de la aplicación:</p>
			<p class="mensaje">
				<span class="severity">[{$trans}]</span>
				<span class="message">{$this->error->get_message()}</span>
				en el fichero
				<span class="file">{$this->error->get_file()}</span>
				, línea
				<span class="line">{$this->error->get_line()}</span>
			</p>
		</div>
		<div class="trace">
			<p>La traza de ejecución es</p>
			<ul>
				{$stack_trace}
			</ul>
		</div>
		<div class="request">
			<p>Adicionalmente, la petición que ha causado el error es:</p>
			<pre>{$request_plain}</pre>
		</div>
	</div>
</body>
</html>
R;
	}
}
