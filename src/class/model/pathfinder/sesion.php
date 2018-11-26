<?php
namespace model\pathfinder;

class sesion extends \orm\model{
	
	private $id;
	private $porcen_no_jug;
	private $nombre;
	private $fecha;
	private $aventura;

	public function __construct(){
		$this->register_prototype('aventura',$this->aventura);
	}

	public function get_id(){return $this->id;}
	public function get_aventura(){return $this->aventura->get();}
	public function get_porcen_no_jug(){return $this->porcen_no_jug;}
	public function get_nombre(){return $this->nombre;}
	public function get_fecha(){return $this->fecha;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_aventura($_val){$this->aventura->set($_val); return $this;}
	public function set_porcen_no_jug($_val){$this->porcen_no_jug=$_val; return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_fecha($_val){$this->fecha=$_val; return $this;}
}
