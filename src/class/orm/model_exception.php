<?php

namespace orm;
//! Excepcion de base de datos para el modelo
class model_exception extends \Exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
