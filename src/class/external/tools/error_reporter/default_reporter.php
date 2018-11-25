<?php
namespace tools\error_reporter;

class default_reporter implements error_reporter {

	public function report(error $_err) {

		$backtrace=nl2br(print_r($_err->get_backtrace(), true));

		echo <<<R
<html>
<body style="color: red">
{$_err->get_severity()} => "{$_err->get_message()}" ($_err->get_file(), $_err->get__line())
<br />
{$backtrace}
</body>
</html>
R;

	}

}
