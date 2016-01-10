<?php
date_default_timezone_set('UTC');
//init frames
$frames_array=array(
"48_hours" => "2880",
"4_hours" => "240"
);

$ma_frames_array=array(
"48_hours" => "2880",
"4_hours" => "240"
);

//write all data (absolute numbers)
foreach ($frames_array as $index=>$lines)
{
	//initial data load
	$data=array_reverse(file_get_tail("/var/www/alix/walls/data/wall_data.dat", $lines));

	$filename=$index."_all_nbt.csv";
	file_put_contents("/var/www/alix/charts/data/$filename", "Time,Sell,Buy,Total \n");

	foreach($data as $data_line)
	{
		$data_line=json_decode($data_line, true);

		if ($data_line)
		{
			$time=date("Y/m/d G:i:s", $data_line["timestamp"]);
			$total_ask=$data_line["totals"]["total_ask"];
			$total_bid=$data_line["totals"]["total_bid"];
			$total_both=$data_line["totals"]["total_both"];
			$writeln="$time,$total_ask,$total_bid,$total_both";

			file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
		}
	}
}

//write all data (percent numbers)
foreach ($frames_array as $index=>$lines)
{
	//initial data load
	$data=array_reverse(file_get_tail("/var/www/alix/walls/data/wall_data.dat", $lines));

	$filename=$index."_all_percent.csv";
	file_put_contents("/var/www/alix/charts/data/$filename", "Time,Sell,Buy,Total \n");

	foreach($data as $data_line)
	{
		$data_line=json_decode($data_line, true);

		if ($data_line)
		{
			$time=date("Y/m/d G:i:s", $data_line["timestamp"]);
			$total_ask_percent=$data_line["totals"]["total_ask_percent"];
			$total_bid_percent=$data_line["totals"]["total_bid_percent"];
			$total_both_percent=$data_line["totals"]["total_both_percent"];
			$writeln="$time,$total_ask_percent,$total_bid_percent,$total_both_percent";

			file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
		}
	}
}

//write both ma_frames (percent numbers) in one file 

$filename="48h_4h_15min_combined_ma_percent.csv";
file_put_contents("/var/www/alix/charts/data/$filename", "Time,Sell MA/4h,Buy MA/4h,Sell MA/15min,Buy MA/15min,Total \n");


//initial data load 48h
$data=array_reverse(file_get_tail("/var/www/alix/walls/data/wall_data.dat", 2880)); #48h hours

foreach($data as $data_line)
{
	$data_line=json_decode($data_line, true);
	if ($data_line)
	{
		$time=date("Y/m/d G:i:s", $data_line["timestamp"]);
		$total_ask_percent_4h=$data_line["4h_avg"]["total_ask_percent"];
		$total_bid_percent_4h=$data_line["4h_avg"]["total_bid_percent"];
		$total_ask_percent_15min=$data_line["15min_avg"]["total_ask_percent"];
		$total_bid_percent_15min=$data_line["15min_avg"]["total_bid_percent"];
		$writeln="$time,$total_ask_percent_4h,$total_bid_percent_4h,$total_ask_percent_15min,$total_bid_percent_15min,100";

		file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
	}
}

$filename="4h_4h_15min_combined_ma_percent.csv";
file_put_contents("/var/www/alix/charts/data/$filename", "Time,Sell MA/4h,Buy MA/4h,Sell MA/15min,Buy MA/15min,Total \n");

//initial data load 4h
$data=array_reverse(file_get_tail("/var/www/alix/walls/data/wall_data.dat", 240)); #4h hours

foreach($data as $data_line)
{
	$data_line=json_decode($data_line, true);
	if ($data_line)
	{
		$time=date("Y/m/d G:i:s", $data_line["timestamp"]);
		$total_ask_percent_4h=$data_line["4h_avg"]["total_ask_percent"];
		$total_bid_percent_4h=$data_line["4h_avg"]["total_bid_percent"];
		$total_ask_percent_15min=$data_line["15min_avg"]["total_ask_percent"];
		$total_bid_percent_15min=$data_line["15min_avg"]["total_bid_percent"];
		$writeln="$time,$total_ask_percent_4h,$total_bid_percent_4h,$total_ask_percent_15min,$total_bid_percent_15min,100";

		file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
	}
}
?>
