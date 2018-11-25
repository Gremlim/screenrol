<?php
namespace app;

class exception extends \Exception {
	public function __construct($_msg) {
		parent::__construct($_msg);
	}
}
