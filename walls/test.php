<?php
include("config.php");
include("logger.php");

//value check
function check_value($value)
{
$return=true;
if (!is_numeric($value)) { $return=false;}

return ($return);
}

//get btc-usd price
function get_btc_usd()
{
$url = 'https://api.bitfinex.com/v1/pubticker/btcusd/';
$content = file_get_contents($url);
$json = json_decode($content, true);

if(check_value($json["mid"])) {
	$btc_usd=$json["mid"];
	}
else
	{
	$url = 'https://coinbase.com/api/v1/prices/spot_rate?currency=USD';
	$content = file_get_contents($url);
	$json = json_decode($content, true);
	if (check_value($json["amount"]))
		{
		$btc_usd=$json["amount"];
		}
		else {
		$url = 'https://www.bitstamp.net/api/ticker/';
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$btc_usd=$json["last"];
		}
	}
return($btc_usd);
}

//
function get_nbt_cny($btc_usd)
{
$url = 'https://www.okcoin.cn/api/ticker.do';
$content = file_get_contents($url);
$json = json_decode($content, true);

	if(check_value($json['ticker']['last']))
 	{
		$nbt_cny=$json['ticker']['last']/$btc_usd;
	}

	if(!$nbt_cny){
	$url = 'https://api.coindesk.com/v1/bpi/currentprice/CNY.json';
	$content = file_get_contents($url);
	$json = json_decode($content, true);

	if(check_value($json['bpi']['CNY']['rate_float'])) {
			$nbt_cny=$json['bpi']['CNY']['rate_float'];
			$nbt_usd=$json['bpi']['USD']['rate_float'];
			$nbt_cny = $nbt_cny/$nbt_usd;	
		}
}
return($nbt_cny);
}

function get_nulagoon_btc_nbt($search_tolerance,$btc_usd) {
$url_wall = 'https://bitbucket.org/henry_nu/data/downloads/datetu.json';
$content_wall = file_get_contents($url_wall);
$wall_json = json_decode($content_wall, true); 

$url_price = 'https://bitbucket.org/henry_nu/data/downloads/rd.json';
$content_price = file_get_contents($url_price);
$price_json = json_decode($content_price, true); 

$ask_total=0;
$bid_total=0;

if (!check_value($wall_json["bal"]["NBT"]) && !check_value($price_json["ask"])) {
	writelog("nulagoon_btc_nbt", "querry_error", "unresolved"); 
	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd+$tolerance;
    $bid_price=$btc_usd-$tolerance;
	
	$ask_value=$wall_json["bal"]["NBT"];
	$bid_value=$wall_json["bal"]["BTC"]*$btc_usd;

	$wall_price_ask=$price_json["ask"];
	$wall_price_bid=$price_json["bid"];

	if($wall_price_ask<=$ask_price)
	{
		$ask_total=$ask_value;
	}
	if($wall_price_bid>=$bid_price)
	{
		$bid_total=$bid_value;
	}

}
$total=$ask_total+$bid_total;
$orderbook=array(
'tolerance'=>$search_tolerance,
'ask_total'=>$ask_total,
'bid_total'=>$bid_total,
'total'=>$total
);
return($orderbook);
}

$btc_usd=get_btc_usd();

$json=get_nulagoon_btc_nbt(1.5, $btc_usd);

print_r($json);

?>