<?php
include("config.php");
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

//get nbt/cny price
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


//get nbt/eur price
function get_nbt_eur()
{
$url = 'http://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote?format=json';
$content = file_get_contents($url);
$json = json_decode($content, true);

 foreach($json['list']['resources'] as $res){
            if ($res['resource']['fields']['name'] == 'USD/EUR') {
					$nbt_eur=$res['resource']['fields']['price'];            	
            	}
            }
	
	if(!$nbt_eur){
	$url = 'https://www.bitstamp.net/api/eur_usd/';
	$content = file_get_contents($url);
	$json = json_decode($content, true);

		if(check_value($json['sell'])) 
		{
		$nbt_eur = 2/($json["sell"]+$json["buy"]);	
		}
	}
return($nbt_eur);
}

//poloniex BTC-NBT
function get_poloniex_btc_nbt($search_tolerance,$btc_usd)
{
$url = 'https://poloniex.com/public?command=returnOrderBook&currencyPair=BTC_NBT&depth=50';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["asks"]))
	{
	writelog("poloniex_btc_nbt", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
   $ask_price=1/$ask_price;

   $bid_price=$btc_usd+$tolerance;
   $bid_price=1/$bid_price;

	foreach ($json["asks"] as $item)
		{
			if($item[0]<=$ask_price)
			{
				$ask_total=$ask_total+$item[1];	
			}
		}
	foreach ($json["bids"] as $item)
		{
			if($item[0]>=$bid_price)
			{
				$bid_total=$bid_total+$item[1];	
			}
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

//bittrex BTC-NBT
function get_bittrex_btc_nbt($search_tolerance,$btc_usd) {
$url = 'https://bittrex.com/api/v1.1/public/getorderbook?market=BTC-NBT&type=both&depth=50';
$content = file_get_contents($url);
$ask_total=0;
$bid_total=0;
#do the json dance
$json = json_decode($content, true); 
$json = json_encode($content);
$json = json_decode($content, true);

if (!$json["success"]) {
	writelog("bittrex_btc_nbt", "querry_error", "unresolved"); 
	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
   $ask_price=1/$ask_price;
   $bid_price=$btc_usd+$tolerance;
   $bid_price=1/$bid_price;

	foreach ($json["result"]["sell"] as $item)
		{
			if($item["Rate"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["Quantity"];	
			}
		}
	foreach ($json["result"]["buy"] as $item)
		{
			if($item["Rate"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["Quantity"];		
				$db_rate=$item["Rate"];
			}

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

//southx BTC-NBT
function get_southx_btc_nbt($search_tolerance,$btc_usd) {
$url = 'https://www.southxchange.com/api/book/btc/nbt';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["BuyOrders"]))
	{
	writelog("southx_btc_nbt", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd+$tolerance;
   $bid_price=$btc_usd-$tolerance;
	foreach ($json["SellOrders"] as $item)
		{
			if($item["Price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["Amount"];	
			}
		}
	foreach ($json["BuyOrders"] as $item)
		{
			if($item["Price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["Amount"];	
			}
		}
}

$ask_total=$ask_total*$btc_usd;
$bid_total=$bid_total*$btc_usd;

$total=$ask_total+$bid_total;
$orderbook=array(
'tolerance'=>$search_tolerance,
'ask_total'=>$ask_total,
'bid_total'=>$bid_total,
'total'=>$total
);
return($orderbook);
}

//southx NBT-USD
function get_southx_nbt_usd($search_tolerance) {
$url = 'https://www.southxchange.com/api/book/nbt/usd';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["BuyOrders"]))
	{
	writelog("southx_nbt_usd", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=(1*$search_tolerance)/100; #alwaysadollar
	$ask_price=1+$tolerance;
   $bid_price=1-$tolerance;

	foreach ($json["SellOrders"] as $item)
		{
			if($item["Price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["Amount"];	
			}
		}
	foreach ($json["BuyOrders"] as $item)
		{
			if($item["Price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["Amount"];	
			}
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

//cryptsy NBT/BTC
function get_cryptsy_nbt_btc($search_tolerance,$btc_usd) {
$url = 'https://api.cryptsy.com/api/v2/markets/493/orderbook';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["success"]))
	{
	writelog("cryptsy_nbt_btc", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
  	$ask_price=1/$ask_price;
  	$bid_price=$btc_usd+$tolerance;
   	$bid_price=1/$bid_price;

	foreach ($json["data"]["sellorders"] as $item)
		{
			if($item["price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["quantity"];	
			}
		}
	foreach ($json["data"]["buyorders"] as $item)
		{
			if($item["price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["quantity"];	
			}
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

//cryptsy NBT/USD
function get_cryptsy_nbt_usd($search_tolerance) {
$url = 'https://api.cryptsy.com/api/v2/markets/496/orderbook';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["success"]))
	{
	writelog("cryptsy_nbt_usd", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=(1*$search_tolerance)/100; #alwaysadollar
	$ask_price=1+$tolerance;
   $bid_price=1-$tolerance;

	foreach ($json["data"]["sellorders"] as $item)
		{
			if($item["price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["quantity"];	
			}
		}
	foreach ($json["data"]["buyorders"] as $item)
		{
			if($item["price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["quantity"];	
			}
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

//bter nbt_cny
function get_bter_nbt_cny($search_tolerance,$nbt_cny) {
$url = 'http://data.bter.com/api/1/depth/nbt_cny';
$content = file_get_contents($url);
$ask_total=0;
$bid_total=0;
$json = json_decode($content, true); 

if (!$json["result"]) {
	writelog("bter_nbt_cny", "querry_error", "unresolved"); 
	}
else {
	$tolerance=($nbt_cny*$search_tolerance)/100;
	$ask_price=$nbt_cny+$tolerance;
   $bid_price=$nbt_cny-$tolerance;

	foreach ($json["asks"] as $item)
		{
			if($item[0]<=$ask_price)
			{
				$ask_total=$ask_total+$item[1];	
			}
		}
	foreach ($json["bids"] as $item)
		{
			if($item[0]>=$bid_price)
			{
				$bid_total=$bid_total+$item[1];
			}

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

//bter NBT/BTC
function get_bter_nbt_btc($search_tolerance,$btc_usd) {
$url = 'http://data.bter.com/api/1/depth/nbt_btc';
$content = file_get_contents($url);
$ask_total=0;
$bid_total=0;
$json = json_decode($content, true); 

if (!$json["result"]) {
	writelog("bter_nbt_btc", "querry_error", "unresolved"); 
	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
   $ask_price=1/$ask_price;
   $bid_price=$btc_usd+$tolerance;
   $bid_price=1/$bid_price;

	foreach ($json["asks"] as $item)
		{
			if($item[0]<=$ask_price)
			{
				$ask_total=$ask_total+$item[1];	
			}
		}
	foreach ($json["bids"] as $item)
		{
			if($item[0]>=$bid_price)
			{
				$bid_total=$bid_total+$item[1];
			}

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

//ccedk.. oh boy
//NBT/USD
function get_ccedk_nbt_usd($search_tolerance) {
$url = 'https://www.ccedk.com/api/v1/orderbook/info?pair_id=46';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if ($json["errors"])
	{
	writelog("ccedk_nbt_usd", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=(1*$search_tolerance)/100; #alwaysadollar
	$ask_price=1+$tolerance;
   	$bid_price=1-$tolerance;

	foreach ($json["response"]["entities"]["asks"] as $item)
		{
			if($item["price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["amount"];	
			}
		}
	foreach ($json["response"]["entities"]["bids"] as $item)
		{
			if($item["price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["amount"];	
			}
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

//ccedk.. NBT/BTC
function get_ccedk_nbt_btc($search_tolerance, $btc_usd) {
$url = 'https://www.ccedk.com/api/v1/orderbook/info?pair_id=47';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if ($json["errors"])
	{
	writelog("ccedk_nbt_btc", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
  	$ask_price=1/$ask_price;
  	$bid_price=$btc_usd+$tolerance;
   	$bid_price=1/$bid_price;

	foreach ($json["response"]["entities"]["asks"] as $item)
		{
			if($item["price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["amount"];	
			}
		}
	foreach ($json["response"]["entities"]["bids"] as $item)
		{
			if($item["price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["amount"];	
			}
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

//ccedk.. NBT/EUR
function get_ccedk_nbt_eur($search_tolerance, $btc_usd) {
$url = 'https://www.ccedk.com/api/v1/orderbook/info?pair_id=49';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if ($json["errors"])
	{
	writelog("ccedk_nbt_eur", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd+$tolerance;
  	$bid_price=$btc_usd-$tolerance;

	foreach ($json["response"]["entities"]["asks"] as $item)
		{
			if($item["price"]<=$ask_price)
			{
				$ask_total=$ask_total+$item["amount"];	
			}
		}
	foreach ($json["response"]["entities"]["bids"] as $item)
		{
			if($item["price"]>=$bid_price)
			{
				$bid_total=$bid_total+$item["amount"];	
			}
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

//HitBTC NBT/BTC
function get_hitbtc_nbt_btc($search_tolerance,$btc_usd) {
$url = 'https://api.hitbtc.com/api/1/public/NBTBTC/orderbook';
$content = file_get_contents($url);
$json = json_decode($content, true);
$ask_total=0;
$bid_total=0;

if (!($json["asks"]))
	{
	writelog("hitbtc_nbt_btc", "querry_error", "unresolved"); 
 	}
else {
	$tolerance=($btc_usd*$search_tolerance)/100;
	$ask_price=$btc_usd-$tolerance;
   $ask_price=1/$ask_price;

   $bid_price=$btc_usd+$tolerance;
   $bid_price=1/$bid_price;

	foreach ($json["asks"] as $item)
		{
			if($item[0]<=$ask_price)
			{
				$ask_total=$ask_total+$item[1];	
			}
		}
	foreach ($json["bids"] as $item)
		{
			if($item[0]>=$bid_price)
			{
				$bid_total=$bid_total+$item[1];	
			}
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

//NuLagoon Tube BTC_NBT
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


function query_exchanges($tolerance,$btc_usd,$nbt_cny,$nbt_eur)
{
$total_ask=0;
$total_bid=0;
$total_both=0;
include("config.php");

$poloniex_btc_nbt=get_poloniex_btc_nbt($tolerance,$btc_usd);
$bittrex_btc_nbt=get_bittrex_btc_nbt($tolerance,$btc_usd);
$bter_nbt_btc=get_bter_nbt_btc($tolerance,$btc_usd);
$bter_nbt_cny=get_bter_nbt_cny($tolerance,$nbt_cny);
$hitbtc_nbt_btc=get_hitbtc_nbt_btc($tolerance,$btc_usd);
$southx_btc_nbt=get_southx_btc_nbt($tolerance, $btc_usd);
$southx_nbt_usd=get_southx_nbt_usd($tolerance);
$cryptsy_nbt_btc=get_cryptsy_nbt_btc($tolerance, $btc_usd);
$cryptsy_nbt_usd=get_cryptsy_nbt_usd(1.6);
$ccedk_nbt_usd=get_ccedk_nbt_usd($tolerance);
$ccedk_nbt_btc=get_ccedk_nbt_btc($tolerance, $btc_usd);
$ccedk_nbt_eur=get_ccedk_nbt_eur($tolerance, $nbt_eur);
$nulagoon_btc_nbt=get_nulagoon_btc_nbt($tolerance, $btc_usd);

//write exchanges into walls_data.dat
$data=array(
'timestamp' => time(),
'btc_usd' => "$btc_usd",
'exchanges' => array(
array('pair'=>'poloniex_btc_nbt', 'amount' => $poloniex_btc_nbt),
array('pair'=>'bittrex_btc_nbt', 'amount' => $bittrex_btc_nbt),
array('pair'=>'bter_nbt_btc', 'amount' => $bter_nbt_btc),
array('pair'=>'bter_nbt_cny', 'amount' => $bter_nbt_cny),
array('pair'=>'hitbtc_nbt_btc', 'amount' => $hitbtc_nbt_btc),
array('pair'=>'southx_btc_nbt', 'amount' => $southx_btc_nbt),
array('pair'=>'southx_nbt_usd', 'amount' => $southx_nbt_usd),
array('pair'=>'cryptsy_nbt_btc', 'amount' => $cryptsy_nbt_btc),
array('pair'=>'cryptsy_nbt_usd', 'amount' => $cryptsy_nbt_usd),
array('pair'=>'ccedk_nbt_usd', 'amount' => $ccedk_nbt_usd),
array('pair'=>'ccedk_nbt_btc', 'amount' => $ccedk_nbt_btc),
array('pair'=>'ccedk_nbt_eur', 'amount' => $ccedk_nbt_eur),
array('pair'=>'nulagoon_btc_nbt', 'amount' => $nulagoon_btc_nbt)
)
);

foreach ($data["exchanges"] as $line)
{
	$total_ask=$total_ask+$line["amount"]["ask_total"];
	$total_bid=$total_bid+$line["amount"]["bid_total"];
}
$total_both=$total_ask+$total_bid;
$total_ask_percent=round(($total_ask/$total_both)*100, 2);
$total_bid_percent=round(($total_bid/$total_both)*100, 2);
$total_both_percent=$total_ask_percent+$total_bid_percent;

$totals=array(
'totals' => array(
'total_ask' => $total_ask,
'total_bid' => $total_bid,
'total_both' => $total_both,
'total_ask_percent' => $total_ask_percent,
'total_bid_percent' => $total_bid_percent,
'total_both_percent' => $total_both_percent
)
);
$data=array_replace_recursive($data, $totals);

#get avgs
$var=get_avg(15);
$var=json_encode($var);
file_put_contents($data_15, $var, LOCK_EX);

$var=get_avg(240);
$var=json_encode($var);
file_put_contents($data_4h, $var, LOCK_EX);

#calculate ma 15
$avg15=calculate_ma("data/last15.dat");
$avg15_array=array(
'15min_avg' => $avg15
);
$data=array_replace_recursive($data, $avg15_array);

#calculate ma 4h
$avg4h=calculate_ma("data/last4h.dat");
$avg4h_array=array(
'4h_avg' => $avg4h
);
$data=array_replace_recursive($data, $avg4h_array);

#create and write final json
$new_json = json_encode($data);
$current = $new_json."\n";
file_put_contents($data_file, $current,  FILE_APPEND | LOCK_EX);

return($new_json);
}


?>