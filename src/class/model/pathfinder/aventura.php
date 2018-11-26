<?php
namespace model\pathfinder;

class aventura extends \orm\model{
	
	private $id;
	private $nombre;
	private $img;
	private $especial;
	private $usuario;
	private $created_by;
	private $descripcion;
	private $publica;
	private $vel_subida;
	private $fecha;
	private $estado;

	public function __construct(){
		$this->register_prototype('usuario',$this->usuario);
		$this->register_prototype('created_by',$this->created_by);
		$this->register_prototype('especial',$this->especial);
	}

	public function get_id(){return $this->id;}
	public function get_nombre(){return $this->nombre;}
	public function get_img(){return $this->img;}
	public function get_descripcion(){return $this->descripcion;}
	public function get_publica(){return $this->publica;}
	public function get_vel_subida(){return $this->vel_subida;}
	public function get_fecha(){return $this->fecha;}
	public function get_estado(){return $this->estado;}
	public function get_especial(){return $this->especial->get();}
	public function get_usuario(){return $this->usuario->get();}
	public function get_created_by(){return $this->created_by->get();}

	public function set_id($_val){ $this->id=$_val; return $this;}
	public function set_nombre($_val){ $this->nombre=$_val; return $this;}
	public function set_img($_val){ $this->img=$_val; return $this;}
	public function set_descripcion($_val){ $this->descripcion=$_val; return $this;}
	public function set_publica($_val){ $this->publica=$_val; return $this;}
	public function set_vel_subida($_val){ $this->vel_subida=$_val; return $this;}
	public function set_fecha($_val){ $this->fecha=$_val; return $this;}
	public function set_estado($_val){ $this->estado=$_val; return $this;}
	public function set_especial($_val){ $this->especial->set($_val); return $this;}
	public function set_usuario($_val){ $this->usuario->set($_val); return $this;}
	public function set_created_by($_val){ $this->created_by->set($_val); return $this;}
}
