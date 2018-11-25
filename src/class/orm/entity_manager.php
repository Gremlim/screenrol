<?php
namespace orm;

//! Esta clase manejará las llamadas a base de datos de los modelos obtenidos
//!NOTE: What happens with different entities from different databases, are they
//!different entity_managers?
class entity_manager{

    private $dbconn; //!Aqui almacenamos la conexion a base de datos PDO para utilizarla en las siguientes llamadas

	//! Para iniciarlo debemos crearlo con una db_connection previamente generada
    public function __construct(db_connection $_dbconn){

        $this->dbconn=$_dbconn;
    }

	//! Dado un objeto de un modelo, actualiza los datos en la base de datos
    public function update(model $_obj){

        if(!$_obj->get_pk()){
            throw new db_update_exception('El id del objeto no está definido');
        }

        $arr_fields=[];
        $arr_values=[];

		$map=model::get_database_map(get_class($_obj));

		foreach($map->fields as $field){

			$method='get_'.$field->name;
			//Check method exists...
			$data=$_obj->$method();
			if(substr_count($field->type,'::')){
				if(!($data instanceof \orm\model)){
					throw new entity_manager_exception('El objeto del campo "'.get_class($data).'" no es un objeto "model" valido');
				}
				$method_value='get_'.$field->reference;
				$value=$data->$method_value();
			}else{
				$value=$data;
			}
			$arr_values[$field->field]=$value;


			if($field->field!=$map->primary){
				$arr_fields[]=$field->field.'=:'.$field->field;
			}
		}

		$values=implode(',',$arr_fields);
		//IDEA: Store this particular statement in a static map.
		//Each time I call update upon an entity type the database has to run a
		//"prepare" first. We could store a map of entity:prepared_statement and
		//reuse it.
		$query="UPDATE `".$map->tablename."` SET $values WHERE `".$map->primary."`=:".$map->primary." LIMIT 1";
		$stmt=$this->dbconn->conn->prepare($query);

		foreach($map->fields as $field){
			$stmt->bindParam(":".$field->field,$arr_values[$field->field]);
		}

		$stmt->execute();

        return $this;
    }

	//! Crea una nueva linea en la base de datos a partir de un modelo ya relleno
    public function create(model $_obj){

		if($_obj->get_pk()){
			throw new db_create_exception('El objeto ya ha sido creado');
		}

		$map=model::get_database_map(get_class($_obj));
        $arr_fields=[];
        $arr_values=[];

        //!Se recogen los valores del objetos y se escapan para la consulta
        foreach($map->fields as $field){
            if($field->field!=$map->primary){
                $method='get_'.$field->name;
                $arr_fields[]=$field->field;

				$data=$_obj->$method();
				if(substr_count($field->type,'::')){
					if(!($data instanceof \orm\model)){
						throw new entity_manager_exception('El objeto del campo "'.get_class($data).'" no es un objeto "model" valido');
					}
					$method_value='get_'.$field->reference;
					$value=$data->$method_value();
				}else{
					$value=$data;
				}
				$arr_values[$field->field]=$value;
            }
        }
        $fields='`'.implode('`,`',$arr_fields).'`';
        $values=':'.implode(',:',$arr_fields);

        $query="INSERT INTO `".$map->tablename."` ($fields) VALUES ($values)";
		//IDEA: Save and reuse this statment. As in "update".
		$stmt=$this->dbconn->conn->prepare($query);
		foreach($arr_fields as $field){
			$stmt->bindParam(":".$field,$arr_values[$field]);
		}
		$stmt->execute();
        $_obj->set_pk(new pk($this->dbconn->conn->lastInsertId()));

        return $this;
    }

	//! Elimina de la base de datos las referencias de un modelo dado
    public function delete(model $_obj){

		$id=$_obj->get_pk();

		if(!$id){
			throw new db_query_exception('El id del objeto no está definido');
		}

		$map=model::get_database_map(get_class($_obj));
		$query="DELETE FROM `".$map->tablename."` WHERE `".$map->primary."`=:id LIMIT 1";
		//IDEA: Perhaps save the statement... As above.
		$stmt=$this->dbconn->conn->prepare($query);
		$stmt->bindParam(':id',$id);
		$stmt->execute();

		return $this;
    }

