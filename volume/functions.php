<?php

function get_pair_frame($search_pair, $search_frame)
{
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include("pairs.php");
include("config.php");

$array_all=file_get_contents($data_munched);

while(!$array_all)
{
	$array_all=file_get_contents($data_munched);
	sleep(1);
}

$array_all=json_decode($array_all, true);
$frame_found=false;


foreach($array_all as $pair_array)
{
	$pair=$pair_array["query_pair"];
	
	if($pair==$search_pair) {
		$count=count($pair_array["content"]);
		$i=0;
		
		while($i<$count && !$frame_found)
		{
		$frame=$pair_array["content"][$i]["frame"];
		if ($frame==$search_frame) {
		$volume=$pair_array["content"][$i]["volume"];		
		$columns=$pair_array["content"][$i]["columns"];		
		$alix=$pair_array["content"][$i]["alix"];	
		$answer = array(
		'error' => 'false',
		'pair' => "$pair",
		'frame' => "$frame",
		'volume' => "$volume",
		'columns' => "$columns",
		'alix' => "$alix"
		);
		$frame_found=true;
		}
	$i++;
	}
	}
}
$answer=json_encode($answer);
return($answer);
}

function pair2link($pairname)
{
    switch($pairname)
    {
        case "poloniex_btc_nbt":
        $answer = "<a href='https://www.poloniex.com/exchange#btc_nbt' target='_blank'>Poloniex BTC/NBT</a>";
        break;
        case "bittrex_btc_nbt":
        $answer = "<a href='https://www.bittrex.com/Market/Index?MarketName=BTC-NBT' target='_blank'>Bittrex BTC/NBT</a>";
        break;
        case "southx_btc_nbt":
        $answer = "<a href='https://www.southxchange.com/Market/Book/BTC/NBT' target='_blank'>southXchange BTC/NBT</a>";
        break;
        case "southx_nbt_usd":
        $answer = "<a href='https://www.southxchange.com/Market/Book/NBT/USD' target='_blank'>southXchange NBT/USD</a>";
        break;
        case "cryptsy_nbt_btc":
        $answer = "<a href='https://www.cryptsy.com/markets/view/NBT_BTC' target='_blank'>Cryptsy NBT/BTC</a>";
        break; 
        case "cryptsy_nbt_usd":
        $answer = "<a href='https://www.cryptsy.com/markets/view/NBT_USD' target='_blank'>Cryptsy NBT/USD</a>";
        break;              
        case "bter_nbt_cny":
        $answer = "<a href='https://bter.com/trade/NBT_CNY' target='_blank'>Bter NBT/CNY</a>";
        break;   
        case "bter_nbt_btc":
        $answer = "<a href='https://bter.com/trade/NBT_BTC' target='_blank'>Bter NBT/BTC</a>";
        break;
        case "hitbtc_nbt_btc":
        $answer = "<a href='https://hitbtc.com/exchange/NBTBTC' target='_blank'>HitBTC NBT/BTC</a>";
        break;
        case "ccedk_nbt_usd":
        $answer = "<a href='https://www.ccedk.com/nbt-usd' target='_blank'>CCEDK NBT/USD</a>";
        break;
        case "ccedk_nbt_eur":
        $answer = "<a href='https://www.ccedk.com/nbt-eur' target='_blank'>CCEDK NBT/EUR</a>";
        break;
        case "ccedk_nbt_btc":
        $answer = "<a href='https://www.ccedk.com/nbt-btc' target='_blank'>CCEDK NBT/BTC</a>";
        break;
        case "ccedk_nbt_ppc":
        $answer = "<a href='https://www.ccedk.com/nbt-ppc' target='_blank'>CCEDK NBT/PPC</a>";
        break;
        case "nulagoon_btc_nbt":
        $answer = "<a href='https://nulagoon.com/' target='_blank'>NuLagoon Tube BTC/NBT</a>";
        break;
    }

return $answer;
}


?>
