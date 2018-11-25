<?php
namespace model\pathfinder;

class instancia extends \orm\model{
	
	private $id;
	private $idusuario;
	private $idaventura;
	private $fecha;
	private $estado;

	public function get_id(){return $this->id;}
	public function get_idusuario(){return $this->idusuario;}
	public function get_idaventura(){return $this->idaventura;}
	public function get_fecha(){return $this->fecha;}
	public function get_estado(){return $this->estado;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idusuario($_val){$this->idusuario=$_val; return $this;}
	public function set_idaventura($_val){$this->idaventura=$_val; return $this;}
	public function set_fecha($_val){$this->fecha=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
}
