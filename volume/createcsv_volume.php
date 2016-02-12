<?php
date_default_timezone_set('UTC');

$tail_file="/var/www/alix/volume/data/totals_history.dat";

$frames_array=array(
"360d" => "360"
);

//write all data (absolute numbers)
foreach ($frames_array as $index=>$lines)
{
	//initial data load
	$data=array_reverse(file_get_tail($tail_file, $lines));

	$filename=$index."_volume.csv";
	file_put_contents("/var/www/alix/charts/data/$filename", "Time,Alix 1,Alix 3,Alix 7, Alix 30, Alix 90 \n");

	foreach($data as $index=>$data_line)
	{
		if(!empty($data_line))
		{
			$data_line=json_decode($data_line, true);
			$time=date("Y/m/d G:i:s", $data_line["timestamp"]);
		
			$alix1=$data_line[1]["value"];
			$alix3=$data_line[3]["value"];
			$alix7=$data_line[7]["value"];
			$alix30=$data_line[30]["value"];
			$alix90=$data_line[90]["value"];

			$writeln="$time,$alix1,$alix3,$alix7,$alix30,$alix90";

			file_put_contents("/var/www/alix/charts/data/$filename","$writeln \n", FILE_APPEND );
		}
	}
}
?>
