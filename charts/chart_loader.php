<?php
include("chart_functions.php");
$file_to_load=$_GET["chart"];
$file_to_load_dot_csv="data/$file_to_load.csv";

$label_array=chart_filename_to_labels($file_to_load);
$title=$label_array["title"];
$liquidity_label=$label_array["liquidity_label"];
$roller=$label_array["roller"];
$roll_period=$label_array["roll_period"];

?>
<html>
<head>
<script type="text/javascript" src="js/dygraph-combined-dev.js"></script>
<style>
#loaded .dygraph-title { font-size: 36px; color:#ffb800;}
#loaded .dygraph-ylabel { font-size: 18px; color:#ffb800;}
#loaded .dygraph-axis-label.dygraph-axis-label-x { font-size: 18px; color:white;}
#loaded .dygraph-axis-label.dygraph-axis-label-y { font-size: 18px; color:white;}

.chart { border: #0074a0 }
</style>
</head>
<body>
<div id="loaded" style="width:98%; height:500px;"></div>
<script type="text/javascript">
   var x_title_label = "<?php echo $title; ?>";
   var x_liquidity_label = "<?php echo $liquidity_label; ?>";
   var x_file_to_load = "<?php echo $file_to_load_dot_csv; ?>";
   var x_roller = "<?php echo $roller; ?>";
   var x_roll_period = "<?php echo $roll_period; ?>";

 g24h = new Dygraph(
    document.getElementById("loaded"),
    x_file_to_load,
    { 
      title: x_title_label,  
      ylabel: x_liquidity_label, 
      legend: 'always',
      colors: ['#0074a0', '#ef3614', 'green', '#ffb800', '#236A62'],
      showRoller: x_roller,
      rollPeriod: x_roll_period,
      labelsKMB: true
    }
    );    

    function load_show(el) {
 	g24h.setVisibility(el.id, el.checked);
  	}
</script>
<?php

if($file_to_load !="48h_4h_15min_combined_ma_percent" && $file_to_load != "4h_4h_15min_combined_ma_percent")
{
$print_br=false;
$font_array=array("#0074a0", "#ef3614", "green");
$button_array=get_display_buttons("standard");
}
else{
$print_br=true;
$font_array=array("#0074a0", "#ef3614", "green", '#ffb800', '#236A62');
$button_array=get_display_buttons("combined_ma");
}

echo "<font color=\"white\"><br><b>Display: </b></font>";
if($print_br) {echo "<br>";}

foreach($button_array as $key=>$button_name)
{
	$color=$font_array[$key];
echo "<input type=checkbox id=$key onClick=\"load_show(this)\" checked>";
echo "<label for=$key><font color=\"$color\"> $button_name</font></label>";
if($print_br) {echo "<br>";}
}

?>
<div align="right">
  <?php echo "<a href=\"?select=$file_to_load\"><p class=\"white_link\">link to this chart</p></a>";?>
</div>
</body>
</html>