<?php
namespace command;

class tmp_clean extends \command\command {

	const 							param_m=0;
	const 							param_m_val=1;

	const							err_params=0;

	private							$max_age;

	//TODO: Could have the time as a parameter...
	protected function			check_input(array $_params) {

		if(count($_params)!=2 || $_params[self::param_m]!='-m' || !is_numeric($_params[self::param_m_val])) {
			return \app\api_result::from_error(self::err_params);
		}

		$this->max_age=$_params[self::param_m_val];

		return \app\api_result::from_data([]);
	}

	protected function			execute_command() {

		$tmp_cleaner=new \app\tmp_cleaner($this->max_age);
		$tmp_cleaner->execute();

		return \app\api_result::from_data(['deleted' => $tmp_cleaner->get_total_deleted(),
				'scanned' => $tmp_cleaner->get_total_scanned()]);
	}

	public function			interpret_result(\app\api_result $_res) {

		if($_res->is_fail()) {
			switch($_res->get_message()) {
				case self::err_params: 	return $this->help();
				default:				return "unknown error";
			}
		}

		$data=$_res->get_json_body();
		return $data->scanned." files were scanned. ".$data->deleted." files were deleted";
	}

	public function			describe() {
		return "Removes the files in app/tmp exceeding a max age.";
	}

	public function			help() {
		return "command::tmp_clean -m #value".PHP_EOL." -m: max age in integer seconds";
	}

}
