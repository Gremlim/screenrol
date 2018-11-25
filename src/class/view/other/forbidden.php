<?php
namespace view\other;

class forbidden extends \view\section {

	public function create_view() {

		return <<<R
		<div class="col-sm-8 col-sm-offset-2" align="center">
			<img class="img-responsive" src="img/logotxt.png" style="width:250px;" title="Screenrol">
			<h2>No tienes permisos para acceder a esta sección</h2>
		</div>
R;
	}
}
