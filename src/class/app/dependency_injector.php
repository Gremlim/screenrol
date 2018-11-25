<?php
namespace app;

class dependency_injector {

	private $request=null;
	private $server=null;

	private $em=null;
	private $em_facturacion=null;
	private $em_incidencias=null;
	private $em_click=null;
	private $em_mailchimp=null;
	private $em_precios=null;


	public function __construct() {
		$this->request=\app\app_request::factory(\request\request_factory::from_apache_request());
		$this->server=new \app\server($_SERVER);
	}

	public function get_request() {
		return $this->request;
	}

	public function get_server() {
		return clone($this->server);
	}
	public function get_user(){
		$em=$this->get_em();
		if($this->request->cookie('sessid')){
			$session=$em->get_one_by_criteria(
				\model\app\session_user::class,
				new \orm\andgroup(
					new \orm\param('phpsessid',\orm\param::equal,$this->request->cookie('sessid')),
					new \orm\param('persist',\orm\param::equal,true)
				)
			);

			if(null===$session){
				$this->request->unset_cookie('sessid');
			}else{
				if( null!==$this->request->cookie('PHPSESSID') && $this->request->cookie('PHPSESSID')!=$this->request->cookie('sessid')){
					$session->set_phpsessid($this->request->cookie('PHPSESSID'));
					$this->request->set_cookie('sessid',$this->request->cookie('PHPSESSID'),(60*60*24*365), \app\config::get()->get_path_prefix().'/');
				}

				$session->set_last_activity(date('Y-m-d H:i:s'));
				$em->update($session);

				return $session->get_usuario();
			}
		}


		if(!$this->request->has_cookie('PHPSESSID')){
			return null;
		}

		$session=$em->get_one_by_criteria(
			\model\app\session_user::class,
			new \orm\andgroup(
				new \orm\param('phpsessid',\orm\param::equal,$this->request->cookie('PHPSESSID'))
			)
		);

		if(null!==$session){
			$session->set_last_activity(date('Y-m-d H:i:s'));
			$em->update($session);

			return $session->get_usuario();
		}

		return null;
	}
	public function get_em(){
		return self::create_em(\app\config::get()->get_database_data(),$this->em);
	}

	private static function create_em($_data,&$_em){
		if(null===$_em){
			$dbconn=new \orm\db_connection($_data->host,$_data->user,$_data->pass,$_data->name);
			$dbconn->conn->exec('SET SESSION wait_timeout=360;');
			$_em=new \orm\entity_manager($dbconn);

			\orm\model::inject_load_function(function ($_classname) {

				$class_cut=explode('\\',$_classname);
				$class = array_pop($class_cut);

				$filepath=\app\tools::build_path('src/class/'.implode('/',$class_cut).'/maps/'.$class.'.json');
				return json_decode(file_get_contents($filepath));
			});
		}

		return $_em;
	}
}
