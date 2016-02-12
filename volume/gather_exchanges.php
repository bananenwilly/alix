<?php
$file = 'data/alix_data.dat';
include('logger.php');
include('exchanges.php');
#error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$btc_usd=get_btc_usd();

if (!$btc_usd)
{
writelog("all", "no_bitcoin_price", "unresolved"); 
exit; #no bitcoin price available, sorry. will not gather data (offline?)
}

query_exchanges();
sleep(300);
include("write_data.php");
sleep(30);
include("parse_json.php");
sleep(5);
include("createcsv_volume.php");
?>