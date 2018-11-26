<?php
die('nope');
$mysql=new mysqli('localhost','root','','screenrol');


$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_aventura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `img` mediumtext NOT NULL,
  `idespecial` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `publica` int(11) NOT NULL,
  `vel_subida` int(5) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` int(11) NOT NULL,
  `idcreatedby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_capitulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idaventura` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_encuentros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idepisodio` int(11) NOT NULL,
  `monstruo` varchar(255) NOT NULL,
  `xp` int(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `secundario` int(11) NOT NULL,
  `idsesion` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_episodio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcapitulo` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_jugador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `idaventura` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_pj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idjugador` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `raza` varchar(255) NOT NULL,
  `clase` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL COMMENT '0=Activo | 1=Muerto |  2=Abandonado | 3=Otro',
  `sexo` enum('H','M','?') NOT NULL DEFAULT '?',
  `img` mediumtext NOT NULL,
  `xpactual` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_pj_sesion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idpj` int(11) NOT NULL,
  `idsesion` int(11) NOT NULL,
  `xp` int(50) NOT NULL COMMENT 'XP base +XP extra',
  `jugado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_sesion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idaventura` int(11) NOT NULL,
  `porcen_no_jug` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("CREATE TABLE IF NOT EXISTS `pf_tesoro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idepisodio` int(11) NOT NULL,
  `tesoro` varchar(255) NOT NULL,
  `ppt` int(11) NOT NULL,
  `po` int(11) NOT NULL,
  `pp` int(11) NOT NULL,
  `pc` int(11) NOT NULL,
  `libro` varchar(255) NOT NULL,
  `pagina` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `idsesion` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$qry=$mysql->query("TRUNCATE `pf_aventura`;");
$qry=$mysql->query("TRUNCATE `pf_capitulo`;");
$qry=$mysql->query("TRUNCATE `pf_encuentros`;");
$qry=$mysql->query("TRUNCATE `pf_episodio`;");
$qry=$mysql->query("TRUNCATE `pf_jugador`;");
$qry=$mysql->query("TRUNCATE `pf_pj`;");
$qry=$mysql->query("TRUNCATE `pf_pj_sesion`;");
$qry=$mysql->query("TRUNCATE `pf_sesion`;");
$qry=$mysql->query("TRUNCATE `pf_tesoro`;");


$qry=$mysql->query("SELECT * FROM gr_instancia");

// ################################# AVENTURA ############################################
while($instancia=$qry->fetch_assoc()){
	$qryAv=$mysql->query("SELECT * FROM gr_aventura WHERE id=".$instancia['idaventura']);
	$aventura=$qryAv->fetch_assoc();
	foreach($aventura as $k=>$v){ $aventura[$k]=utf8_decode($v);}	
	
	$date = new DateTime();
	$date->setTimestamp($instancia['fecha']);

	$fecha = $date->format('Y-m-d H:i:s');
	$qryAv=$mysql->query(<<<R
		INSERT INTO pf_aventura (id,nombre,img,idespecial,idusuario,descripcion,publica,vel_subida,fecha,estado,idcreatedby) VALUES (
			NULL,
			'{$aventura['nombre']}',
			'{$aventura['img']}',
			'{$aventura['idespecial']}',
			'{$instancia['idusuario']}',
			'{$aventura['descripcion']}',
			'{$aventura['publica']}',
			'{$aventura['vel_subida']}',
			'{$fecha}',
			'{$instancia['estado']}',
			'{$aventura['idusuario']}'
		)
R
	);
	echo $mysql->errno ? $mysql->error.PHP_EOL : null;
	$idAventura=$mysql->insert_id;

	// ################################# SESIONES ############################################
	$sesiones=[];
	$qrySes=$mysql->query("SELECT * FROM gr_sesion WHERE idinstancia=".$instancia['id']);
	while($sesion=$qrySes->fetch_assoc()){
		foreach($sesion as $k=>$v){ $sesion[$k]=utf8_decode($v);}	
		$date = new DateTime();
		$date->setTimestamp($sesion['fecha']);
		$fecha = $date->format('Y-m-d H:i:s');
		$mysql->query(<<<R
			INSERT INTO pf_sesion (id,idaventura,porcen_no_jug,nombre,fecha) VALUES (
				NULL,
				'{$idAventura}',
				'{$sesion['porcen_no_jug']}',
				'{$sesion['nombre']}',
				'{$fecha}'
			)
R
		);		
		echo $mysql->errno ? $mysql->error.PHP_EOL : null;
		$sesiones[$sesion['id']]=$mysql->insert_id;
	}

	// ################################# JUGADORES ############################################
	
	$qryAv=$mysql->query("SELECT * FROM gr_jugador WHERE idinstancia=".$instancia['id']);
	while($jugador=$qryAv->fetch_assoc()){
		$mysql->query(<<<R
			INSERT INTO pf_jugador (id,idusuario,estado,idaventura) VALUES (
				NULL,
				0,
				'{$jugador['estado']}',
				'{$idAventura}'
			)
R
		);	
		echo $mysql->errno ? $mysql->error.PHP_EOL : null;	
		$idJugador=$mysql->insert_id;
		
		// ################################# PJ ############################################
		$qryJug=$mysql->query("SELECT * FROM gr_pj WHERE idjugador=".$jugador['id']);
		while($pj=$qryJug->fetch_assoc()){
			foreach($pj as $k=>$v){ $pj[$k]=utf8_decode($v);}
			$mysql->query(<<<R
				INSERT INTO pf_pj (id,idjugador,nombre,raza,clase,estado,sexo,img,xpactual) VALUES (
					NULL,
					{$idJugador},
					'{$pj['nombre']}',
					'{$pj['raza']}',
					'{$pj['clase']}',
					'{$pj['estado']}',
					'{$pj['sexo']}',
					'{$pj['img']}',
					'{$pj['xpactual']}'
				)
R
			);		
			echo $mysql->errno ? $mysql->error.PHP_EOL : null;
			$idPJ=$mysql->insert_id;
			
			// ################################# PJ ############################################
			$qryPj=$mysql->query("SELECT * FROM gr_pj_sesion WHERE idpj=".$pj['id']);
			while($pjses=$qryPj->fetch_assoc()){
				$sesid=isset($sesiones[$pjses['idsesion']]) ? $sesiones[$pjses['idsesion']] : 0;
				$mysql->query(<<<R
					INSERT INTO pf_pj_sesion (id,idpj,idsesion,xp,jugado) VALUES (
						NULL,
						{$idPJ},
						'{$sesid}',
						'{$pjses['xp']}',
						'{$pjses['jugado']}'
					)
R
				);		
				echo $mysql->errno ? $mysql->error.PHP_EOL : null;
			}
		}
	}
	


	// ################################# CAPITULO ############################################
	$qryAv=$mysql->query("SELECT * FROM gr_capitulo WHERE idaventura=".$aventura['id']);
	while($capitulo=$qryAv->fetch_assoc()){
		foreach($capitulo as $k=>$v){ $capitulo[$k]=utf8_decode($v);}
		$qryCp=$mysql->query(<<<R
			INSERT INTO pf_capitulo (id,idaventura,nombre,num) VALUES (
				NULL,
				'{$idAventura}',
				'{$capitulo['nombre']}',
				'{$capitulo['num']}'
			)
R
		);		
		echo $mysql->errno ? $mysql->error.PHP_EOL : null;
		
		$idCapitulo=$mysql->insert_id;

		// ################################# EPISODIO ############################################

		$qryCp=$mysql->query("SELECT * FROM gr_episodio WHERE idcapitulo=".$capitulo['id']);
		while($episodio=$qryCp->fetch_assoc()){
			foreach($episodio as $k=>$v){ $episodio[$k]=utf8_decode($v);}
			$qryEp=$mysql->query(<<<R
				INSERT INTO pf_episodio (id,idcapitulo,nombre,num) VALUES (
					NULL,
					'{$idCapitulo}',
					'{$episodio['nombre']}',
					'{$episodio['num']}'
				)
R
			);		
			echo $mysql->errno ? $mysql->error.PHP_EOL : null;
			$idEpisodio=$mysql->insert_id;

			// ################################# ENCUENTROS ############################################

			$qryEp=$mysql->query("SELECT * FROM gr_encuentros WHERE idepisodio=".$episodio['id']." AND (idinstancia=0 OR idinstancia=".$instancia['id'].")");
			while($encuentro=$qryEp->fetch_assoc()){
				foreach($encuentro as $k=>$v){ $encuentro[$k]=utf8_decode($v);}
				$inst=$mysql->query("SELECT * FROM gr_inst_encuentro WHERE idinstancia=$instancia[id] AND idencuentro=$encuentro[id]")->fetch_assoc();
				$sesid=isset($sesiones[$inst['idsesion']]) ? $sesiones[$inst['idsesion']] : 0;
				$estado=isset($inst['estado']) ? $inst['estado'] : 0;
				$mysql->query(<<<R
					INSERT INTO pf_encuentros (id,idepisodio,monstruo,xp,cantidad,secundario,idsesion,estado) VALUES (
						NULL,
						'{$idEpisodio}',
						'{$encuentro['monstruo']}',
						'{$encuentro['xp']}',
						'{$encuentro['cantidad']}',
						'{$encuentro['secundario']}',
						'{$sesid}',
						'{$estado}'
					)
R
				);	
				echo $mysql->errno ? $mysql->error.PHP_EOL : null;	
			}

			// ################################# TESOROS ############################################

			$qryEp=$mysql->query("SELECT * FROM gr_tesoro WHERE idepisodio=".$episodio['id']." AND (idinstancia=0 OR idinstancia=".$instancia['id'].")");
			while($tesoro=$qryEp->fetch_assoc()){
				foreach($tesoro as $k=>$v){ $tesoro[$k]=utf8_decode($v);}
				$inst=$mysql->query("SELECT * FROM gr_inst_tesoro WHERE idinstancia=$instancia[id] AND idtesoro=$tesoro[id]")->fetch_assoc();
				$sesid=isset($sesiones[$inst['idsesion']]) ? $sesiones[$inst['idsesion']] : 0;
				$estado=isset($inst['estado']) ? $inst['estado'] : 0;
				$mysql->query(<<<R
					INSERT INTO pf_tesoro (id,idepisodio,tesoro,ppt,po,pp,pc,libro,pagina,cantidad,idsesion,estado) VALUES (
						NULL,
						'{$idEpisodio}',
						'{$tesoro['tesoro']}',
						'{$tesoro['ppt']}',
						'{$tesoro['po']}',
						'{$tesoro['pp']}',
						'{$tesoro['pc']}',
						'{$tesoro['libro']}',
						'{$tesoro['pagina']}',
						'{$tesoro['cantidad']}',
						'{$sesid}',
						'{$estado}'
					)
R
				);	
				echo $mysql->errno ? $mysql->error.PHP_EOL : null;
			}


		}
	}
}