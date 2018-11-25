<?php
namespace model\app;

class usuario extends \orm\model{
	private $id;
	private $user;
	private $pass;
	private $mail;
	private $ultconex;
	private $root;
	private $borrado;
	public function get_id(){return $this->id;}
	public function get_user(){return $this->user;}
	public function get_pass(){return $this->pass;}
	public function get_mail(){return $this->mail;}
	public function get_ultconex(){return $this->ultconex;}
	public function get_root(){return $this->root;}
	public function get_borrado(){return $this->borrado;}

	public function set_id($_val){ 			$this->id=$_val; 		return $this;}
	public function set_user($_val){ 		$this->user=$_val; 		return $this;}
	public function set_pass($_val){ 		$this->pass=$_val; 		return $this;}
	public function set_mail($_val){ 		$this->mail=$_val; 		return $this;}
	public function set_ultconex($_val){ 	$this->ultconex=$_val;	return $this;}
	public function set_root($_val){ 		$this->root=$_val; 		return $this;}
	public function set_borrado($_val){ 	$this->borrado=$_val;	return $this;}
	
}
