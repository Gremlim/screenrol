<?php
namespace model\pathfinder;

class personaje extends \orm\model{
	
	private $id;
	private $idjugador;
	private $nombre;
	private $raza;
	private $clase;
	private $estado;
	private $sexo;
	private $img;
	private $xpactual;

	public function get_id(){return $this->id;}
	public function get_idjugador(){return $this->idjugador;}
	public function get_nombre(){return $this->nombre;}
	public function get_raza(){return $this->raza;}
	public function get_clase(){return $this->clase;}
	public function get_estado(){return $this->estado;}
	public function get_sexo(){return $this->sexo;}
	public function get_img(){return $this->img;}
	public function get_xpactual(){return $this->xpactual;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_idjugador($_val){$this->idjugador=$_val; return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_raza($_val){$this->raza=$_val; return $this;}
	public function set_clase($_val){$this->clase=$_val; return $this;}
	public function set_estado($_val){$this->estado=$_val; return $this;}
	public function set_sexo($_val){$this->sexo=$_val; return $this;}
	public function set_img($_val){$this->img=$_val; return $this;}
	public function set_xpactual($_val){$this->xpactual=$_val; return $this;}
}
