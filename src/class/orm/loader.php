<?php
namespace orm;

class loader{

	//! Carga el array devuelto por la base de datos segun el mapa de la clase

    //! Los tipos deben ser entregados por el mapa de la clase, los unicos tipos
    //! permitidos son "int", "string", "boolean" y "float"
	public static function load_object(array $_row,$_classname,$_map,\orm\entity_manager $_em){
		if(!$_row){
			throw new \Exception('No se le ha entregado ningun dato al loader');
		}
        $obj=new $_classname; //!< El constructor de Las clases debe ser público para poder crear nuevos objetos que se iteren aqui

        foreach($_map->fields as $field){
            if($field->field==$_map->primary){
                $obj->set_pk(new pk($_row[$field->field]));
            }else{

				if(substr_count($field->type,'::')){
					$classname_instance=str_replace('::',"\\",$field->type);
					if(!class_exists($classname_instance)){
						throw new model_exception('La clase "'.$classname_instance.'" no existe. Por lo tanto no se puede definir en el modelo '.$_classname);
					}

					$obj->get_prototype_by_key($field->name)
						->set_load_function(function() use ($_em,$classname_instance, $_row, $field){
							return $_em->get_one_by_field($classname_instance,$field->reference,$_row[$field->field]);
						});
				}else{
					$method='set_'.$field->name;

					if(!method_exists($obj, $method)) {
						throw new entity_manager_exception("El método '$method' no existe en la clase '".get_class($obj)."', quizás la clase no está construida correctamente como modelo.");
					}

	                switch($field->type){
	                    case 'int':
	                        $obj->$method((int)$_row[$field->field]);
	                    break;
	                    case 'string':
	                        $obj->$method((string)$_row[$field->field]);
	                    break;
	                    case 'boolean':
	                        $obj->$method((boolean)$_row[$field->field]);
	                    break;
	                    case 'float':
	                        $obj->$method((float)$_row[$field->field]);
	                    break;
	                    default:
	                        throw new entity_manager_exception('El tipo de campo "'.$field->type.'" no está soportado.');
	                    break;
	                }
				}
            }
        }
        return $obj;
    }
}
