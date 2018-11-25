<?php
namespace orm;

class orgroup extends group{
	//! Le devolvemos el valor de union para el grupo con el resultado 'OR'
	protected function get_union(){
		return ' OR ';
	}
}
