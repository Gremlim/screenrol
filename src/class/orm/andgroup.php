<?php
namespace orm;

class andgroup extends group{
	//! Le devolvemos el valor de union para el grupo con el resultado 'AND'
	protected function get_union(){
		return ' AND ';
	}
}
