<!DOCTYPE html>
<html>
<head>
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
<title>ALix Walls Charts</title> 
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<link rel="icon" href="../favicon.ico" type="image/x-icon">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="js/alix.js"></script>
<meta charset="utf-8">
</head>


<div class="container">
<body>
<div align="right"><a href='https://www.coinerella.com' target='_blank'><img height='5%' width='5%' src='../img/coinerella_logo_really_small.png'</img></a></div>
<div id="sidebar" style="display: block;">
<table id='t01'>
<?php
include("../walls/pairs.php");
include("chart_functions.php");

if(!isset($_GET["select"])) {
$selected_chart="4_hours_all_percent";
}
else { $selected_chart=$_GET["select"]; }

echo"<th>ALix</th></tr><th>Absolute Numbers</tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('48_hours_all_nbt')\";>48 hours</td></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('4_hours_all_nbt')\";>4 hours</td></tr>";
echo"<th>Percent</th></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('48_hours_all_percent')\";>48 hours</td></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('4_hours_all_percent')\";>4 hours</a></td></tr>";
echo"<th>Moving Average</th></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('48h_4h_15min_combined_ma_percent')\";>48 hours combined</a></td></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('4h_4h_15min_combined_ma_percent')\";>4 hours combined</a></td></tr>";
echo "</table>";
echo "</div>"; #end of sidebar
?>
<br>
 <div id="chart" align="center"></div>​
<?php echo "<script>setSelect(\"$selected_chart\");</script>";?>
<div id="footer" align="center">
<a href="https://alix.coinerella.com/walls"><p class="white_link">ALix Walls -</p></a>
<a href="https://alix.coinerella.com/volume"><p class="white_link"> ALix Volume -</p></a>
<a href="https://alix.coinerella.com/charts"><p class="white_link"> ALix Charts -</p></a>
<a href="https://www.coinerella.com/index.php/ALix" target="_blank"><p class="white_link"> About ALix</p></a>
 </div>
</body>
</html>
