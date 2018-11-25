<?php
namespace orm;

//! Crea un grupo de parametros o grupos instancias de resolve_to_sql

//! Se le envia por argumentos el numero que queramos de instancias de resolve_to_sql
//! para poder generar las busquedas de consultas preparadas para PDO pudiendo
//! tambien bindear los valores de los parametros.
abstract class group implements resolve_to_sql{
	private $arguments;

	//! Obtiene la union para el group de sus clases hijas
	protected abstract function get_union();

	public function __construct(){
		$this->arguments = func_get_args();
	}

	//! Construye la consulta para PDO y devuelve un string con el resultado
	public function make_sql_string(){
		$argumentos=[];
		foreach($this->arguments as $arg){
			if($arg instanceof resolve_to_sql){
				$argumentos[]=$arg->make_sql_string();
			}else{
				if(is_object($arg)){
					throw new \Exception('Argumento erroneo ('.get_class($arg).') en el grupo al construir');
				}else{
					throw new \Exception("Argumento erroneo ($arg) en el grupo al construir");
				}
			}
		}

		return '('.implode($this->get_union(),$argumentos).')';
	}

	//! Pasandole un PDOStatement bindea los valores de los parametros del grupo
	public function bind_value(\PDOStatement $_stmt){

		foreach($this->arguments as $arg){
			if($arg instanceof resolve_to_sql){
				$arg->bind_value($_stmt);
			}else{
				if(is_object($arg)){
					throw new \Exception("Argumento erroneo ('.get_class($arg).') en el grupo al hacer 'bind_value'");
				}else{
					throw new \Exception("Argumento erroneo ($arg) en el grupo al hacer 'bind_value'");
				}
			}
		}

		return $_stmt;
	}
}
