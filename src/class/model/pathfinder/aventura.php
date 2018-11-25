<?php
namespace model\pathfinder;

class aventura extends \orm\model{
	
	private $id;
	private $nombre;
	private $img;
	private $idespecial;
	private $idusuario;
	private $descripcion;
	private $publica;
	private $vel_subida;

	public function get_id(){return $this->id;}
	public function get_nombre(){return $this->nombre;}
	public function get_img(){return $this->img;}
	public function get_idespecial(){return $this->idespecial;}
	public function get_idusuario(){return $this->idusuario;}
	public function get_descripcion(){return $this->descripcion;}
	public function get_publica(){return $this->publica;}
	public function get_vel_subida(){return $this->vel_subida;}

	public function set_id($_val){ $this->id=$_val; return $this;}
	public function set_nombre($_val){ $this->nombre=$_val; return $this;}
	public function set_img($_val){ $this->img=$_val; return $this;}
	public function set_idespecial($_val){ $this->idespecial=$_val; return $this;}
	public function set_idusuario($_val){ $this->idusuario=$_val; return $this;}
	public function set_descripcion($_val){ $this->descripcion=$_val; return $this;}
	public function set_publica($_val){ $this->publica=$_val; return $this;}
	public function set_vel_subida($_val){ $this->vel_subida=$_val; return $this;}
}
