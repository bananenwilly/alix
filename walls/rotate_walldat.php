<?php
$oldest_needed_walls="172800"; #24h in s
$walls_max_file_size="25"; #mb

$now=time();
$oldest_timestamp_needed=$now-$oldest_needed_walls;
$walls_file="data/wall_data.dat";
$walls_rotate_file="data/archive/wall_data_$now.dat";
$walls_file_size_byte=filesize($walls_file);
$walls_file_size_mb=$walls_file_size_byte/1048576;

if ($walls_file_size_mb>$walls_max_file_size)
{
	$walls_file_data=file($walls_file); #read file

	foreach($walls_file_data as $json_line) #go through file
	{
		$json_line_decode=json_decode($json_line, true);
		$timestamp=$json_line_decode["timestamp"];
		
		if($timestamp<$oldest_timestamp_needed)
		{
			file_put_contents($walls_rotate_file, $json_line, FILE_APPEND | LOCK_EX);
		}
		if($timestamp>=$oldest_timestamp_needed)
		{
			file_put_contents("$walls_file-temp", $json_line, FILE_APPEND | LOCK_EX);
		}
	}
copy("$walls_file-temp", $walls_file); #overwrite old file with temp file
unlink("$walls_file-temp"); #delete temp file
}
?>
