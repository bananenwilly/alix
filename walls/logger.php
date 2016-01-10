<?php

function writelog ($pair, $type, $content)
{
	date_default_timezone_set('UTC');
	$filename="logs/error.log";
	$time = time();
	$timestamp = date("d.m.Y - H:i:s");
	$error_string="$time - $timestamp UTC - $pair - $type - $content \n";
	
	file_put_contents($filename, $error_string, FILE_APPEND | LOCK_EX);
}

?>