	//! Funcion para obtener todos los elementos de un modelo dado

	//! La funcion devuelve un objeto iterable de em_iterable_result para
	//! poder interactual con el y recibir todos los elementos de la consulta
	/*!
		!$_class_name -> Es el nombre de la clase a buscar
		!$_order -> string del nombre del campo seguido del orden a realizar Ej: "campo3 ASC"
		!$_limit -> numero de resultados maximo
		!$_offset -> offset para el limit
	!*/
    public function get_all($_class_name,$_order=null,$_limit=null,$_offset=0){

		$calc_rows=(null!==$_limit) ? true : false;

        $map=model::get_database_map($_class_name);
        $query=$this->query_builder($map->tablename,null,$_order,$_limit,$_offset,'*',$calc_rows);
		$stmt=$this->dbconn->conn->query($query);

		return new em_iterable_result($this,$stmt,$_class_name,$map,$this->calculate_total_rows($calc_rows));
    }

	//! Obtiene el primer resultado ordenado por clave primaria del campo buscado
	/*!
		!$_class_name -> Es el nombre de la clase a buscar
		!$_field -> es el nombre del campo buscado
		!$_value -> es el valor buscado
	!*/
    public function get_one_by_field($_class_name,$_field,$_value){

		$map=model::get_database_map($_class_name);
		self::check_field($map->fields, $_field);

		$where=new param($_field,param::equal,$_value);
		$query=$this->query_builder($map->tablename,$where,$map->primary.' ASC',1,0);

		$stmt=$this->dbconn->conn->prepare($query);
		$where->bind_value($stmt);
		$stmt->execute();
		if($row=$stmt->fetch(\PDO::FETCH_ASSOC)){
			return loader::load_object($row,$_class_name,$map,$this);
		}else{
			return null;
		}
    }

	//! Obtiene todos los resultados del campo buscado
	/*!
		!$_class_name -> Es el nombre de la clase a buscar
		!$_field -> es el nombre del campo buscado
		!$_value -> es el valor buscado
		!$_order -> string del nombre del campo seguido del orden a realizar Ej: "campo3 ASC"
		!$_limit -> numero de resultados maximo
		!$_offset -> offset para el limit
	!*/
    public function get_all_by_field($_class_name,$_field,$_value,$_order=null,$_limit=null,$_offset=0){

        $map=model::get_database_map($_class_name);
		self::check_field($map->fields, $_field);

		$calc_rows=(null!==$_limit) ? true : false;

		$where=new param($_field,param::equal,$_value);
		$query=$this->query_builder($map->tablename,$where,$_order,$_limit,$_offset,'*',$calc_rows);
		$stmt=$this->dbconn->conn->prepare($query);

		$where->bind_value($stmt);
		$stmt->execute();

		return new em_iterable_result($this,$stmt,$_class_name,$map,$this->calculate_total_rows($calc_rows));
    }

	//! Devuelve un objeto modelado segun un valor de clave primaria
    public function get_by_id($_class_name,$_id){

        if(!$_id){
            throw new db_query_exception('El id del objeto no está definido');
        }
		$map=model::get_database_map($_class_name);

		$where=new param($map->primary,param::equal,$_id);
		$query=$this->query_builder($map->tablename,$where,null,1);
		$stmt=$this->dbconn->conn->prepare($query);
		$where->bind_value($stmt);
		$stmt->execute();

        return $stmt->rowCount() ? loader::load_object($stmt->fetch(\PDO::FETCH_ASSOC),$_class_name,$map,$this) : null;
	}

    //! Ejecuta una consulta generica dada a la funcion

	//! La funcion devuelve un objeto iterable de db_iterable_result para
	//! poder interactual con el y recibir todos los elementos de la consulta
    public function query_raw($query){

		$stmt=$this->dbconn->conn->query($query);
		return new db_iterable_result($stmt);
    }

    //! Construye la consulta para los SELECTs

