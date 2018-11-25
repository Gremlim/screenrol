<?php
//Include path is set at the root.
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/..'));
require_once("src/autoload.php");

$interpreter=new \command\interpreter;
$interpreter->execute('command::tmp_clean', ['-m', 10]);
echo $interpreter->last_result();
