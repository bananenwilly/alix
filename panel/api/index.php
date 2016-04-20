<?php

//default message
$message=array('message' => 'unknown request');
$message=json_encode($message); //make json

//what's requested?
if(isset($_GET["custodians_10k"]))
{
	$path = "/var/www/alix/panel/data/custodians_last_10k.dat";
	$message = file_get_contents($path);

	while(!$message) {
		$message = file_get_contents($path);
		sleep(1);
	}
}

if(isset($_GET["custodians_100"]))
{
	$path = "/var/www/alix/panel/data/custodians_last_100.dat";
	$message = file_get_contents($path);

	while(!$message) {
		$message = file_get_contents($path);
		sleep(1);
	}
}

if(isset($_GET["motions_10k"]))
{
	$path = "/var/www/alix/panel/data/motions_last_10k.dat";
	$message = file_get_contents($path);

	while(!$message) {
		$message = file_get_contents($path);
		sleep(1);
	}
}

if(isset($_GET["motions_100"]))
{
	$path = "/var/www/alix/panel/data/motions_last_100.dat";
	$message = file_get_contents($path);

	while(!$message) {
		$message = file_get_contents($path);
		sleep(1);
	}
}

if(isset($_GET["elected_custodians"]))
{
	$path = "/var/www/alix/panel/data/elected_custodians.dat";
	$message = file_get_contents($path);

	while(!$message) {
		$message = file_get_contents($path);
		sleep(1);
	}
}



//print
print_r($message);

?>