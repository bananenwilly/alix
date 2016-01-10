<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include("pairs.php");
include("config.php");

#write available pairs
$answer = array(
'error' => 'false', 
'available' => $pairs_available
);
$answer_json=json_encode($answer);

copy($pairs_available_file, "$pairs_available_file-temp");
file_put_contents("$pairs_available_file-temp", $answer_json, LOCK_EX);
copy("$pairs_available_file-temp", $pairs_available_file);
unlink("$pairs_available_file-temp");

#write available frames
$answer = array(
'error' => 'false',
'available' => $frames_available
);
$answer_json=json_encode($answer);
copy($frames_available_file, "$frames_available_file-temp");
file_put_contents("$frames_available_file-temp", $answer_json, LOCK_EX);
copy("$frames_available_file-temp", $frames_available_file);
unlink("$frames_available_file-temp");

#write available data with frames
$array=file_get_contents($data_file);

while(!$array)
{
$array=file_get_contents($data_file);
sleep(1);
}

$array = explode("\n", $array);
$array = array_filter($array);
$array_all = array();

foreach ($pairs_available as $query_pair) 
{
$query_pair=$query_pair["pair"];
$pair_array=array();
$search_pair=array_search($query_pair, array_column($pairs_available, 'pair'));

	foreach ($frames_available as $query_frame)
	{
	$search_frame=array_search($query_frame, $frames_available);
	$pair_amount=0;
	$data_count=0;

		foreach ($array as $json_line)
		{
			$json_array=json_decode($json_line, true);
			foreach($json_array["exchanges"] as $pairs)	
				{
					if ($pairs["pair"]==$query_pair)
	 					{
	 						$timeframe=(time()-$search_frame); 	 		
					 		if ($json_array["timestamp"] > $timeframe)
	 							{
									$pair_amount=$pair_amount+$pairs["amount"];
									$data_count++;
								}	 
	 					}
				}
		}
		$alix=$pair_amount/$data_count;
		$answer = array(
		'frame' => "$query_frame",
		'volume' => "$pair_amount",
		'columns' => "$data_count",
		'alix' => "$alix"
		);
		
		array_push($pair_array, $answer);
	}
$pair_array=array('query_pair'=>"$query_pair", "content"=>$pair_array);
array_push($array_all, $pair_array);
}

$array_all=json_encode($array_all);

copy($data_munched, "$data_munched-temp");
file_put_contents("$data_munched-temp", $array_all, LOCK_EX);
copy("$data_munched-temp", $data_munched);
unlink("$data_munched-temp");
?>