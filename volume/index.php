<?php
$pair_amount=0;
$data_count=0;
include("config.php");
include("pairs.php");
include("functions.php");

if(isset($_GET["pair"]) && isset($_GET["frame"])){
	$query_pair=$_GET["pair"];
	#check if query pair is set and in table
	$search_pair=array_search($query_pair, array_column($pairs_available, 'pair'));

	$query_frame=$_GET["frame"];
	#check if frame is set in table
	$search_frame=array_search($query_frame, $frames_available);


	if ($search_pair >=0 && !is_bool($search_pair))
	{
		if ($search_frame) { 
		$answer=get_pair_frame($query_pair,$query_frame);
		echo $answer;
		}
	}
}

if (isset($query_pair) && is_bool($search_pair))  { 
$answer = array(
'error' => 'true',
'msg' => "pair invalid"
);
$answer_json=json_encode($answer);
echo $answer_json;

 }
 
if (isset($query_frame) && !$search_frame)  { 
$answer = array(
'error' => 'true',
'msg' => "frame invalid"
);
$answer_json=json_encode($answer);
echo $answer_json;
}

if(isset($_GET["pairs_available"]))
{
$output=file_get_contents($pairs_available_file);
echo $output;
}

if(isset($_GET["frames_available"]))
{
$output=file_get_contents($frames_available_file);
echo $output;
}

if (empty($_GET))
{
	header("Location: gui_index.php");
}

?>