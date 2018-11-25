<?php
namespace controller\other;

class login extends \controller\unauthenticated_controller {

    public function				__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);

	}

	public function requires_authenticated_user() {
		return false;
	}

	public function 			show_login($_error_code, $_username) {

		$request=$this->get_di()->get_request();

		if(null!==$this->get_di()->get_user() || (null==$_error_code && $request->has_cookie('sessid'))) {
			return $this->reply_with_redirection(\app\tools::build_url('index'),
				\app\response::status_code_303_see_other, []);
		}

        return $this->reply_with_document_view(
            $view=new \view\other\login($_error_code, $_username),
            [],
            \app\response::status_code_200_ok
        );
	}

	public function				show_recover_pass($_sent, $_msg) {

		return $this->reply_with_document_view(
			$view=new \view\other\login_recover_pass($_sent, $_msg),
			[],
			\app\response::status_code_200_ok
		);
	}

	public function				perform_pass_recovery($_email) {

		$email=trim($_email);
		$hash=self::generate_hash($email);

		try {
			$mail=new \app\mail;
			$mail->send_password_recovery($email, $hash);
			// log_dash('peticion_reseteo_pass',$email, "OK\n HASH: $hash");
			return $this->reply_with_redirection(
				\app\tools::build_url('recovery?sent=1'), \app\response::status_code_303_see_other, []);
		}
		catch(\Exception $e) {
			// log_dash('peticion_reseteo_pass',$email, "ERR\n HASH: $hash");
			return $this->reply_with_redirection(
				\app\tools::build_url('recovery?msg=2'), \app\response::status_code_303_see_other, []);
		}
	}

	public function				show_reset_pass($_email, $_hash, $_msg, $_sent) {

		//Check hash.
		$new_hash=self::generate_hash($_email);
		if($new_hash!=$_hash) {
			return $this->reply_with_redirection(
				\app\tools::build_url('recovery?msg=3'), \app\response::status_code_303_see_other, []);
		}

		return $this->reply_with_document_view(
			$view=new \view\other\login_reset_pass($_email, $_hash, $_msg, $_sent),
			[],
			\app\response::status_code_200_ok
		);
	}

	public function				perform_pass_change($_email, $_hash, $_pass, $_check_pass) {

		try {
			//Check hash.
			$new_hash=self::generate_hash($_email);
			if($new_hash!=$_hash) {
				throw new \Exception(null, 3);
			}

			//Check pass...
			if(!strlen($_pass) || $_pass!=$_check_pass) {
				throw new \Exception(null, 1);
			}

			//Get data and update.
			$em=$this->get_di()->get_em();
			$user=$em->get_one_by_field(\model\app\usuario::class,'mail',trim($_email));
			if(null===$user) {
				throw new \Exception(null, 4);
			}
			$user->set_password($_pass);
			$em->update($user);

			return $this->reply_with_redirection(
				\app\tools::build_url('reset?email='.$_email.'&seed='.$_hash.'&sent='.$user->get_usuario()), \app\response::status_code_303_see_other, []);
		}
		catch(\Exception $e) {

			$code=$e->getCode();
			//Lol...
			$url=$code < 3 ? 'reset?email='.$_email.'&seed='.$_hash.'&msg='.$code : 'recovery?msg='.$code;
			return $this->reply_with_redirection(
				\app\tools::build_url($url), \app\response::status_code_303_see_other, []);
		}
	}

	public function				do_login($_user, $_pass, $_remember) {


		$em=$this->get_di()->get_em();

		$em=$this->get_di()->get_em();
		$user=$em->get_one_by_criteria(
			\model\app\usuario::class,
			new \orm\andgroup(
				new \orm\param('user',\orm\param::equal,trim($_user)),
				new \orm\param('pass',\orm\param::equal,md5($_pass))
			)
		);

		if(null===$user) {
			return $this->reply_with_redirection(\app\tools::build_url('login?err=in&username='.$_user),
				\app\response::status_code_303_see_other, []);
		}

		$request=$this->get_di()->get_request();
		$PHPSSID=$request->cookie('PHPSESSID');

		if($_remember) {
			$request->set_cookie('sessid', $PHPSSID, (60*60*24*365), \app\config::get()->get_path_prefix().'/');
		}

		$user->set_ultconex(time());
		$em->update($user);

		if($PHPSSID){
			$sess=new \model\app\session_user;
			$sess->set_phpsessid($PHPSSID)
				->set_usuario($user)
				->set_fecha(date('Y-m-d H:i:s'))
				->set_last_activity(date('Y-m-d H:i:s'))
				->set_persist($_remember ? 1 : 0);
			$em->create($sess);
		}

		// log_dash('conectar',$user->get_usuario());

		return $this->reply_with_redirection(\app\tools::build_url('index'),
			\app\response::status_code_303_see_other, []);
	}

	public function				do_logout() {

		$request=$this->get_di()->get_request();

		if($request->has_cookie('sessid')) {
			$request->unset_cookie('sessid');
		}

		$em=$this->get_di()->get_em();
		$session=$em->get_one_by_field(\model\app\session_user::class,'phpsessid',$request->cookie('PHPSESSID'));
		if(null!==$session) {
			$em->delete($session);
		}

		return $this->reply_with_redirection('login', \app\response::status_code_303_see_other, []);
	}

	private static function		generate_hash($_email) {

		return md5($_email.'ySJ4n08w47');
	}
}
