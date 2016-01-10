<?php
include("logger.php");
function check_value($value)
{
$return=true;
if (!is_numeric($value)) { $return=false;}

return ($return);
}

function get_nulagoon_btc_nbt()
{
$url_price = 'https://bitbucket.org/henry_nu/data/downloads/rd.json';
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

$jop=get_nulagoon_btc_nbt();

print_r($jop);
?>