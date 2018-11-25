<?php
namespace orm;

interface resolve_to_sql{

	public function make_sql_string();
	public function bind_value(\PDOStatement $_stmt);
}
