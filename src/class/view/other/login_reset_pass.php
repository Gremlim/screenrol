<?php
namespace view\other;

class login_reset_pass extends \view\section {

	private							$email;
	private							$seed;
	private							$message_code;
	private							$username_sent;

    public function 				__construct($_email, $_seed, $_msg, $_username_sent) {

		$this->email=$_email;
		$this->seed=$_seed;
		$this->message_code=$_msg;
		$this->username_sent=$_username_sent;
	}

	public function					get_js_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['js/sections/login_reset_pass.js']);
	}

	public function					get_css_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/css/sections/login.css',
		'assets/css/bootstrap.min.css',
		'assets/css/style.css.css']);	//Not a section, but...
	}

	public function 				create_view() {

		if($this->username_sent) {
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
		<div id="imglogo" style="width:180px;"><a href="/app"><img src="img/logo.png" class="img-responsive"></a></div>
		<div id="formularioLogin">
			<div class="alert alert-success col-sm-4 col-sm-offset-4">
			La clave para <b>{$this->username_sent}</b> ha sido reseteada correctamente.
			<br />
			<br />
 			Pinche <a href="/app">Aquí</a> para volver al inicio.</div>
		</div>
	</center>
R;
	}

	private function				show_form() {

		$show_error='';

		switch($this->message_code) {
			case 1: $show_error="Las contraseñas proporcionadas no coinciden"; break;
			case 2: $show_error="Ha ocurrido un error en el proceso de recuperación"; break;
		}

		$display_error=!strlen($show_error) ? 'hidden' : null;
		$url=\app\tools::build_url('login/perform_pass_change');

		return <<<R
	<center>
		<div id="contenedorCentral">
		<div id="imglogo" style="width:180px;"><a href="/app"><img src="img/logo.png" class="img-responsive"></a></div>
		<div id="formularioLogin">
			<div class="panel panel-default col-sm-4 col-sm-offset-4">
				<div class="panel-heading">
					<h3 class="panel-title">Recuperar la contraseña</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="formu" method="post" role="form" action="{$url}" >
						<div class="form-group formUser">
							<label for="usuario" class="col-lg-2 control-label">Usuario</label>
							<div class="col-lg-10">
								<p align="left" style="color:#32558E;font-weight:bold;padding-top:5px;">{$this->email}</p>
								<input type="hidden" class="form-control" name="usuario" id="usuario" value="{$this->email}">
							</div>
						</div>
						<div class="form-group formPass">
							<label for="clave" class="col-lg-2 control-label">Clave</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" name="pass" id="clave" placeholder="Introduce la Contraseña">
							</div>
						</div>
						<div class="form-group formPass">
							<label for="rclave" class="col-lg-2 control-label">Repita la Clave</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" name="check_pass" id="rclave" placeholder="Repite la Contraseña">
							</div>
						</div>
						<div class="alert alert-danger col-lg-12 {$display_error}">{$show_error}</div>

						<div class="form-group">
							<div class="col-lg-12">
							<a class="btn btn-default" id="botonBorrar" href="recuperar_pass.php">Cancelar</a>
								<input type="submit" name="enviar" value="Restablecer" class="btn btn-primary" id="botonEntrar"/>
							</div>
						</div>

						<input type="hidden" name="seed" id="seed" value="{$this->seed}" />
						<input type="hidden" name="email" id="email" value="{$this->email}" />
					</form>
				</div>
			</div>
		</div>
</center>
R;
	}
}
