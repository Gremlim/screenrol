<?php
namespace app;

class router_result {

	public						$classname;
	public						$method_name;
	public						$params;

	public	function			__construct($_c, $_m, array $_p) {

		$this->classname=$_c;
		$this->method_name=$_m;
		$this->params=$_p;
	}

	public function				check() {

		if(!class_exists($this->classname)) {
			throw new router_exception('Class '.$this->classname.' does not exist');
		}

		if(!method_exists($this->classname, $this->method_name)) {
			throw new router_exception('Method '.$this->method_name.' does not exist in '.$this->classname);
		}
	}
}
