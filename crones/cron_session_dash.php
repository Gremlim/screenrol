<?php
//Include path is set at the root.
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/..'));
require_once('src/autoload.php');

//NOTE: descomentar para ignorar el semaforo
// \tools\semaphore::debug_ignore_locked();

$S=new \tools\semaphore(\app\tools::build_path('tmp/session_dash'));
if($S->is_locked()){
	die('Cron detenido por semaforo activo.');
}

$data=\app\config::get()->get_database_data();

$dbconn=new \orm\db_connection($data->host,$data->user,$data->pass,$data->name);
$em=new \orm\entity_manager($dbconn);

\orm\model::inject_load_function(function ($_classname) {

	$class_cut=explode('\\',$_classname);
	$class = array_pop($class_cut);

	$filepath=\app\tools::build_path('src/class/'.implode('/',$class_cut).'/maps/'.$class.'.json');
	return json_decode(file_get_contents($filepath));
});


//Seleccionamos todas las sesiones no persistentes que tengan una inactividad
//superior a 30 min
$sesiones=$em->get_by_criteria(
	\model\app\session_user::class,
	new \orm\andgroup(
		new \orm\raw_param('last_activity',\orm\raw_param::lower_equal,'DATE_SUB(NOW(),INTERVAL 30 MINUTE)'),
		new \orm\param('persist',\orm\param::equal,false)
	)
);

while($sess=$sesiones->next()){
	$em->delete($sess);
}

//Seleccionamos todas las sesiones persistentes con mas de un año
$sesiones=$em->get_by_criteria(
	\model\app\session_user::class,
	new \orm\andgroup(
		new \orm\raw_param('fecha',\orm\raw_param::lower_equal,'DATE_SUB(NOW(),INTERVAL 365 DAY)'),
		new \orm\param('persist',\orm\param::equal,true)
	)
);

while($sess=$sesiones->next()){
	$em->delete($sess);
}
