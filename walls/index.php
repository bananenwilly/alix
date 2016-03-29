<?php
#$before = microtime(true);

include("config.php");
include("functions.php");
$exit=0;

if(isset($_GET["json"]))
{
$content = file_get_contents($data_munched);

	while(!$content)
	{
	$content = file_get_contents($data_munched);
	sleep(1);
	}

print_r($content);
$exit=1;
}

if(isset($_GET["json4h"]))
	{
	$var=file_get_contents($data_4h);
	while(!$var)
	{
		$var=file_get_contents($data_4h);
		sleep(1);
	}
	print_r($var);
	$exit=1;
	}

if(isset($_GET["json15min"]))
	{
	$var=file_get_contents($data_15);
	while(!$var)
	{
		$var=file_get_contents($data_15);
		sleep(1);
	}
	print_r($var);
	$exit=1;
	}

if ($exit)	
{
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ALix Walls</title> 
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script>
var _prum = [['id', '56689167abe53de950064484'],
             ['mark', 'firstbyte', (new Date()).getTime()]];
(function() {
    var s = document.getElementsByTagName('script')[0]
      , p = document.createElement('script');
    p.async = 'async';
    p.src = '//rum-static.pingdom.net/prum.min.js';
    s.parentNode.insertBefore(p, s);
})();
</script>
</head>
<body>
<div align="right"><a href='https://www.coinerella.com' target='_blank'><img height='5%' width='5%' src='../img/coinerella_logo_really_small.png'</img></a></div>
<center><a href='https://www.coinerella.com/index.php/ALix' target='_blank'><img width='5%' height='5%' src='../img/alix_v1_double_a_transparent_small.png'</img></a></center>
<center><h1>Tier 1 liquidity for NuBits</h1></center>

<?php
if(!isset($_GET["json"])) {
echo "<meta http-equiv='refresh' content='60;url='>";
	
	$content = file_get_contents($data_munched);
	while(!$content)
	{
	$content = file_get_contents($data_munched);
	sleep(1);
	}
	$content=json_decode($content, true);

	if(isset($_GET["4h"]))
	{
	$var=file_get_contents($data_4h);
	while(!$var)
	{
		$var=file_get_contents($data_4h);
		sleep(1);
	}
	$var=json_decode($var, true);
	$line_title="4h";
	}
	else {
	$var=file_get_contents($data_15);
	while(!$var)
	{
		$var=file_get_contents($data_15);
		sleep(1);
	}
	$var=json_decode($var, true);
	$line_title="15m";
	}

	echo "<div align='left' id='menu'>
        current time frame: <a href=''><p class='white_menu'>$line_title</p></a> <br>select:
         <a href='?'><p class='white_menu'>15 minutes</p></a> - 
         <a href='?4h'><p class='white_menu'>4 hours</p></a>
 </div>";

	usort($content["exchanges"], 'sort_index');

	$timestamp=$content["timestamp"];
	$btc_usd=$content["btc_usd"];
	date_default_timezone_set('UTC');
	
	$date=date("d.m.Y, H:i:s", $timestamp);	
	echo "<table id='t01'>";
	echo "<tr><th>Pair</th><th>Ask</th><th>Bid</th><th>Total</th><th></th><th>Ask $line_title</th><th>Bid $line_title</th><th>Total $line_title</th><th>Tolerance</th></tr>";

	$total_all=0;
	$bid_all=0;
	$ask_all=0;
	$ask_all_avg=0;
	$bid_all_avg=0;
	$total_all_avg=0;

	foreach($content["exchanges"] as $exchange)
		{

				$pair=$exchange["pair"];
				$pair_link=pair2link($pair);
				$tolerance=$exchange["amount"]["tolerance"];
				$ask_wall=round($exchange["amount"]["ask_total"],4);
				$bid_wall=round($exchange["amount"]["bid_total"],4);	
				$pair_15_ask_avg=round($var["$pair"."_ask"],4);
				$pair_15_bid_avg=round($var["$pair"."_bid"],4);
				$pair_15_total_avg=round($var["$pair"."_total"],4);

				$total=round($ask_wall+$bid_wall,4);
				$total_avg=$pair_15_ask_avg+$pair_15_bid_avg;

				echo "<tr>";
				echo "<td>$pair_link</td>";
				if($ask_wall>0)	{echo "<td>$ask_wall</td>";}
				else { echo	"<td class='red'>0</td>";}
				if($bid_wall>0)	{echo "<td>$bid_wall</td>";}
				else { echo	"<td class='red'>0</td>";}
				if($total>0)	{echo "<td>$total</td>";}
				else { echo	"<td class='red'>0</td>";}
				echo "<td></td>";

				if($pair_15_ask_avg>0)	{echo	"<td>$pair_15_ask_avg</td>";}
				else { echo	"<td class='red'>0</td>";}
				if($pair_15_bid_avg>0)	{echo	"<td>$pair_15_bid_avg</td>";}
				else { echo	"<td class='red'>0</td>";}
				if($pair_15_total_avg>0)		{echo "<td>$pair_15_total_avg</td>";}
				else { echo	"<td class='red'>0</td>";}

				echo "<td>$tolerance</td>";
				echo "</tr>";
				$ask_all=$ask_all+$ask_wall;
				$ask_all_avg=$ask_all_avg+$pair_15_ask_avg;
				$bid_all=$bid_all+$bid_wall;
				$bid_all_avg=$bid_all_avg+$pair_15_bid_avg;
				$total_all=$total_all+$total;
				$total_all_avg=$total_avg+$total_all_avg;
					
		}
		$ask_all_percent=round(($ask_all/$total_all)*100, 2);
		$bid_all_percent=round(($bid_all/$total_all)*100, 2);
		$ask_all_avg_percent=round(($ask_all_avg/$total_all_avg)*100, 2);
		$bid_all_avg_percent=round(($bid_all_avg/$total_all_avg)*100, 2);
		$total_all_percent=$ask_all_percent+$bid_all_percent;
		$total_all_avg_percent=$ask_all_avg_percent+$bid_all_avg_percent;

		echo "<tr>";
		echo "<td><b>Total (NBT) </b></td>";
		echo "<td><b>$ask_all</b></td>";
		echo "<td><b>$bid_all</b></td>";
		echo "<td><b>$total_all</b></td>";
		echo "<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";

		echo "<td><b>$ask_all_avg</b></td>";
		echo "<td><b>$bid_all_avg</b></td>";
		echo "<td><b>$total_all_avg</b></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td><b>Total (Percent) </b></td>";
		echo "<td><b>$ask_all_percent%</b></td>";
		echo "<td><b>$bid_all_percent%</b></td>";
		echo "<td><b>$total_all_percent%</b></td>";
		echo "<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";

		echo "<td><b>$ask_all_avg_percent%</b></td>";
		echo "<td><b>$bid_all_avg_percent%</b></td>";
		echo "<td><b>$total_all_avg_percent%</b></td>";
		echo "</tr>";

		echo "</table>";
		
		echo "<p class='ex'>$date (UTC) - ";
		echo "USD/BTC: $btc_usd</p>";
		
		#$after = microtime(true);
		#echo ($after-$before) . " sec\n";

}
?>
<footer align="center">
	<a href="https://alix.coinerella.com/walls"><p class="footer_link">ALix Walls -</p></a>
	<a href="https://alix.coinerella.com/volume"><p class="footer_link"> ALix Volume -</p></a>
	<a href="https://alix.coinerella.com/charts"><p class="footer_link"> ALix Charts -</p></a>
	<a href="https://alix.coinerella.com/panel"><p class="footer_link"> ALix Panel -</p></a>
	<a href="https://www.coinerella.com/index.php/ALix" target="_blank"><p class="footer_link"> About ALix</p></a>
</footer>
</body>
</html>