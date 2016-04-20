<?php
//read last 10k data
$path = "/var/www/alix/panel/data/elected_custodians.dat";
$content = file_get_contents($path);

while(!$content)
{
	$content = file_get_contents($path);
	sleep(1);
}

$content=json_decode($content, true);
$i=1;

//create container
?>
<h1>Elected Custodians</h1>
<div style="margin: 30px;">
	<div class="row">
		<div class="col-md-12">
			<table class="t01">
				<th>#</th><th>Address</th><th>Amount</th><th>Block Passed</th><th>Time</th></tr>
					<?php  
						foreach($content as $content_sub_array)
							{					
								$address=$content_sub_array["address"];
								$amount=$content_sub_array["amount"];
								$height=$content_sub_array["height"];
								$time=$content_sub_array["time"];
								$unit=$content_sub_array["unit"];

								if($unit=="B") {$unit="NBT";} else {$unit="NSR";}

								echo "<tr>";
								echo "<td>$i</td><td><a href=\"https://discuss.nubits.com/search?q=$address\" target=\"_blank\"><p class=\"black_link\">$address</p></a></td><td>$amount $unit</td><td><a href=\"http://blockexplorer.nu/blocks/$height/1\" target=\"_blank\"><p class=\"black_link\"><b>$height</b></p></a></td><td><b>$time</b></td>";
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


