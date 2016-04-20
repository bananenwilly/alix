<?php
//get which data to use for alix walls
if(isset($_GET["alix_walls"]))
{
	if($_GET["alix_walls"]=="15min" OR $_GET["alix_walls"]=="4h")
	{
		$alix_walls_data=$_GET["alix_walls"];
	}
	else{
		$alix_walls_data="15min";
	}
}
else {
	$alix_walls_data="15min";
}

//get alix walls data
$alix_data=json_decode(file_get_contents("https://alix.coinerella.com/walls/?json"), true);
if($alix_walls_data=="15min")
{
	$alix_n=$alix_data["15min_avg"];
}
if($alix_walls_data=="4h")
{
	$alix_n=$alix_data["4h_avg"];
}

//get which data to use for liq history
if(isset($_GET["liq_history"]))
{
	if($_GET["liq_history"]=="15min" OR $_GET["liq_history"]=="4h")
	{
		$liq_history_data=$_GET["liq_history"];
	}
	else{
		$liq_history_data="15min";
	}
}
else {
	$liq_history_data="15min";
}

//get liq history data
if($liq_history_data=="15min")
{	
	$liq_history=json_decode(file_get_contents("https://raw.coinerella.com/?liquidity_15min"), true);
}
if($liq_history_data=="4h")
{
	$liq_history=json_decode(file_get_contents("https://raw.coinerella.com/?liquidity_4h"), true);
}

//get which data to use for t1
if(isset($_GET["data"]))
{
	if($_GET["data"]=="alix" OR $_GET["data"]=="reported")
	{
		$t1_data=$_GET["data"];
	}
	else{
		$t1_data="alix";
	}
}
else {
	$t1_data="alix";
}

//calculate
$tier1_ask_percent=number_format(round(($liq_history["tier1"]["sell"]/$liq_history["tier1"]["both"]*100), 2), 2);
$tier1_bid_percent=number_format(round(($liq_history["tier1"]["buy"]/$liq_history["tier1"]["both"]*100), 2), 2);
$tier2_ask_percent=number_format(round(($liq_history["tier2"]["sell"]/$liq_history["tier2"]["both"]*100), 2), 2);
$tier2_bid_percent=number_format(round(($liq_history["tier2"]["buy"]/$liq_history["tier2"]["both"]*100), 2), 2);
$tier3_ask_percent=number_format(round(($liq_history["tier3"]["sell"]/$liq_history["tier3"]["both"]*100), 2), 2);
$tier3_bid_percent=number_format(round(($liq_history["tier3"]["buy"]/$liq_history["tier3"]["both"]*100), 2), 2);

if ($t1_data=="alix")
{
	$all_both=round($alix_n["total_both"]+$liq_history["tier2"]["both"]+$liq_history["tier3"]["both"],4);
	$all_ask=round($alix_n["total_ask"]+$liq_history["tier2"]["sell"]+$liq_history["tier3"]["sell"],4);
	$all_bid=round($alix_n["total_bid"]+$liq_history["tier2"]["buy"]+$liq_history["tier3"]["buy"],4);
	$button1="<a href=\"?load=liquidity&data=alix&alix_walls=$alix_walls_data&liq_history=$liq_history_data\" class=\"btn btn-primary btn-md active\" role=\"button\" style=\"margin: 2px;\">Use ALix Walls data for T1</a>";	
	$button2="<a href=\"?load=liquidity&data=reported&alix_walls=$alix_walls_data&liq_history=$liq_history_data\" class=\"btn btn-primary btn-md\" role=\"button\" style=\"margin: 2px;\">Use reported liquidity for T1</a>";	
}
if ($t1_data=="reported")
{
	$all_both=round($liq_history["tier1"]["both"]+$liq_history["tier2"]["both"]+$liq_history["tier3"]["both"],4);
	$all_ask=round($liq_history["tier1"]["sell"]+$liq_history["tier2"]["sell"]+$liq_history["tier3"]["sell"],4);
	$all_bid=round($liq_history["tier1"]["buy"]+$liq_history["tier2"]["buy"]+$liq_history["tier3"]["buy"],4);	
	$button1="<a href=\"?load=liquidity&data=alix&alix_walls=$alix_walls_data&liq_history=$liq_history_data\" class=\"btn btn-primary btn-md\" role=\"button\" style=\"margin: 2px;\">Use ALix Walls data for T1</a>";	
	$button2="<a href=\"?load=liquidity&data=reported&alix_walls=$alix_walls_data&liq_history=$liq_history_data\" class=\"btn btn-primary btn-md active\" role=\"button\" style=\"margin: 2px;\">Use reported liquidity for T1</a>";	
}

