<?php
namespace view\other;

class login_recover_pass extends \view\section {

	private 						$sent;
	private							$message;

    public function 				__construct($_s, $_m) {

		$this->sent=$_s;
		$this->message=$_m;
	}

	public function					get_js_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['js/jquery-2.1.0.min.js',
			'js/sections/login_recover_pass.js']);
	}

	public function					get_css_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/css/sections/login.css',
		'assets/css/bootstrap.min.css',
		'assets/css/style.css.css']);	//Not a section, but...
	}

	public function 				create_view() {

		if($this->sent) {
			return $this->show_as_sent();
		}
		else {
			return $this->show_form();
		}

	}

	private function				show_as_sent() {

		return <<<R
<center>
	<div id="contenedorCentral">
	<div id="imglogo" style="width:180px;">
			<a href="/app">
				<img src="img/logo.png" class="img-responsive">
			</a>
	</div>
	<div id="formularioLogin">
		<div class="alert alert-success col-sm-4 col-sm-offset-4">
			<b>Correo de recuperación Enviado Correctamente.</b> Pinche <a href="/app">Aquí</a> para volver al inicio.
		</div>
	</div>
</center>
R;
	}

	private function				show_form() {

		$show_errors='';

		switch($this->message) {
			case 1: $show_errors='El email introducido no pertence a Screenrol'; break;
			case 2: $show_errors='Ha ocurrido un error a la hora de enviar el correo'; break;
			case 3: $show_errors='El enlace seguido es incorrecto'; break;
			case 4: $show_errors='No ha sido posible restaurar la contraseña'; break;
		}

		$class_errors=!strlen($show_errors) ? 'hidden' : null;

		return <<<R
<center>
	<div id="contenedorCentral">
	<div id="imglogo" style="width:180px;">
			<a href="/app">
				<img src="img/logo.png" class="img-responsive">
			</a>
	</div>
	<div id="formularioLogin">
		<div class="alert alert-danger col-sm-4 col-sm-offset-4 {$class_errors}">{$show_errors}</div>

		<div class="panel panel-default col-sm-4 col-sm-offset-4">
			<div class="panel-heading">
				<h3 class="panel-title">Reestablecer Contrase&ntilde;a</h3>
			</div>
			<div class="panel-body">
				<center><b>¿Que va a pasar cuando pulse el boton de "Reestablecer"?</b></center>
				<p>
Cuando pulse el boton se enviará un correo con un enlace de
verificación a la cuenta de correo que nos indique mas abajo. Cuando pinche en
ese enlace se le dará la opcion de crear un nuevo usuario si no lo tiene o en
caso contrario de modificar su contraseña. Una vez pase esto, podra acceder a la app con su usuario y contraseña.
				</p>
				<br/>

				<form class="form-horizontal" id="formu" method="post" role="form" action="login/perform_pass_recovery">
					<div class="form-group" id="formMail">
					<label for="email" class="col-lg-2 control-label">Email</label>
					<div class="col-lg-10">
						<input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu Email">
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-12">
						<a class="btn btn-default" id="botonBorrar" href="/app">Cancelar</a>
						<input type="button" name="entrar" value="Restablecer" class="btn btn-primary" id="botonEntrar"/>
					</div>
				</div>
			</form>
		</div>
	</div>
</center>
R;
	}
}
