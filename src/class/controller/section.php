<?php
namespace controller;

//!Base controller for all sections, which is ANYTHING between the head and
//!footer of the site.
abstract class section extends \controller\controller {

	public function				 __construct(\app\dependency_injector $_di) {

	       parent::__construct($_di);
	}
}
