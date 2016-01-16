<?php
include('config.php');
include('logger.php');
include('exchanges.php');
include("functions.php");
#error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$btc_usd=get_btc_usd();
$nbt_cny=get_nbt_cny($btc_usd);
$nbt_eur=get_nbt_eur();

if (!$btc_usd)
{
writelog("all", "no_bitcoin_price", "unresolved"); 
exit; #no bitcoin price available, sorry. will not gather data (offline?)
}

$query=query_exchanges($global_tolerance,$btc_usd,$nbt_cny,$nbt_eur);
file_put_contents($data_munched, $query, LOCK_EX);

#create chart points
include("../charts/createcsv_walls.php");

?>