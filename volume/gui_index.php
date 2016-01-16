<!DOCTYPE html>
<html>
<head>
<title>ALix Volume</title> 
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="sorttable.js"></script>
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
<center><h1>Tier 1 average volume for NuBits</h1></center>
<br>
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

echo "<table id='t01' class='sortable'>";
echo "<tr><th>Pair</th>";

#get TH
foreach($frames_available as $frames)
{
	echo "<th>ALix $frames</th>";
}
echo "</tr>";

#put out exchanges
$alix_total=array();
foreach ($raw_array as $exchange)
{
	$name=$exchange["query_pair"];
	$pair_link=pair2link($name);
	echo "<tr><td>$pair_link</td>";
	foreach ($exchange["content"] as $alix_frames)
	{
		$alix=round($alix_frames["alix"], 4);
		$frame=$alix_frames["frame"];
		@$alix_total[$frame]=$alix_total[$frame]+$alix;
		if($alix>0)	{echo "<td>$alix</td>";}
		else { echo	"<td class='red'>0</td>";}	
	}
}
echo "</tr>";
echo "<tfoot><tr><td>Total (NBT)</td>";
foreach($frames_available as $frames)
{
	$total=$alix_total["$frames"];
	echo "<td><b>$total</b></td>";
}
echo "</tr></tfoot>";
?>
</table><br>
<div id="footer" align="center">
<a href="https://alix.coinerella.com/walls"><p class="white_link">ALix Walls -</p></a>
<a href="https://alix.coinerella.com/volume"><p class="white_link"> ALix Volume -</p></a>
<a href="https://alix.coinerella.com/charts"><p class="white_link"> ALix Charts -</p></a>
<a href="https://www.coinerella.com/index.php/ALix" target="_blank"><p class="white_link"> About ALix</p></a>
 </div>
</body>