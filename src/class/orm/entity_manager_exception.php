<?php

namespace orm;

//! Excepcion para el entity manager
class entity_manager_exception extends \Exception{
    public function __construct($fail_str){
        parent::__construct($fail_str);
    }
}
