<?php
session_start();
require("src/autoload.php");

try {
	$di=new \app\dependency_injector;
	\tools\error_reporter\error_reporter_central::init(new \app\app_error_reporter($di), \app\config::get()->get_error_reporting(), \app\config::get()->get_display_errors());

	$router=new \app\router($di);
	$route_data=$router->solve();
	$controller=new $route_data->classname($di);

	if($controller->requires_authenticated_user() && null===$di->get_user()) {
		header('location: '.\app\tools::build_url('login'));
		die();
	}

	$response=call_user_func_array([$controller, $route_data->method_name], $route_data->params);

	//Check the return type...
	if(!is_object($response)) {
		throw new \Exception("Response for {$route_data->classname}::{$route_data->method_name} is not of the correct type");
	}

	if(!($response instanceof \app\response)) {
		throw new \Exception('Got response type '.get_class($response).', which is not of \app\response type');
	}

	die($response->output());
}
catch(\Exception $e) {
	//Lol.... Get the error_reporter get it.
	throw $e;
}
