<?php
namespace model\pathfinder;

class encuentro extends \orm\model{
	
	private $id;
	private $monstruo;
	private $xp;
	private $cantidad;
	private $secundario;
	private $estado;
	private $episodio;
	private $sesion;

	public function __construct(){
		$this->register_prototype('episodio',$this->episodio);
		$this->register_prototype('sesion',$this->sesion);
	}

	public function get_id(){return $this->id;}
	public function get_monstruo(){return $this->monstruo;}
	public function get_xp(){return $this->xp;}
	public function get_cantidad(){return $this->cantidad;}
	public function get_secundario(){return $this->secundario;}
	public function get_estado(){return $this->estado;}
	public function get_episodio(){return $this->episodio->get();}
	public function get_sesion(){return $this->sesion->get();}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_monstruo($_val){$this->monstruo=$_val; return $this;}
	public function set_xp($_val){$this->xp=$_val; return $this;}
	public function set_cantidad($_val){$this->cantidad=$_val; return $this;}
	public function set_secundario($_val){$this->secundario=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
	public function set_episodio($_val){$this->episodio->set($_val); return $this;}
	public function set_sesion($_val){$this->sesion->set($_val); return $this;}
}
