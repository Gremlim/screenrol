<?php
namespace orm;

//! Clase para manejar el resultado iterable de la base de datos.
class em_iterable_result{
	private $stmt; //!< El PDOStatement de la consulta realizada anteriormente
	private $class_name; //!< Nombre de la clase que queremos construir
	private $map; //!< Mapa del modelo enviado
	private $total_rows; //!< El total de filas de la consulta si se realiza con un FOUND_ROWS()
	private $return_flag=true; //!< Esta flag servirá para solicitar que no envie mas contenido.

	private $em;
	//! Necesitamos el PDOStatement para poder construir este objeto
	public function __construct(\orm\entity_manager $_em,\PDOStatement $_stmt,$_class_name,$_map,$_total_rows=null){
		if(null===$_stmt){
			throw new entity_manager_exception('Se necesita un objeto \PDOStatement');
		}
		$this->stmt=$_stmt;
		$this->class_name=$_class_name;
		$this->map=$_map;
		$this->total_rows=$_total_rows;
		$this->em=$_em;
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
			$row=$this->stmt->fetch(\PDO::FETCH_ASSOC);
			if(!$row){
				return false;
			}
			return loader::load_object(
				$row,
				$this->class_name,
				$this->map,
				$this->em
			);
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
