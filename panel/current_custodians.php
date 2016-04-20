<?php
if(isset($_GET["window"]))
{
	if($_GET["window"]=="100" OR $_GET["window"]=="10k")
	{
		$window=$_GET["window"];
	}
	else{
		$window="10k";
	}
}
else {
	$window="10k";
}

//generate buttons

if ($window == "100")
{
	$button1="<a href=\"?load=current_custodians&window=100\" class=\"btn btn-primary btn-md active\" role=\"button\" style=\"margin: 2px;\">100 block window</a>";	
	$button2="<a href=\"?load=current_custodians&window=10k\" class=\"btn btn-primary btn-md\" role=\"button\" style=\"margin: 2px;\">10k block window</a>";	
}
if ($window == "10k")
{
	$button1=$button2="<a href=\"?load=current_custodians&window=100\" class=\"btn btn-primary btn-md\" role=\"button\" style=\"margin: 2px;\">100 block window</a>";	
	$button2=$button2="<a href=\"?load=current_custodians&window=10k\" class=\"btn btn-primary btn-md active\" role=\"button\" style=\"margin: 2px;\">10k block window</a>";	
}

//read last 10x data
$path = "/var/www/alix/panel/data/custodians_last_$window.dat";
$content = file_get_contents($path);

while(!$content)
{
	$content = file_get_contents($path);
	sleep(1);
}

$content=json_decode($content, true);
array_pop($content); //remove last array entry -> totals

$i=1;

//create container
?>
<h1>Current Custodian Votes</h1>
<p class="explain"> Votes casted over the last <?php echo $window; ?> blocks</p>
<?php echo "$button1 $button2"; ?> 
<div style="margin: 30px;">
	<div class="row">
		<div class="col-md-12">
			<table class="t01">
				<th>#</th><th>Address</th><th>Amount</th><th>Block %</th><th>Sharedays %</th></tr>
					<?php  
						foreach($content as $content_sub_array)
							{				
								$block_percentage=number_format(round($content_sub_array["block_percentage"],2),2);
								$shareday_percentage=number_format(round($content_sub_array["shareday_percentage"],2),2);
								$address=$content_sub_array["address"];
								$amount=$content_sub_array["amount"];
								echo "<tr>";
								echo "<td>$i</td><td><a href=\"https://discuss.nubits.com/search?q=$address\" target=\"_blank\"><p class=\"black_link\">$address</p></a></td><td>$amount</td><td><b>$block_percentage</b></td><td><b>$shareday_percentage</b></td>";
								echo "</tr>";
								$i++;
							}
					?>
			</table>
		</div>	
	</div>
</div>
</body>
</html>