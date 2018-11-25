<?php
namespace orm;

class prototype{

	private $instance=null;
	private $dirty=false;
	private $load_function;

	public function set_load_function($_val){
		$this->load_function=$_val;
		return $this;
	}

	public function get(){
		if(false===$this->dirty){
			$this->instance=call_user_func($this->load_function);
			$this->dirty=true;
		}
		return $this->instance;
	}
	public function set(\orm\model $_model){
		$this->instance=$_model;
		$this->dirty=true;
	}


}
