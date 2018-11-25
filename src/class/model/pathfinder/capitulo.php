<?php
namespace model\pathfinder;

class capitulo extends \orm\model{
	
	private $id;
	private $idaventura;
	private $nombre;
	private $num;

	public function get_id(){return $this->id;}
	public function get_idaventura(){return $this->idaventura;}
	public function get_nombre(){return $this->nombre;}
	public function get_num(){return $this->num;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idaventura($_val){$this->idaventura=$_val; return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_num($_val){$this->num=$_val; return $this;}
}
