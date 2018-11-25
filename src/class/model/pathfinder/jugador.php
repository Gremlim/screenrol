<?php
namespace model\pathfinder;

class jugador extends \orm\model{
	
	private $id;
	private $idinstancia;
	private $estado;
	private $nombre;
	
	public function get_id(){return $this->id;}
	public function get_idinstancia(){return $this->idinstancia;}
	public function get_estado(){return $this->estado;}
	public function get_nombre(){return $this->nombre;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idinstancia($_val){$this->idinstancia=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
}
