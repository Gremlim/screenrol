<?php

namespace orm;

//! Excepcion de base de datos para los delete
class db_delete_exception extends entity_manager_exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
