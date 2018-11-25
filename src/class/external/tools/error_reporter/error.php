<?php
namespace tools\error_reporter;

class error {

	private 					$severity;
	private						$message;
	private						$file;
	private						$line;
	private						$backtrace;

	public function 			__construct($_err_severity, $_err_message, $_err_file, $_err_line, array $_backtrace) {

		$this->severity=$_err_severity;
		$this->message=$_err_message;
		$this->file=$_err_file;
		$this->line=$_err_line;
		$this->backtrace=$_backtrace;
	}

	public function				get_severity() {

		return $this->severity;
	}

	public function				get_message() {

		return $this->message;
	}

	public function				get_file() {

		return $this->file;
	}

	public function				get_line() {

		return $this->line;
	}

	public function				get_backtrace() {

		return $this->backtrace;
	}

}
