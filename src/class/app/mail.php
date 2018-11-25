<?php
namespace app;

class mail {

	public function 		send_mail($_message, $_subject, array $_mail_list, array $_bcc_list, $_attachment=null) {

		try {
			$mail=new \PHPMail(true);
			$maildata=\app\config::get()->get_database_data();

			//Setup mail...
			$mail->SetFrom($maildata->user, 'Screenrol');
			$mail->addReplyTo('soporte@screenrol.es');
			$mail->AddCustomHeader('X-Mailer', 'PHP/'.phpversion());

			//The first one goes into addadress... The rest into CC. The BCC into BCC.
			$mail->AddAddress(array_shift($_mail_list));
			array_walk($_mail_list, function($_item) use (&$mail) {if(trim($_item)){$mail->AddCC($_item);}});
			array_walk($_bcc_list, function($_item) use (&$mail) {if(trim($_item)){$mail->AddBCC($_item);}});

			$mail->CharSet="UTF-8";
			$mail->msgHTML(self::build_standard_email_body($_message, $_subject));
			$mail->Subject=$_subject;

			if(null!==$_attachment){
				$mail->AddAttachment($_attachment,basename($_attachment));
			}

			return $mail->Send();

		}
		catch(\phpmailerException $e) {
			throw new \app\exception("failure to send contratos issue : ".$e->getMessage());
		}
	}

	public function			send_password_recovery($_email, $_hash) {

		$mail=new \PHPMail(true);
		$maildata=\app\config::get()->get_mail_data_admin();

		//Setup mail...
		$mail->SetFrom($maildata->user, $maildata->name);
		$mail->AddCustomHeader('X-Mailer', 'PHP/'.phpversion());
		$mail->AddAddress($_email);

		$restore_url=\app\config::get()->get_app_url()."reset_pass.php?seed={$_hash}&email={$_email}";

		$subject="Recuperación de contraseña";
		$message=<<<R
<p>Este correo se ha mandado porque ha habido una petición de creación o modificación de contraseña.</p>
<p>Si usted no ha solicitado este reestablecimiento puede ignorar este mensaje con tranquilidad.</p>
<p>Si por el contrario usted lo solicitó y deseea que sea efectivo debe entrar en la siguiente dirección web:</p>
<p>{$restore_url} o clickando en <a href="{$restore_url}">en este enlace</a></p>
<p>Una vez ingrese Podra obtener una contraseña proporcionando una serie de datos.</p>
<p>Muchas Gracias.</p>
R;

		$mail->CharSet="UTF-8";

		$mail->msgHTML(self::build_standard_email_body($message, $subject));
		$mail->Subject=$subject;
		$mail->Send();
	}

	

	//!Builds the email body with the associated contents and title. Contents
	//!are not processed at all in this function.
	private static function	build_standard_email_body($_contents, $_title=null) {

		$logo_path=\app\config::get()->get_app_path();

		$view_title=null!==$_title ? '<h1>'.$_title.'</h1>' : null;

		return <<<R
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			html {font-family: Helvetica, Arial; font-size: 1.2em;}
			body {background-color: #fff; color: #666;}
			p {margin-bottom: 1.5em; line-height: 1.6em; text-align: justify;}
			a {text-decoration: none; color: #42bcf4; font-weight: bold;}
			a:hover {text-decoration: underline;}
			#header {width: 100%; background-color: #666; text-align: center; padding: 1em 2em;}
			#body {width: 800px; margin: 1em auto; padding: 1em 0em;}
			#body h1 {font-size: 1.2em;}
			#footer {width: 100%; background-color: #666; text-align: center; padding: 1em 2em 1em 0em; color: #fff;}
			#footer p {text-align: center; font-size: 0.6em;}
		</style>
	</head>
	<body>
		<div id="header">
			<img src="{$logo_path}assets/img/logo_screenrol.png" alt='Screenrol' title='Screenrol'>
		</div>
		<div id="body">
			{$view_title}
			{$_contents}
		</div>
		<div id="footer">
			<p>Copyright © 2018 Screenrol, All rights reserved.</p>
		</div>
	</body>
</html>
R;
	}

}
