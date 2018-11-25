<?php
namespace app;

class tmp_cleaner {

	const 					dir='tmp/';

	private					$now;
	private					$max_age;
	private					$total_scanned=0;
	private					$total_deleted=0;

	public function			__construct($_max_age=86400) {

		$this->max_age=$_max_age;
		$this->now=time();
	}

	public function			get_total_scanned() {

		return $this->total_scanned;
	}

	public function			get_total_deleted() {

		return $this->total_deleted;
	}

	public function			execute() {

		$tmp_path=\app\tools::build_path(self::dir);
		$files=array_diff(scandir($tmp_path), array('..', '.', '.htaccess'));

		$this->total_scanned=count($files);

		array_walk($files, [$this, 'process_file']);
	}

	private function		process_file($_filename) {

		$fullpath=\app\tools::build_path(self::dir.$_filename);
		$last_modified=\filemtime($fullpath);

		$diff=$this->now-$last_modified;
		if($diff >= $this->max_age) {
			unlink($fullpath);
			++$this->total_deleted;
		}
	}
}
