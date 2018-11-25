<?php
namespace model\pathfinder;

class instancia_tesoro extends \orm\model{
	
	private $id;
	private $idinstancia;
	private $idsesion;
	private $idtesoro;
	private $estado;
	
	public function get_id(){return $this->id;}
	public function get_idinstancia(){return $this->idinstancia;}
	public function get_idsesion(){return $this->idsesion;}
	public function get_idtesoro(){return $this->idtesoro;}
	public function get_estado(){return $this->estado;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idinstancia($_val){$this->idinstancia=$_val; return $this;}
	public function set_idsesion($_val){$this->idsesion=$_val; return $this;}
	public function set_idtesoro($_val){$this->idtesoro=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
}
