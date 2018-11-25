<?php
namespace orm;

//!Modelo para objetos de la base te datos.
abstract class model{

    private static          $map=[];   //!< Se guarda el mapa de propiedades de la tabla en la base de datos
	private static			$load_function=null; //!< variable para guardar la funcion de carga de mapas
	private $protos;
	public static function from_defaults($_class_name){

		if(null===$_class_name || !class_exists($_class_name)){
			throw new model_exception("Se debe incluir una clase válida");
		}
		$res=new $_class_name;
		return $res->get_defaults();
	}

	//! Se inserta la funcion de carga de mapas en JSON para poder modelar los
	//! objetos del modelo y obtener la informacion de la tabla en la BD
	public static function 	inject_load_function($_f){

		if(!is_callable($_f)){
			throw new model_exception("inject_load_function debe recibir una funcion válida");
		}
		self::$load_function=$_f;
	}

	//! Se obtiene el mapa creado en JSON de la tabla de la base de datos.

	//! El fichero JSON tendrá el mismo nombre que la clase que setea el mapa.
	//! Una vez se devuelve el mapa, queda guardado para proximas peticiones
	//! así no hay que consultar el JSON una y otra vez
	public static function         get_database_map($_class_name=null) {

		if(null===self::$load_function) {
			throw new model_exception("No se ha cargado la funcion de carga de mapas");
		}
        $classname= $_class_name ? $_class_name : self::class;

        if(!isset(self::$map[$classname])){
			$json=call_user_func(self::$load_function,$classname);

			if(!property_exists($json, "tablename") || !property_exists($json, "primary") || !property_exists($json, "fields")) {
                throw new model_exception("file '$_class_name.json' contains malformed patterns");
            }
			foreach($json->fields as $k=>$field){
				if(!property_exists($field, "name") || !property_exists($field, "field") || !property_exists($field, "type") || !property_exists($field, "default")) {
	                throw new model_exception("file '$_class_name.json' contains malformed patterns in field [".$k."]");
				}
			}

            self::$map[$classname]=$json;
        }
        return self::$map[$classname];
    }

	//! Con esta funcion seteamos la clave primaria de un modelo sin necesitar
	//! conocer el nombre del mismo ya que proviene del mapa obtenido previamente.
    public function         set_pk(pk $pk_value){

		$map=model::get_database_map(get_class($this));
        $method_id='set_'.$map->primary;
		if(!method_exists($this,$method_id)){
			throw new model_exception("El metodo '".$method_id."' no existe en el modelo");
		}
        $this->$method_id($pk_value->value);
        return $this;
    }

	//! Con esta funcion obtenemos la clave primaria de un modelo sin necesitar
	//! conocer el nombre del mismo ya que proviene del mapa obtenido previamente.
    public function         get_pk(){

		$map=model::get_database_map(get_class($this));
        $method_id='get_'.$map->primary;
		if(!method_exists($this,$method_id)){
			throw new model_exception("El metodo '".$method_id."' no existe en el modelo");
		}
        return $this->$method_id();
    }

	//! Usamos esta funcion para generar el objeto con los datos por defecto que
	//! le asignamos en el mapa JSON que hemos obtenido previamente.
    private function get_defaults(){

		$map=model::get_database_map(get_class($this));
		foreach($map->fields as $field){
            if($field->field!=$map->primary){
				if(!substr_count($field->type,'::')){
					$method='set_'.$field->name;
					if(!method_exists($this,$method)){
						throw new model_exception("El metodo '".$method."' no existe en el modelo");
					}
	                switch($field->type){
	                    case 'int':
	                        $this->$method((int)$field->default);
	                    break;
	                    case 'string':
	                        $this->$method((string)$field->default);
	                    break;
	                    case 'boolean':
	                        $this->$method((boolean)$field->default);
	                    break;
	                    case 'float':
	                        $this->$method((float)$field->default);
	                    break;
	                    default:
	                        throw new model_exception('El tipo de campo "'.$field->type.'" no está soportado.');
	                    break;
	                }
				}
            }
        }
		return $this;
    }

	//! Convierte los datos del objeto en un array
	public function to_array(){
		$map=model::get_database_map(get_class($this));

		return array_reduce($map->fields,function($_carry,$_item){

			$method='get_'.$_item->name;
			if(substr_count($_item->type,'::')){
				$classname_instance=str_replace('::',"\\",$_item->type);
				if(!class_exists($classname_instance)){
					throw new model_exception('La clase "'.$classname_instance.'" no existe. Por lo tanto no se puede definir en el modelo '.$_classname);
				}
				$obj=$this->$method();
				$meth_ref='get_'.$_item->reference;

				$_carry[$_item->field]=$obj->$meth_ref();
			}else{
				if(!method_exists($this,$method)){
					throw new model_exception("El metodo '".$method."' no existe en el modelo");
				}
				$_carry[$_item->field]=$this->$method();
			}

			return $_carry;
		},[]);
	}

	public function register_prototype($_k,&$_val){
		$this->protos[$_k]=new \orm\prototype();
		$_val=$this->protos[$_k];
	}

	public function get_prototype_by_key($_k){
		if(!isset($this->protos[$_k])){
			throw new model_exception("No existe ningun prototipo con la clave '$_k'");
		}
		return $this->protos[$_k];
	}

}
