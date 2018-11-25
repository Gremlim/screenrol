<?php
namespace app;

class config {

	private static $instance=null;

	public static function get() {

		if(!self::$instance) {
			self::$instance=new config();
		}

		return self::$instance;
	}

	////////////////////////////////////////////////////////////////////////////

	private $config_data=null;

	public function get_stage() {
		return $this->config_data->app->stage;
	}

	public function get_app_url() {
		return $this->config_data->app->url;
	}
	public function get_path_prefix() {
		return $this->config_data->app->path_prefix;
	}

	public function get_app_path() {
		return $this->config_data->app->path;
	}

	public function get_database_data() {
		return $this->config_data->databases->screenrol;
	}

	public function get_path_tmp() {
		return $this->config_data->app->path_tmp;
	}

	public function	get_error_reporting() {
		return $this->config_data->app->error_reporting;
	}

	public function	get_display_errors() {
		return $this->config_data->app->display_errors;
	}

	public function get_mail_data_admin() {
		return $this->config_data->mail->mail_admin;
	}

	private function __construct() {
		
		try {
			//The configuration path is always relative to this file. This is weak,
			//but at least it is guaranteed to work wherever we are.
			$file_path=realpath(__DIR__.'/../../conf/config.json');
			$this->config_data=\tools\json::from_file($file_path);
		}
		catch(\Exception $e) {
			// mail('gremlim@gremlim.com', 'error in file_path for app\config', $e->getMessage());
			throw $e;
		}
	}
};
