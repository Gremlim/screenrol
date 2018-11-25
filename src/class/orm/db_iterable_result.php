<?php
namespace orm;

//! Clase para manejar el resultado iterable de la base de datos.
class db_iterable_result{
	private $stmt; //!< El PDOStatement de la consulta realizada anteriormente
	private $total_rows; //!< El total de filas de la consulta si se realiza con un FOUND_ROWS()
	private $return_flag=true; //!< Esta flag servirá para solicitar que no envie mas contenido.

	//! Necesitamos el PDOStatement para poder construir este objeto
	public function __construct(\PDOStatement $_stmt,$_total_rows=null){
		if(null===$_stmt){
			throw new entity_manager_exception('Se necesita un objeto \PDOStatement');
		}
		$this->stmt=$_stmt;
		$this->total_rows=$_total_rows;
	}

	//! Al destruir la clase eliminamos el PDOStatement y hacemos que no pueda
	//! continuar devolviendo filas.
	public function __destruct(){
		$this->return_flag=false;
		$this->stmt=null;
	}

	//! Esta funcion se utiliza para devolver el proximo contenido iterable
	public function next(){
		if(null===$this->stmt){
			throw new entity_manager_exception('No existe recurso de base de datos');
		}
		if($this->return_flag){
			return $this->stmt->fetch(\PDO::FETCH_ASSOC);
		}else{
			return false;
		}
	}

	//! Podemos pedir que deje de enviar contenido llamando a esta funcion.
	public function end(){
		$this->return_flag=false;
	}

	//! Se obtiene el total de las filas calculadas si el SQL tiene un limite puesto
	public function get_calc_rows(){
		if(null === $this->total_rows){
			throw new entity_manager_exception('La consulta realizada no puede obtener un total de filas');
		}

		return $this->total_rows;
	}

}