$all_ask_percent=number_format(round(($all_ask/$all_both)*100, 2), 2);
$all_bid_percent=number_format(round(($all_bid/$all_both)*100, 2), 2);
$all_both_percent=$all_ask_percent+$all_bid_percent;

$timestamp=$alix_data["timestamp"];
?>
		<h1>Network Liquidity</h1>
	</div>
	<div style="margin: 30px;">
		<div class="row">
			<div class="col-md-12">
				<h2> Gathered Liquidity (ALix Walls) </h2>
				<table class="t01">
				<th>Tier</th><th>Ask</th><th>%</th><th>Bid</th><th>%</th><th>Total</th></tr>
				<td>T1</td>
				<td><?php echo round($alix_n["total_ask"],4) ?></td><td><b><?php echo number_format(round($alix_n["total_ask_percent"],4), 2) ?></b></td>
				<td><?php echo round($alix_n["total_bid"],4) ?></td><td><b><?php echo number_format(round($alix_n["total_bid_percent"],4), 2) ?></b></td>
				<td><?php echo round($alix_n["total_both"],4) ?></tr>
				</table>
			</div>	
			<div class="col-md-12">
				<h2> Reported Liquidity (from Nu client) </h2>
				<table class="t01">
				<th>Tier</th><th>Ask</th><th>%</th><th>Bid</th><th>%</th><th>Total</th></tr>
				<td>T1</td><td>
				<?php 
					echo $liq_history["tier1"]["sell"]; 
					echo "</td><td><b>$tier1_ask_percent</b></td><td>"; 
					echo $liq_history["tier1"]["buy"]; 
					echo "</td><td><b>$tier1_bid_percent</b></td><td>"; 
					echo $liq_history["tier1"]["both"];
				?>
				</tr>
				<td>T2</td><td>
				<?php 
					echo $liq_history["tier2"]["sell"]; 
					echo "</td><td><b>$tier2_ask_percent</b></td><td>"; 
					echo $liq_history["tier2"]["buy"]; 
					echo "</td><td><b>$tier2_bid_percent</b></td><td>"; 
					echo $liq_history["tier2"]["both"];
				?>
				</tr>
				<td>T3</td><td>
				<?php 
					echo $liq_history["tier3"]["sell"]; 
					echo "</td><td><b>$tier3_ask_percent</b></td><td>"; 
					echo $liq_history["tier3"]["buy"]; 
					echo "</td><td><b>$tier3_bid_percent</b></td><td>"; 
					echo $liq_history["tier3"]["both"];
				?>
				</tr>
				</table>
			</div>
			<div class="col-md-12">
					<h2> Totals: </h2>
					<table class="t01">
					<td><b>Ask</b></td><td><?php echo "$all_ask </td><td><b>$all_ask_percent%</b>";?></td></tr>
					<td><b>Bid</b></td><td><?php echo "$all_bid </td><td><b>$all_bid_percent%</b>";?></td></tr>
					<td><b>Total</b></td><td><?php echo "$all_both </td><td>$all_both_percent%";?></td></tr>
					</table>
					<div style="margin-top: 10px;">
						<?php 
						echo $button1; echo $button2;	
						echo "
						<div class=\"dropdown\" style=\"display:inline\";>
						    <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\">ALix: $alix_walls_data
						    <span class=\"caret\"></span></button>
						    <ul class=\"dropdown-menu\">
						      <li><a href=\"?load=liquidity&data=$t1_data&alix_walls=15min&liq_history=$liq_history_data\">15 min</a></li>
						      <li><a href=\"?load=liquidity&data=$t1_data&alix_walls=4h&liq_history=$liq_history_data\">4 h</a></li>
						    </ul>
						</div>
						<div class=\"dropdown\" style=\"display:inline\";>
						    <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\">Liquidity: $liq_history_data
						    <span class=\"caret\"></span></button>
						    <ul class=\"dropdown-menu\">
						      <li><a href=\"?load=liquidity&data=$t1_data&alix_walls=$alix_walls_data&liq_history=15min\">15 min</a></li>
						      <li><a href=\"?load=liquidity&data=$t1_data&alix_walls=$alix_walls_data&liq_history=4h\">4 h</a></li>
						    </ul>
					  	</div>
					  	";
					     ?>
					</div>
			</div>
		</div>
		<div class="row">
		<?php
		date_default_timezone_set('UTC');
		$date=date("d.m.Y, H:i:s", $timestamp);	
		echo "<p class='explain' style='display: inline;'>$date (UTC)";
		?>
		</div>
</div>