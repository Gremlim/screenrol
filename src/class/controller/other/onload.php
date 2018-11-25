<?php
namespace controller\other;

require_once(\app\tools::build_path('lib/mysql.inc.php'));

class onload extends \controller\controller {

    public function				__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);
	}

}
