<?php
namespace model\app;

class session_user extends \orm\model{

	private $id;
	private $phpsessid;
	private $usuario;
	private $fecha;
	private $persist;
	private $last_activity;

	public function __construct(){
		$this->register_prototype('usuario',$this->usuario);
	}

	public function get_id(){return $this->id;}
	public function get_phpsessid(){return $this->phpsessid;}
	public function get_usuario(){return $this->usuario->get();}
	public function get_fecha(){return $this->fecha;}
	public function get_persist(){return $this->persist;}
	public function get_last_activity(){return $this->last_activity;}

	public function set_id($_value){
		$this->id=$_value;
		return $this;
	}
	public function set_phpsessid($_value){
		$this->phpsessid=$_value;
		return $this;
	}
	public function set_usuario($_value){
		$this->usuario->set($_value);
		return $this;
	}
	public function set_fecha($_value){
		$this->fecha=$_value;
		return $this;
	}
	public function set_persist($_value){
		$this->persist=$_value;
		return $this;
	}
	public function set_last_activity($_value){
		$this->last_activity=$_value;
		return $this;
	}

	public function jsonSerialize() {
		return (object) get_object_vars($this);
	}
}
