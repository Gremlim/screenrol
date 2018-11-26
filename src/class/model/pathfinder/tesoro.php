<?php
namespace model\pathfinder;

class aventura extends \orm\model{
	
	private $id;
	private $tesoro;
	private $ppt;
	private $po;
	private $pp;
	private $pc;
	private $libro;
	private $pagina;
	private $cantidad;
	private $estado;

	private $episodio;
	private $sesion;

	public function __construct(){
		$this->register_prototype('episodio',$this->episodio);
		$this->register_prototype('sesion',$this->sesion);
	}

	public function get_id(){ return $this->id;}
	public function get_tesoro(){ return $this->tesoro;}
	public function get_ppt(){ return $this->ppt;}
	public function get_po(){ return $this->po;}
	public function get_pp(){ return $this->pp;}
	public function get_pc(){ return $this->pc;}
	public function get_libro(){ return $this->libro;}
	public function get_pagina(){ return $this->pagina;}
	public function get_cantidad(){ return $this->cantidad;}
	public function get_estado(){ return $this->estado;}

	public function get_episodio(){ return $this->episodio->get();}
	public function get_sesion(){ return $this->sesion->get();}

	public function set_id($_val){ $this->id=$_val; return $this;}
	public function set_tesoro($_val){ $this->tesoro=$_val; return $this;}
	public function set_ppt($_val){ $this->ppt=$_val; return $this;}
	public function set_po($_val){ $this->po=$_val; return $this;}
	public function set_pp($_val){ $this->pp=$_val; return $this;}
	public function set_pc($_val){ $this->pc=$_val; return $this;}
	public function set_libro($_val){ $this->libro=$_val; return $this;}
	public function set_pagina($_val){ $this->pagina=$_val; return $this;}
	public function set_cantidad($_val){ $this->cantidad=$_val; return $this;}
	public function set_estado($_val){ $this->estado=$_val; return $this;}
	
	public function set_episodio($_val){ $this->episodio->set($_val); return $this;}
	public function set_sesion($_val){ $this->sesion->set($_val); return $this;}
}
