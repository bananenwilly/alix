<?php
include("config.php");
include("pairs.php");
include("functions.php");

$raw_json=file_get_contents($data_munched);

while (!$raw_json)
{
	$raw_json=file_get_contents($data_munched);
	sleep(1);
}

$raw_array=json_decode($raw_json, true);

#put out exchanges
$alix_total=array();
foreach ($raw_array as $exchange)
{
	$name=$exchange["query_pair"];
	foreach ($exchange["content"] as $alix_frames)
	{
		$alix=round($alix_frames["alix"], 4);
		$frame=$alix_frames["frame"];
		@$alix_total[$frame]=$alix_total[$frame]+$alix;
	}
}

$alix_array=array("timestamp" => time());

foreach($frames_available as $frames)
{
	$total=$alix_total["$frames"];
	$this_array=
		array("$frames" =>
		array(
		"value" => "$total"
		));

	$alix_array=array_replace_recursive($alix_array, $this_array);
}

$alix_json=json_encode($alix_array);
$alix_json.="\n";

file_put_contents("$totals_data", $alix_json, LOCK_EX | FILE_APPEND);
?>