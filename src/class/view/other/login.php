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
		}, [
			"assets/css/bootstrap/bootstrap-grid.min.css",
			"assets/css/bootstrap/bootstrap-reboot.min.css",
			"assets/css/bootstrap/bootstrap.min.css",
			"assets/css/fontawesome/all.min.css",
			"assets/css/style.css"
		]);	//Not a section, but...
	}

	public function 				create_view() {

		$view_errors='';
		switch($this->error_code) {
			case 'in': $view_errors='Datos de entrada incorrectos.'; break;
		}


		$error_class=strlen($view_errors) ? null : 'hidden';

		return <<<R
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-3">
					<form role="form" class="form-horizontal" method="post" action="do_login">
						<div class="form-group">
							<input type="text" name="user" id="user" class="form-control" value="{$this->username}" placeholder="Introduce tu usuario">
						</div>
						<div class="form-group">
							<input type="password" name="pass" id="pass" class="form-control" placeholder="Introduce tu clave">
						</div>
						<div class="alert alert-danger alert-dismissable {$error_class}" >
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<span>{$view_errors}</span>
						</div>
						<div class="form-group">

							<button type="reset" class="btn btn-default">Borrar</button>
							<button type="submit" class="btn btn-primary">Entrar</button>

							<div class="checkbox">
								<input type="checkbox" name="remember" id="remember" value="1" />
								<label for="remember">
									<b>Recuerdame</b>
								</label>
							</div>
						</div>
						<p>Si no recuerdas la contraseña pincha <a href="recovery">aqui</a></p>
					</form>
				</div>
			</div>
		</div>
R;

	}
}
