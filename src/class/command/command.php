<?php
namespace command;

//!Commands are simple, parametrisable actions that must be achieved from
//!different points in the application. These are tipically cron tasks
//!with parameters that should also be achievable from a command line.
abstract class command {

	//!Checks the input and executes the command. Returns \app\api_result.
	final public function			execute(array $_params) {

		$input_check=$this->check_input($_params);
		if(!($input_check instanceof \app\api_result)) {
			throw new \Exception('command check_input must return an instance of \app\api_result');
		}

		if($input_check->is_fail()) {
			return $input_check;
		}

		//TODO: Maybe turn params into an ordered array.
		return $this->execute_command($_params);
	}

	//!Checks the validity of the input expressed as a sequential array of
	//!parameters.

	//!The implementation will return a app\api_result class with result set
	//!get to _RES_SUCCESS if input can be correctly validated. In case of
	//!error it will return _RES_FAIL with message set to the error message.
	//!The body will not be used.
	protected abstract function		check_input(array $_params);

	//!Executes the underlying command.

	//!The implementation will return a app\api_result class with result set
	//!get to _RES_SUCCESS or _RES_FAIL and the message as needed. The body
	//!will contain any information needed by the caller. Parameters are not
	//!passed: the command is supposed to store any needed values when
	//!check_input is called.
	protected abstract function		execute_command();

	//!Return the interpreted result as a string.

	//!The implementation must return a string that summarises the result of
	//!The action, success or not.
	public abstract function		interpret_result(\app\api_result $_res);

	//!Returns a string with the command description.
	public abstract function		describe();

	//!Returns a string with the command help.
	public abstract function		help();
};
