<?php
namespace command;

class interpreter {

	private			$last_result=null;
	private			$last_command=null;
	private			$show_error;

	public function execute($_cmd_path, array $_params) {

		$class=\app\tools::classname_resolver($_cmd_path);
		if(!class_exists($class)) {
			$this->show_error="Command interpreter could not find command with path ".$_cmd_path.PHP_EOL;
			return;
		}

		$command=new $class;
		if(!($command instanceof \command\command)) {
			$this->show_error="Command with path ".$_cmd_path." is not a \command\command".PHP_EOL;
			return;
		}

		try {
				$this->last_command=$command;
				$this->last_result=$command->execute($_params);
				return $this->last_result;
		}
		catch(\Exception $e) {
			$this->show_error="Error in ".$_cmd_path." : ".$e->getMessage().PHP_EOL;
		}
	}

	public function last_result() {

		if(null===$this->last_command) {
			echo $this->show_error;
			//throw new \Exception("Command interpreter could not find last result");
		}
		else if(null===$this->last_result) {
			echo $this->show_error;
		}
		else {
			return $this->last_command->interpret_result($this->last_result).PHP_EOL;
		}
	}
}
