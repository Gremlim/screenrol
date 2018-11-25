<?php
namespace app;

class router_exception extends \Exception {
	public function __construct($_msg) {
		parent::__construct($_msg);
	}
}
