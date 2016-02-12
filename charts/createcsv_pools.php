<?php
include("/var/www/alix/walls/functions.php");

date_default_timezone_set('UTC');
//init frames
$frames_array=array(
"48_hours" => "2880"
);

//write all data (absolute numbers)
foreach ($frames_array as $index=>$lines)
{
	//initial data load
	$data=array_reverse(file_get_tail("/var/www/raw/data/history.dat", $lines));

	$filename=$index."_nupool_poloniex.csv";
	file_put_contents("/var/www/alix/charts/data/$filename", "Time,Sell,Buy,Total \n");

	foreach($data as $data_line)
	{
		$data_line=json_decode($data_line, true);
		$time=date("Y/m/d G:i:s", $data_line["timestamp"]);

		foreach($data_line["data"] as $pool)
		{
			if ($pool["exchange"]=="poloniex")
			{		
			$total_ask=$pool["ask_filled"];
			$total_bid=$pool["bid_filled"];
			$total_both=$total_ask+$total_bid;
			$writeln="$time,$total_ask,$total_bid,$total_both";

			file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
			}
		}
	}
}

?>
