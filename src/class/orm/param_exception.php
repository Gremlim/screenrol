<?php

namespace orm;
//! Excepcion para los parametros de la consulta
class param_exception extends \Exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
