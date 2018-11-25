<?php

namespace orm;
//! Excepcion de base de datos para los update
class db_update_exception extends entity_manager_exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
