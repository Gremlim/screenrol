<?php
namespace model\pathfinder;

class capitulo extends \orm\model{
	
	private $id;
	private $aventura;
	private $nombre;
	private $num;

	public function __construct(){
		$this->register_prototype('aventura',$this->aventura);
	}

	public function get_id(){return $this->id;}
	public function get_aventura(){return $this->aventura->get();}
	public function get_nombre(){return $this->nombre;}
	public function get_num(){return $this->num;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_aventura($_val){$this->aventura->set($_val); return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_num($_val){$this->num=$_val; return $this;}
}
