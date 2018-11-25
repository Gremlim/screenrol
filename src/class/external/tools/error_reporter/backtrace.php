<?php
namespace tools\error_reporter;

class backtrace {

	private					$index; //!<Index on which it appears.
	private					$file;
	private					$line;
	private					$function;

	public static function	from_array_and_key(array $_d, $_key) {

		$file=isset($_d['file']) ? $_d['file'] : 'no file';
		$line=isset($_d['line']) ? $_d['line'] : 'no line';
		$function=isset($_d['function']) ? $_d['function'] : 'unknown';

		return new backtrace($_key, $file, $line, $function);
	}

	public function			get_index() {

		return $this->index;
	}

	public function			get_file() {

		return $this->file;
	}

	public function			get_line() {

		return $this->line;
	}

	public function			get_function() {

		return $this->function;
	}

	private function			__construct($_i, $_f, $_l, $_func) {

		$this->index=$_i;
		$this->file=$_f;
		$this->line=$_l;
		$this->function=$_func;
	}
}
