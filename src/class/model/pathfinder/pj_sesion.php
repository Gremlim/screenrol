<?php
namespace model\pathfinder;

class pj_sesion extends \orm\model{
	
	private $id;
	private $idpj;
	private $idsesion;
	private $xp;
	private $jugado;

	public function get_id(){return $this->id;}
	public function get_idpj(){return $this->idpj;}
	public function get_idsesion(){return $this->idsesion;}
	public function get_xp(){return $this->xp;}
	public function get_jugado(){return $this->jugado;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idpj($_val){$this->idpj=$_val; return $this;}
	public function set_idsesion($_val){$this->idsesion=$_val; return $this;}
	public function set_xp($_val){$this->xp=$_val; return $this;}
	public function set_jugado($_val){$this->jugado=$_val; return $this;}
}
