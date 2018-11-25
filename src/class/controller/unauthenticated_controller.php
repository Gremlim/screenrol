<?php
namespace controller;

abstract class unauthenticated_controller extends controller {

	public function requires_authenticated_user() {

		return false;
	}
}
