<?php
include("exchanges.php");
include('logger.php');
include('config.php');
$btc_usd=get_btc_usd();
$count=0;

if (!$btc_usd)
{
writelog("all", "no_bitcoin_price", "unresolved"); 
exit; #no bitcoin price available, sorry. will not gather data (offline?)
}

//resolve no btc price error
$i=0;
$error_log=file_get_contents($error_log_file);
$error_array=array_filter(explode("\n", $error_log));
$count_error_lines=count($error_array);

while($count_error_lines>$i)
{
$error_lines_div=explode(" - ", $error_array[$i]);
#0 timestamp
#1 date
#2 time on clock
#3 pair
#4 error type
#5 status
$timestamp=$error_lines_div[0];
$timeframe=time()-3900; #last 65 minutes

if ($timestamp>=$timeframe) #check for log lines from the last two hours
	{
	$error_type=$error_lines_div[4];
	$status=$error_lines_div[5];
	
	 	if (strcmp($status, "unresolved")==1)
	 		{
	 			if(strcmp($error_type, "no_bitcoin_price")<1)
	 			{
					#we had a failure in the last 65 minutes.
					$error_lines_div[5]="resolved";
					query_exchanges();
	 			}
	 		}
	}	
	$error_lines=$error_lines_div[0].' - '.$error_lines_div[1].' - '.$error_lines_div[2].' - '.$error_lines_div[3].' - '.$error_lines_div[4].' - '.$error_lines_div[5];		
	file_put_contents("$error_log_file-temp", $error_lines."\n",  FILE_APPEND);	
$i++;
}
copy("$error_log_file-temp", $error_log_file);
unlink("$error_log_file-temp");

//resolve query errors
$data_array = explode("\n", file_get_contents($data_file));
$data_array = array_filter($data_array);

	foreach ($data_array as $json_line)	
	{
		$json_array=json_decode($json_line, true);
		$timestamp=$json_array["timestamp"];
		$timeframe=time()-7200; #last 2 hours

		if ($timestamp>=$timeframe)
		{
			$count++;		
		}	
		
			foreach ($json_array["exchanges"] as $item)
				{

					if ($item["amount"]=="-1")
						{		
							if ($timestamp>=$timeframe) #check for data lines from the last two hours
								{
									$pair=$item["pair"];
									$search=array_search($pair, array_column($json_array['exchanges'], 'pair'));
									$new_amount="get_" . $pair;
									$new_amount=$new_amount($btc_usd);					
					
									if($new_amount!="-1")
									{
										$json_array['exchanges'][$search]["amount"]=$new_amount;
										writelog($pair, "querry_error", "resolved"); 
									}					
								}							
						}	
				}			
		$json_array=json_encode($json_array);		
		file_put_contents("$data_file-temp", $json_array."\n",  FILE_APPEND | LOCK_EX);	
	}

copy("$data_file-temp", $data_file);
unlink("$data_file-temp");

if ($count==0) #we have NO lines from the last two hours at all.. failure... re-gather
{
	writelog("all", "no_lines_last_two", "resolved"); 
	query_exchanges();
}	

include("write_data.php");
?>