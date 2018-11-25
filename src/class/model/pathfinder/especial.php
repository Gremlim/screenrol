<?php
namespace model\pathfinder;

class especial extends \orm\model{
	
	private $id;
	private $nombre;
	private $img;

	public function get_id(){return $this->id;}
	public function get_nombre(){return $this->nombre;}
	public function get_img(){return $this->img;}

	public function set_id($_val){$this->id=$_val; return $this;}
	public function set_nombre($_val){$this->nombre=$_val; return $this;}
	public function set_img($_val){$this->img=$_val; return $this;}
}
