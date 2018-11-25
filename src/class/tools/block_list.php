<?php
namespace tools;
class block_list_exception extends \Exception{
	public function __construct($_msg){
		parent::__construct($_msg);
	}
}
class block_list{

	const PERMISSION_DENY=false;
	const PERMISSION_ALLOW=true;

	private $filename;
	private $permission;
	private $other_check;
	private $data;

	public function __construct($_filename,$_permission,$_other_check=null){
		$this->filename=$_filename;
		$this->permission=$_permission;
		$this->other_check=$_other_check;
	}

	public function add($_value){
		$data=$this->get();
		if(null===$data){
			throw new block_list_exception('No existe la lista');
		}
		if(false===array_search($_value,$data)){
			array_push($data, $_value);
			return $this->save($data);
		}
		return true;
	}

	public function delete($_value){
		$data=$this->get();
		if(null===$data){
			throw new block_list_exception('No existe la lista');
		}
		$key=array_search($_value, $data);
		if($key!==false){
			unset($data[$key]);
			return $this->save($data);
		}
		return true;
	}

	public function check($_value){
		$data=$this->get();
		foreach($data as $item){
			$item=trim($item);
			if($item!='' && substr($item,0,1)!='#'){
				$estr=str_replace(["/"],["\/"],$item);
				$pattern="/^".str_replace('*','[\d\w\/\.\,\;\-\_\@]+',$estr)."$/";
				$search=preg_match($pattern,$_value);
				if(false===$search){
					throw new block_list_exception("Hubo un error al verificar las lineas con el item '$item'");
				}
				if(1===$search){
					return $this->permission;
				}
			}
		}
		if(null!==$this->other_check){
			return call_user_func($this->other_check,$_value);
		}
		return !($this->permission);
	}

	private function get(){
		if(null===$this->data){
			if(!file_exists($this->filename)) {
				throw new block_list_exception("El fichero '$this->filename' no existe, creelo para poder usar la lista");
			}
			$this->data = explode("\n",file_get_contents($this->filename));
		}
		return $this->data;
	}

	private function save(array $_data){
		if(!file_exists($this->filename)) {
			touch($this->filename);
			chmod($this->filename, 0775);
		}
		return file_put_contents($this->filename, implode("\n",$_data));
	}
}
