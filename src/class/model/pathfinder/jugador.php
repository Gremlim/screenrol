<?php
namespace model\pathfinder;

class jugador extends \orm\model{
	
	private $id;
	private $estado;
	private $usuario;
	private $aventura;
	
	public function __construct(){
		$this->register_prototype('usuario',$this->usuario);
		$this->register_prototype('aventura',$this->aventura);
	}

	public function get_id(){return $this->id;}
	public function get_estado(){return $this->estado;}
	public function get_usuario(){return $this->usuario->get();}
	public function get_aventura(){return $this->aventura->get();}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
	public function set_usuario($_val){$this->usuario->set($_val); return $this;}
	public function set_aventura($_val){$this->aventura->set($_val); return $this;}
}
