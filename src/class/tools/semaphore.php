<?php
namespace tools;

class semaphore {

	private $filename;
	private $max_time;
	private $pid;

	private static $dev_ignore=false;

	//!Creates a semaphore.
	public function __construct($_filename,$_max_time=null) {

		$this->filename = $_filename;
		$this->max_time=$_max_time;
		$this->pid=uniqid().'-'.microtime();

		if(!self::$dev_ignore) {
			if(!$this->is_locked()){
		        if(false===file_put_contents($this->filename,$this->pid)) {
		            throw new \Exception('Fallo al crear el fichero del semaforo');
		        }
			}
		}
    }

	public function __destruct(){
		if(!$this->is_locked()){
			$this->clear();
		}
	}

	//!When called without arguments, checks if the semaphore ignore flag is set
	//!for the script. This flag will enable ignoring all the semaphore related
	//!calls: semaphores will not be created, removed, or checked. When called with
	//!an argument, will set the flag to the specified value. This function is
	//!useful to debug time-bound scripts.
	public static function debug_ignore_locked() {
        self::$dev_ignore=true;
	}

	//!Checks if a semaphore file locks. To lock, a semaphore file must at least
	//!exist. If we provide a $this->max_time (in seconds), the semaphore file must be
	//!younger than the time in seconds to lock.
	public function is_locked() {

		//Check first the global flag.
		if(!$this->exists()) {
			return false;
		}

        //If we have a max time we check against it.
		if(null!==$this->max_time) {
			$filetime=filemtime($this->filename);
			return time() - $filetime <= $this->max_time;
		}

		if($this->pid===file_get_contents($this->filename)){
			return false;
		}
		//If we didn't have a max time and the file exists, we consider it locked.
		return true;
	}

	//!Removes a semaphore.
	public function clear() {
		//Check first the global flag.

		if($this->exists()) {
			unlink($this->filename);
		}
	}
	//!Returns true if a semaphore file exists.
	private function exists() {

		//Check first the global flag.
		if(self::$dev_ignore) {
			return false;
		}

		return file_exists($this->filename);
	}
}
