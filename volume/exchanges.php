<?php
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

//poloniex BTC-NBT
function get_poloniex_btc_nbt()
{
$url = 'https://poloniex.com/public?command=return24hVolume';
$content = file_get_contents($url);
$json = json_decode($content, true);
if (!check_value($json["BTC_NBT"]["NBT"]))
	{
	writelog("poloniex_btc_nbt", "querry_error", "unresolved"); 
	$poloniex_btc_nbt_24="-1";	
 	}
else {
	if ($json["BTC_NBT"]["NBT"]=="0.00000000")
			{	
			$poloniex_btc_nbt_24="0";
			}
	else 	
			{
			$poloniex_btc_nbt_24=$json["BTC_NBT"]["NBT"];
			}
}
return($poloniex_btc_nbt_24);
}

//bittrex BTC-NBT
function get_bittrex_btc_nbt () {
$url = 'https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-nbt';
$content = file_get_contents($url);
$json = json_decode($content);

if (!$json->result) {
	writelog("bittrex_btc_nbt", "querry_error", "unresolved"); 
	$bittrex_btc_nbt_24 ="-1";	
	}
else {
	foreach($json->result as $item)
	{
   	if($item->MarketName == "BTC-NBT")
    	{
        $bittrex_btc_nbt_24=$item->Volume;
    	}
	}
	if ($bittrex_btc_nbt_24==0.00000000)
	{	
	 $bittrex_btc_nbt_24="0";
	}
}
return($bittrex_btc_nbt_24);
}

//southx BTC-NBT
function get_southx_btc_nbt ($btc_usd) {
$url = 'https://www.southxchange.com/api/price/btc/us-nbt';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["Volume24Hr"]))
	{
	writelog("southx_btc_nbt", "querry_error", "unresolved"); 
	$southx_btc_nbt_24 ="-1";	
}
else {
	if ($json["Volume24Hr"]==0.0)
	{
	$southx_btc_nbt_24="0";
	}
	else {
	$southx_btc_nbt_24=$json["Volume24Hr"]*$btc_usd; //btc value
	}
}
return($southx_btc_nbt_24);
}

//southx NBT-USD
function get_southx_nbt_usd() {
$url = 'https://www.southxchange.com/api/price/us-nbt/usd';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["Volume24Hr"]))
	{
	writelog("southx_nbt_usd", "querry_error", "unresolved"); 
	$southx_nbt_usd_24 ="-1";	
}
else {
	if ($json["Volume24Hr"]==0.0)
	{
	$southx_nbt_usd_24="0";
	}
	else {
	$southx_nbt_usd_24=$json["Volume24Hr"];
	}
}
return ($southx_nbt_usd_24);
}

//cryptsy NBT/BTC
function get_cryptsy_nbt_btc() {
$url = 'https://api.cryptsy.com/api/v2/markets/493/volume';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["data"]["volume"]))
	{
	writelog("cryptsy_nbt_btc", "querry_error", "unresolved"); 
	$cryptsy_nbt_btc_24 ="-1";		
	}
	else {
	if ($json["data"]["volume"]==0)
	{
	$cryptsy_nbt_btc_24="0";
	}
	else {
	$cryptsy_nbt_btc_24=$json["data"]["volume"];
	}
}
return ($cryptsy_nbt_btc_24);
}

//cryptsy NBT/USD
function get_cryptsy_nbt_usd() {
$url = 'https://api.cryptsy.com/api/v2/markets/496/volume';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["data"]["volume"]))
	{
	writelog("cryptsy_nbt_usd", "querry_error", "unresolved"); 
	$cryptsy_nbt_usd_24 ="-1";		
	}
	else {
	if ($json["data"]["volume"]==0)
	{
	$cryptsy_nbt_usd_24="0";
	}
	else {
	$cryptsy_nbt_usd_24=$json["data"]["volume"];
	}
}
return ($cryptsy_nbt_usd_24);
}

