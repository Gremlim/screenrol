<?php
namespace tools\error_reporter;

interface error_reporter {
	public function report(error $_err);
};
