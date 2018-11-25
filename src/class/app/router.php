<?php
namespace app;

class router {

	private 				$di;

	public function 		__construct(\app\dependency_injector $_di) {

		$this->di=$_di;
	}

	public function			solve() {

		$request=$this->di->get_request();

		//TODO: Perhaps we could separate in many files.
		$json=json_decode(file_get_contents(\app\tools::build_path('src/conf/router.json')));
		$patterns=[];
		if(property_exists($json,'patterns')){
			foreach($json->patterns as $v){
				$patterns[]=\tools\pattern_matcher\matcher::pattern_from_json($v);
			}
		}

		if(property_exists($json,'files')){
			foreach($json->files as $v){
				if(!property_exists($v, "file")) {
					throw new router_exception("El router no contiene una propiedad 'file' válida");
				}
				$patt=\tools\pattern_matcher\matcher::patterns_from_file(\app\tools::build_path($v->file));
				$patterns=array_merge($patterns,$patt);
			}
		}

		$fp=\tools\pattern_matcher\matcher::from_patterns($patterns);
		$path=\app\tools::normalize_script_path($request->get_uri_without_query_string());
		$match=$fp->match($path);

		$controller_name=null;

		if(!$match->is_match()) {
			return new router_result(\app\tools::classname_resolver("controller::other::not_found"), 'execute', []);
		}

		$controller_name=$match->get_name();
		if(!class_exists(\app\tools::classname_resolver($controller_name))) {
			return new router_result(\app\tools::classname_resolver("controller::other::not_found"), 'execute', []);
		}

		//Check permissions...
		$profile=null!==$this->di->get_user() ? $this->di->get_user()->get_permisos() : null;
		if(!\app\path_authority::check($controller_name, $profile)) {
			return new router_result(\app\tools::classname_resolver("controller::other::forbidden"), 'execute', []);
		}

		//Resolve and return.
		$classname=\app\tools::classname_resolver($controller_name);
		$method=self::get_method_from_match($match);
		try {
			$params=self::get_params_from_match($match, $request);
		}
		catch(\Exception $e) {	//Adding new information to the message...
			throw new router_exception($e->getMessage().' for '.$classname.'::'.$method);
		}

		$result=new router_result($classname, $method, $params);
		$result->check();
		return $result;
	}

	private static function			get_method_from_match(\tools\pattern_matcher\result $_res) {

		$metadata=$_res->get_metadata();

		if(!property_exists($metadata, 'method')) {
			throw new router_exception("Malformed route for controller ".$controller_name);
		}

		return $metadata->method;
	}

	private static function			get_params_from_match(\tools\pattern_matcher\result $_res, \request\request $_request) {

		$metadata=$_res->get_metadata();
		if(!property_exists($metadata, 'params') || !is_array($metadata->params)) {
			throw new router_exception("Malformed params for controller");
		}

		$result=array();

		foreach($metadata->params as $k => $param) {

			if(!property_exists($param, 'name')) {
				throw new router_exception('all parameters must provide a name');
			}

			$optional=property_exists($param, 'optional') && true===$param->optional;

			$default=null;
			if($optional) {
				if(!property_exists($param, 'default')) {
					throw new router_exception('optional parameter '.$param->name.' must provide a default value');
				}
				$default=$param->default;
			}

			if(!property_exists($param, 'source')) {
				throw new router_exception('source property does not exist for '.$param->name);
			}

			switch($param->source) {
				case 'body': 	$result[]=self::get_body_param($_request, $param, $optional, $default);	break;
				case 'query':	$result[]=self::get_query_param($_request, $param, $optional, $default);break;
				case 'param':	$result[]=self::get_param_param($_res, $param, $optional, $default);	break;
				case 'raw':		$result[]=self::get_raw_param($_request, $param, $optional, $default); break;
				case 'file':	$result[]=self::get_file_param($_request, $param, $optional); break;
				default:
					throw new router_exception('unknown parameter source '.$param->source.' in name '.$param->name);
				break;
			}
		}

		return $result;
	}

	private static function get_body_param(\request\request $_request, $_param, $_optional, $_default) {

		if(!$_optional && !$_request->has_body($_param->name)) {
			throw new router_exception('body '.$_param->name.' is not optional, but is not present');
		}
		return $_request->body($_param->name, $_default);
	}

	// Esta funcion no incluye el default ya que si no existe siempre se va a tomar
	// por defecto null
	private static function get_file_param(\request\request $_request, $_param, $_optional) {

		if(!$_optional && !$_request->has_file($_param->name)) {
			throw new router_exception('file '.$_param->name.' is not optional, but is not present');
		}

		return $_request->file($_param->name);
	}

	private static function get_query_param(\request\request $_request, $_param, $_optional, $_default) {

		if(!$_optional && !$_request->has_query($_param->name)) {
			throw new router_exception('query '.$_param->name.' is not optional, but is not present');
		}
		return $_request->query($_param->name, $_default);
	}

	private static function get_param_param($_res, $_param, $_optional, $_default) {

		$exists=$_res->has_parameter($_param->name);
		if(!$_optional && !$exists) {
			throw new router_exception('route param '.$_param->name.' is not optional, but is not present');
		}
		return $exists ? $_res->get_parameter($_param->name)->get_value() : $_default;
	}

	const raw_param_type_json='json';
	const raw_param_type_string='string';

	private static function get_raw_param(\request\request $_request, $_param, $_optional, $_default) {

		$body=$_request->get_body();
		$exists=strlen($body);

		if(!property_exists($_param, "type")) {
			throw new router_exception('raw param '.$_param->name.' must provide a type');
		}

		if(!$_optional && !$exists) {
			throw new router_exception('raw param '.$_param->name.' is not optional, but is not present or has no length');
		}

		$raw=$exists ? $body : $_default;

		switch($_param->type) {
			case self::raw_param_type_json:
				$res=json_decode($raw);
				if(false===$res) {
					throw new router_exception('raw param '.$_param->name.' received invalid JSON data');
				}
				return $res;
			break;
			case self::raw_param_type_string:
				return (string)$raw;
			break;
			default:
				throw new router_exception('raw param '.$_param->name.' did not provide a valid type');
		}
	}

}