//bter NBT/CNY
function get_bter_nbt_cny() {
$url = 'http://data.bter.com/api/1/ticker/nbt_cny';
$content = file_get_contents($url);
$json = json_decode($content, true);

if(!check_value($json["vol_nbt"]))
	{
	writelog("bter_nbt_cny", "querry_error", "unresolved"); 
	$bter_nbt_cny_24 ="-1";		
	}
	else {
	if ($json["vol_nbt"]=="0.00000000")
		{	
		$bter_nbt_cny_24="0";
		}
	else
		{
		$bter_nbt_cny_24=$json["vol_nbt"];
		}
}
return($bter_nbt_cny_24);
}

function get_bter_nbt_btc() {
//bter NBT/BTC
$url = 'http://data.bter.com/api/1/ticker/nbt_btc';
$content = file_get_contents($url);
$json = json_decode($content, true);

if(!check_value($json["vol_nbt"]))
	{
	writelog("bter_nbt_btc", "querry_error", "unresolved"); 
	$bter_nbt_btc_24 ="-1";		
	}
	else {
	if ($json["vol_nbt"]=="0.00000000")
		{	
		$bter_nbt_btc_24="0";
		}
	else
		{
		$bter_nbt_btc_24=$json["vol_nbt"];
		}
}
return($bter_nbt_btc_24);
}

function get_ccedk_nbt_usd() {
//ccedk.. oh boy
//NBT/USD
$url = 'https://www.ccedk.com/api/v1/stats/marketdepthfull?pair_id=46';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["response"]["entity"]["vol"]))
	{
	writelog("ccedk_nbt_usd", "querry_error", "unresolved"); 
	$ccedk_nbt_usd_24 ="-1";		
	}
	else {
if ($json["response"]["entity"]["vol"]=="0.00000000")
	{	
	$ccedk_nbt_usd_24="0";
	}
else {
	$ccedk_nbt_usd_24=$json["response"]["entity"]["vol"];
	}
}
return($ccedk_nbt_usd_24);
}

function get_ccedk_nbt_btc() {
//ccedk.. NBT/BTC
$url = 'https://www.ccedk.com/api/v1/stats/marketdepthfull?pair_id=47';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["response"]["entity"]["vol"]))
	{
	writelog("ccedk_nbt_btc", "querry_error", "unresolved"); 
	$ccedk_nbt_btc_24 ="-1";		
	}
	else {
if ($json["response"]["entity"]["vol"]=="0.00000000")
	{	
	$ccedk_nbt_btc_24="0";
	}
else {
	$ccedk_nbt_btc_24=$json["response"]["entity"]["vol"];
	}
}
return($ccedk_nbt_btc_24);
}

function get_ccedk_nbt_ppc() {
//ccedk.. NBT/PPC
$url = 'https://www.ccedk.com/api/v1/stats/marketdepthfull?pair_id=48';
$content = file_get_contents($url);
$json = json_decode($content, true);
if (!check_value($json["response"]["entity"]["vol"]))
	{
	writelog("ccedk_nbt_ppc", "querry_error", "unresolved"); 
	$ccedk_nbt_ppc_24 ="-1";		
	}
	else {
if ($json["response"]["entity"]["vol"]=="0.00000000")
	{	
	$ccedk_nbt_ppc_24="0";
	}
else {
	$ccedk_nbt_ppc_24=$json["response"]["entity"]["vol"];
	}
}
return($ccedk_nbt_ppc_24);
}

function get_ccedk_nbt_eur() {
//ccedk.. NBT/EUR
$url = 'https://www.ccedk.com/api/v1/stats/marketdepthfull?pair_id=49';
$content = file_get_contents($url);
$json = json_decode($content, true);
if (!check_value($json["response"]["entity"]["vol"]))
	{
	writelog("ccedk_nbt_eur", "querry_error", "unresolved"); 
	$ccedk_nbt_eur_24 ="-1";		
	}
	else {
if ($json["response"]["entity"]["vol"]=="0.00000000")
	{	
	$ccedk_nbt_eur_24="0";
	}
else {
	$ccedk_nbt_eur_24=$json["response"]["entity"]["vol"];
	}
}
return($ccedk_nbt_eur_24);
}

