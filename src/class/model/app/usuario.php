<?php
namespace model\app;

class usuario extends \orm\model{
	private $cod;
	private $usuario;
	private $password;
	private $ultimo_acceso;
	private $email;
	private $permisos;
	private $tlf;
	private $nombre;

	public function get_cod(){return $this->cod;}
	public function get_usuario(){return $this->usuario;}
	public function get_password(){return $this->password;}
	public function get_ultimo_acceso(){return $this->ultimo_acceso;}
	public function get_email(){return $this->email;}
	public function get_permisos(){return $this->permisos;}
	public function get_tlf(){return $this->tlf;}
	public function get_nombre(){return $this->nombre;}

	public function set_cod($_value){
		$this->cod=$_value;
		return $this;
	}
	public function set_usuario($_value){
		$this->usuario=$_value;
		return $this;
	}
	public function set_password($_value){
		$this->password=$_value;
		return $this;
	}
	public function set_ultimo_acceso($_value){
		$this->ultimo_acceso=$_value;
		return $this;
	}
	public function set_email($_value){
		$this->email=$_value;
		return $this;
	}
	public function set_permisos($_value){
		$this->permisos=$_value;
		return $this;
	}
	public function set_tlf($_value){
		$this->tlf=$_value;
		return $this;
	}
	public function set_nombre($_value){
		$this->nombre=$_value;
		return $this;
	}

	public function jsonSerialize() {
		return (object) get_object_vars($this);
	}
}
