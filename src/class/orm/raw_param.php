<?php
namespace orm;

//! Crea un parametro preparado para PDO para añadirlo a las consultas

//! Dados un campo, operador y valor, puede preparar y bindear un campo de la
//! consulta de la base de datos preparada para PDO.
class raw_param implements resolve_to_sql{
	const like=' LIKE ';
	const like_partial=' LIKE %%';
	const not_like=' NOT LIKE ';
	const equal='=';
	const greater='>';
	const lower='<';
	const greater_equal='>=';
	const lower_equal='<=';
	const not_equal='!=';
	const in='IN (...)';
	const not_in='NOT IN (...)';
	const between='BETWEEN';
	const not_between='NOT BETWEEN';
	const is=' IS ';
	const is_not=' IS NOT ';

	private $field;
	private $operator;
	private $value;

	public function __construct($_field,$_operator,$_value=null){

		$this->field=$_field;
		$this->operator=$_operator;
		$this->value=$_value;
	}

	//! Construye la consulta en PDO del parametro segun su operador
	public function make_sql_string(){

		switch($this->operator){
			case self::like:
			case self::not_like:
			case self::equal:
			case self::not_equal:
			case self::is:
			case self::is_not:
			case self::greater:
			case self::lower:
			case self::greater_equal:
			case self::lower_equal:
				if(!is_scalar($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un escalar.');
				}
				return $this->field.$this->operator.$this->value;
			break;
			case self::like_partial:
				if(!is_scalar($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un escalar.');
				}
				return $this->field.' LIKE '.$this->value;
			break;
			case self::in:
				//TODO: DRY.
				if(!is_array($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un array.');
				}
				return $this->field.' IN ('.implode(',',$this->value).')';
			break;
			case self::not_in:
				if(!is_array($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un array.');
				}
				return $this->field.' NOT IN ('.implode(',',$this->value).')';
			break;
			case self::between:
				if(!is_array($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un array.');
				}
				if(count($this->value)!=2){
					throw new param_exception('El numero de valores pasados es incorrecto. Deben ser 2.');
				}
				return $this->field.' BETWEEN '.$this->value[0].' AND '.$this->value[1];
			break;
			case self::not_between:
				if(!is_array($this->value)){
					throw new param_exception('El valor ('.$this->value.') dado para '.$this->field.' es incompatible con el operador. Debería ser un array.');
				}
				if(count($this->value)!=2){
					throw new param_exception('El numero de valores pasados es incorreto. Deben ser 2.');
				}
				return $this->field.' NOT BETWEEN '.$this->value[0].' AND '.$this->value[1];
			break;
			default:
				throw new param_exception('Operacion no encontrada');
			break;
		}
	}

	//! Devuelve el mismo PDOStatement
	public function bind_value(\PDOStatement $_stmt){
		return $_stmt;
	}

}