//HitBTC NBT/BTC
function get_hitbtc_nbt_btc() {
$url = 'https://api.hitbtc.com/api/1/public/NBTBTC/ticker';
$content = file_get_contents($url);
$json = json_decode($content, true);

if (!check_value($json["volume"]))
	{
	writelog("hitbtc_nbt_btc", "querry_error", "unresolved"); 
	$hitbtc_nbt_btc_24 ="-1";		
	}
	else {
if ($json["volume"]=="0.000")
	{	
	$hitbtc_nbt_btc_24="0";
	}
else {
	$hitbtc_nbt_btc_24=$json["volume"];
	}
}
return($hitbtc_nbt_btc_24);
}

//NuLagoon BTC/NBT
function get_nulagoon_btc_nbt()
{
$url_price = 'https://raw.githubusercontent.com/henrynu/NlgTube/master/data/rd.json';
$content_price = file_get_contents($url_price);
$price_json = json_decode($content_price, true); 

if (!check_value($price_json["24vol"][0]))
	{
	writelog("nulagoon_btc_nbt", "querry_error", "unresolved"); 
	$nulagoon_btc_nbt_24="-1";	
 	}
else {
	$nulagoon_btc_nbt_24=$price_json["24vol"][0];
	}

return($nulagoon_btc_nbt_24);
}


function query_exchanges()
{
$file = 'data/alix_data.dat';
$btc_usd=get_btc_usd();

$poloniex_btc_nbt_24=get_poloniex_btc_nbt();
$bittrex_btc_nbt_24=get_bittrex_btc_nbt();
$southx_btc_nbt_24=get_southx_btc_nbt($btc_usd);
$southx_nbt_usd_24=get_southx_nbt_usd();
#$cryptsy_nbt_btc_24=get_cryptsy_nbt_btc(); - defunc
#$cryptsy_nbt_usd_24=get_cryptsy_nbt_usd(); - defunc
$bter_nbt_cny_24=get_bter_nbt_cny();
$bter_nbt_btc_24=get_bter_nbt_btc();
$ccedk_nbt_usd_24=get_ccedk_nbt_usd();
$ccedk_nbt_btc_24=get_ccedk_nbt_btc();
$ccedk_nbt_ppc_24=get_ccedk_nbt_ppc();
$ccedk_nbt_eur_24=get_ccedk_nbt_eur();
$hitbtc_nbt_btc_24=get_hitbtc_nbt_btc();	
$nulagoon_btc_nbt_24=get_nulagoon_btc_nbt();

//write exchanges into alix_data.dat
$data=array(
'timestamp' => time(),
'btc_usd' => "$btc_usd",
'exchanges' => array(
array('pair'=>'poloniex_btc_nbt', 'amount' => "$poloniex_btc_nbt_24"),
array('pair'=>'bittrex_btc_nbt', 'amount' => "$bittrex_btc_nbt_24"),
array('pair'=>'southx_btc_nbt', 'amount' => "$southx_btc_nbt_24"),
array('pair'=>'southx_nbt_usd', 'amount' => "$southx_nbt_usd_24"),
#array('pair'=>'cryptsy_nbt_btc', 'amount' => "$cryptsy_nbt_btc_24"), - defunc
#array('pair'=>'cryptsy_nbt_usd', 'amount' => "$cryptsy_nbt_usd_24"), - defunc
array('pair'=>'bter_nbt_cny', 'amount' => "$bter_nbt_cny_24"),
array('pair'=>'bter_nbt_btc', 'amount' => "$bter_nbt_btc_24"),
array('pair'=>'ccedk_nbt_usd', 'amount' => "$ccedk_nbt_usd_24"),
array('pair'=>'ccedk_nbt_btc', 'amount' => "$ccedk_nbt_btc_24"),
array('pair'=>'ccedk_nbt_ppc', 'amount' => "$ccedk_nbt_ppc_24"),
array('pair'=>'ccedk_nbt_eur', 'amount' => "$ccedk_nbt_eur_24"),
array('pair'=>'hitbtc_nbt_btc', 'amount' => "$hitbtc_nbt_btc_24"),
array('pair'=>'nulagoon_btc_nbt', 'amount' => "$nulagoon_btc_nbt_24")
)
);
$new_json = json_encode($data);

$current = file_get_contents($file);
$current .= $new_json."\n";
file_put_contents($file, $current,  PHP_EOL | LOCK_EX);

}
?>