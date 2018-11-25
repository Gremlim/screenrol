<?php
namespace orm;

//! Esta es la clase para clave primaria de los modelos de BD
class pk{
    public $value; //!< Es el valor de la clave primaria
    public function __construct($_value){
		$this->value=$_value;
    }
}
