<?php
namespace view\other;

class login extends \view\section {

	private							$error_code;
	private							$username;

    public function 				__construct($_error_code, $_username) {

		$this->error_code=$_error_code;
		$this->username=$_username;
	}

	public function					get_css_array() {

		return array_map(function($_item) {
			return \app\tools::build_url($_item);
		}, ['assets/css/sections/login.css',
		'assets/css/bootstrap.min.css',
		'assets/css/style.css']);	//Not a section, but...
	}

	public function 				create_view() {

		$view_errors='';
		switch($this->error_code) {
			case 'in': $view_errors='Datos de entrada incorrectos.'; break;
		}


		$error_class=strlen($view_errors) ? null : 'hidden';

		return <<<R
		<div class="container-fluid">
			<div id="saltoslogin"></div>

			<div id="contLogin" class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4">
				<div class="row">
					<form role="form" class="form-horizontal" method="post" action="do_login">
						<div class="form-group">
							<div class="col-sm-8  col-lg-offset-3">
								<input type="text" name="user" id="user" class="form-control" value="{$this->username}" placeholder="Introduce tu usuario">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-8 col-lg-offset-3">
								<input type="password" name="pass" id="pass" class="form-control" placeholder="Introduce tu clave">
						</div>
						</div>
						<div class="alert alert-danger alert-dismissable col-lg-8 col-lg-offset-3 {$error_class}" >
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<span>{$view_errors}</span>
						</div>
						<div class="form-group">
							<div class="col-lg-4 col-sm-offset-4" align="right" style="white-space: nowrap;">

								<button type="reset" class="btn btn-default">Borrar</button>
								<button type="submit" class="btn btn-primary">Entrar</button>
							</div>
							<div class="col-sm-3 checkbox checkbox-" align="center" style="color:white;">
								<input type="checkbox" name="remember" id="remember" value="1" />
								<label for="remember">
									<b>Recuerdame</b>
								</label>
							</div>
						</div>
						<div class="col-lg-7 col-lg-offset-3" align="center" style="color:white;font-weight:bold;">
							<p>Si no recuerdas la contraseña pincha <a href="recuperar_pass.php">aqui</a></p>
						</div>
					</form>
				</div>
			</div>
		</div>
R;

	}
}
