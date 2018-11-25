<?php

namespace orm;

//! Conexion a la base de datos por PDO

//!Necesita que se le envie como parametros host, user, password y nombre de la
//!base de datos.
class db_connection{

    public $conn; //!< Conexion final a la base de datos que se quedará como publica para poder utilizarla

	//! Se realiza la conexion con PDO a la base de datos con los parametros recibidos
	public function __construct($_host,$_user,$_pass,$_dbname){
		$conn_string="mysql:host=".$_host.";charset=utf8";
		$this->conn=new \PDO($conn_string,$_user,$_pass);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn->exec("USE ".$_dbname);
    }

	//! Al destruirse se elimina todo rastro de la conexion PDO
    public function __destruct(){

		$this->conn=null;
    }
}