	//! Aunque el unico valor obligatorio es $_table se puede asignar muchos mas campos.
	/*!
		!$_where -> es un array con los nombres de los campos a buscar para luego preparar el PDOStatement
		!$_order -> string del nombre del campo seguido del orden a realizar Ej: "campo3 ASC"
		!$_limit -> numero de resultados maximo
		!$_offset -> offset para el limit
		!$_fields -> campos que queremos que devuelva la consulta
		!$calc_rows -> lanza un SQL_CALC_FOUND_ROWS para luego recogerlo con otra consulta de "SELECT FOUND_ROWS();"
	!*/
    private function query_builder($_table, resolve_to_sql $_where=null,$_order=null,$_limit=null,$_offset=null,$_fields='*',$calc_rows=false){

		$limit = null!==$_limit ? "LIMIT ".$_limit : "" ;
		$offset = null!==$_offset && null!==$_limit ? "OFFSET ".$_offset : "" ;
        $order = null!==$_order ? "ORDER BY ".$_order : "" ;
		$calculate_rows=$calc_rows ? 'SQL_CALC_FOUND_ROWS' : '';
		$imp_where=(null!==$_where) ? "AND ". $_where->make_sql_string() : '';

        return "SELECT $calculate_rows ".$_fields." FROM `".$_table."` WHERE TRUE ".$imp_where." ".$order." ".$limit." ".$offset;
    }

	//! Funcion para obtener todos los resultados de una tabla dado un criterio instancia de "resolve_to_sql"
	/*!
		!$_class_name -> Es el nombre de la clase a buscar
		!$_criterio -> es una instancia resolve_to_sql con los criterios de busqueda
		!$_order -> string del nombre del campo seguido del orden a realizar Ej: "campo3 ASC"
		!$_limit -> numero de resultados maximo
		!$_offset -> offset para el limit
	!*/
	public function get_by_criteria($_class_name, resolve_to_sql $_criterio,$_order=null,$_limit=null,$_offset=0){

		if(null===$_class_name || !class_exists($_class_name)){
			throw new db_query_exception("El primer argumento debe ser una clase válida");
		}

		$calc_rows=(null!==$_limit) ? true : false;

		$map=model::get_database_map($_class_name);
		$query=$this->query_builder($map->tablename,$_criterio,$_order,$_limit,$_offset,'*',$calc_rows);
		$stmt=$this->dbconn->conn->prepare($query);
		$_criterio->bind_value($stmt);
		$stmt->execute();

		return new em_iterable_result($this,$stmt,$_class_name,$map,$this->calculate_total_rows($calc_rows));
	}
	//! Obtiene el primer resultado ordenado por clave primaria del campo buscado
	/*!
		!$_class_name -> Es el nombre de la clase a buscar
		!$_criterio -> es una instancia resolve_to_sql con los criterios de busqueda
	!*/
    public function get_one_by_criteria($_class_name,resolve_to_sql $_criterio){

		if(null===$_class_name || !class_exists($_class_name)){
			throw new db_query_exception("El primer argumento debe ser una clase válida");
		}
		$map=model::get_database_map($_class_name);

		$query=$this->query_builder($map->tablename,$_criterio,$map->primary.' ASC',1,0);

		$stmt=$this->dbconn->conn->prepare($query);
		$_criterio->bind_value($stmt);
		$stmt->execute();
		if($row=$stmt->fetch(\PDO::FETCH_ASSOC)){
			return loader::load_object($row,$_class_name,$map,$this);
		}else{
			return null;
		}
    }

	//!Checks if a field exists in a map. Throws if not.
	private static function check_field(array $_fields, $_field) {

		if(!count(array_filter($_fields, function($_item) use ($_field) {
			return $_item->field==$_field;
		}))) {
			throw new db_query_exception("El modelo no contiene el campo de busqueda '$_field'");
		}
	}

	//! Devuelve el numero de filas calculadas de la consulta o NULL si no lo
	//! debe calcular.
	private function calculate_total_rows($_calc_rows){
		if($_calc_rows!=false){
			$stmt_total=$this->dbconn->conn->query("SELECT FOUND_ROWS() as num;");
			$row=$stmt_total->fetch(\PDO::FETCH_ASSOC);
			return $row['num'];
		}
		return null;
	}

}
