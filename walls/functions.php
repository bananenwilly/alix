<?php

function sort_index ($a, $b)
{
    return $b['amount']['total'] - $a['amount']['total'];
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
        case "nulagoon_btc_nbt":
        $answer = "<a href='https://nulagoon.com/' target='_blank'>NuLagoon Tube BTC/NBT</a>";
        break;
    }

return $answer;
}

function file_get_tail ($file, $n_lines)
{
    $before = microtime(true);

    $array=file($file); #less optimal the larger the file gets
    $end_line=count($array);
    $array=array_reverse($array);

    for ($i=0; $i < $n_lines; $i++)
    {
        $new_array[]=$array[$i];
    }

    return($new_array);
}

function get_avg ($number_of_lines)
{
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    include("config.php");
    $vars=array();

    $last_n=file_get_tail($data_file,$number_of_lines);

    foreach($last_n as $json_line)
    {
    $json_array=json_decode($json_line, true);
    foreach($json_array["exchanges"] as $exchange)
     {
        $pair_name=$exchange["pair"];
        $pair_ask=$exchange["amount"]["ask_total"];
        $pair_bid=$exchange["amount"]["bid_total"];
        $pair_total=$exchange["amount"]["total"];
        
        $ask_new=$pair_ask+$vars["$pair_name"."_ask"];
        $bid_new=$pair_bid+$vars["$pair_name"."_bid"];
        $total_new=$pair_total+$vars["$pair_name"."_total"];

        $new_array=array(
        "$pair_name"."_ask" =>"$ask_new",
        "$pair_name"."_bid" => "$bid_new",
        "$pair_name"."_total" => "$total_new"
        );

        $vars=array_replace_recursive($vars, $new_array);
        
     }
    }

    $n_rec=count($last_n);

    foreach($vars as $key=>$pair)
    {   

     $pair_new=$pair/$n_rec;

     $new_array=array(
     "$key" =>"$pair_new",
     );
    $vars=array_replace_recursive($vars, $new_array);
    }

    $ma_array=array("moving_avg_minutes" => $number_of_lines);
    $vars=array_replace_recursive($vars, $ma_array);

    return($vars);
}

function calculate_ma($pathtofile)
{
$file=json_decode(file_get_contents($pathtofile),true);
$total_ask=0;
$total_bid=0;
$total_both=0;

foreach($file as $key=>$line)
{
    $key_ex=explode("_", $key);
    
    if(isset($key_ex[3]))
    {
        if($key_ex[3]=="ask")
        {
            $total_ask=$total_ask+$line;
        }
        if($key_ex[3]=="bid")
        {
            $total_bid=$total_bid+$line;
        }   
        if($key_ex[3]=="total")
        {
            $total_both=$total_both+$line;
        }       
    }
}

$total_ask_percent=round(($total_ask/$total_both)*100, 2);
$total_bid_percent=round(($total_bid/$total_both)*100, 2);
$total_both_percent=$total_ask_percent+$total_bid_percent;

$answer_array=array(
'total_ask' => $total_ask,
'total_bid' => $total_bid,
'total_both' => $total_both,
'total_ask_percent' => $total_ask_percent,
'total_bid_percent' => $total_bid_percent,
'total_both_percent' => $total_both_percent
);

return($answer_array);
}


?>
