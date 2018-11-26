<?php
namespace model\pathfinder;

class pj_sesion extends \orm\model{
	
	private $id;
	private $xp;
	private $jugado;

	private $pj;
	private $sesion;

	public function __construct(){
		$this->register_prototype('pj',$this->pj);
		$this->register_prototype('sesion',$this->sesion);
	}

	public function get_id(){return $this->id;}
	public function get_pj(){return $this->pj->get();}
	public function get_sesion(){return $this->sesion->get();}
	public function get_xp(){return $this->xp;}
	public function get_jugado(){return $this->jugado;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_pj($_val){$this->pj->set($_val); return $this;}
	public function set_sesion($_val){$this->sesion->set($_val); return $this;}
	public function set_xp($_val){$this->xp=$_val; return $this;}
	public function set_jugado($_val){$this->jugado=$_val; return $this;}
}
