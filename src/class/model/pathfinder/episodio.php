<?php
namespace model\pathfinder;

class episodio extends \orm\model{
	
	private $id;
	private $capitulo;
	private $nombre;
	private $num;

	public function __construct(){
		$this->register_prototype('capitulo',$this->capitulo);
	}

	public function get_id(){return $this->id;}
	public function get_capitulo(){return $this->capitulo->get();}
	public function get_nombre(){return $this->nombre;}
	public function get_num(){return $this->num;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_capitulo($_val){$this->capitulo->set($_val); return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_num($_val){$this->num=$_val; return $this;}
}
