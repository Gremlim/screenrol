<?php
namespace model\pathfinder;

class encuentro extends \orm\model{
	
	private $id;
	private $idepisodio;
	private $monstruo;
	private $xp;
	private $cantidad;
	private $secundario;
	private $idinstancia;

	public function get_id(){return $this->id;}
	public function get_idepisodio(){return $this->idepisodio;}
	public function get_monstruo(){return $this->monstruo;}
	public function get_xp(){return $this->xp;}
	public function get_cantidad(){return $this->cantidad;}
	public function get_secundario(){return $this->secundario;}
	public function get_idinstancia(){return $this->idinstancia;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idepisodio($_val){$this->idepisodio=$_val; return $this;}
	public function set_monstruo($_val){$this->monstruo=$_val; return $this;}
	public function set_xp($_val){$this->xp=$_val; return $this;}
	public function set_cantidad($_val){$this->cantidad=$_val; return $this;}
	public function set_secundario($_val){$this->secundario=$_val; return $this;}
	public function set_idinstancia($_val){$this->idinstancia=$_val; return $this;}
}
