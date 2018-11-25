<?php

namespace orm;

//! Excepcion de base de datos para los create
class db_create_exception extends entity_manager_exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
