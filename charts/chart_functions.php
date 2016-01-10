<?php
function chart_pair2link($pairname)
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
    }
return $answer;
}

function chart_filename_to_labels($pairname)
{
    switch($pairname)
    {
        case "4_hours_all_percent":
        $answer = array("title" => "All exchanges over 4 hours in percent ", "liquidity_label" => "Liquidity (Percent)", "roller" => "true", "roll_period" => "5");
        break;
        case "4_hours_all_nbt":
        $answer = array("title" => "All exchanges over 4 hours ", "liquidity_label" => "Liquidity (NBT)", "roller" => "true", "roll_period" => "5");
        break;
        case "48_hours_all_percent":
        $answer = array("title" => "All exchanges over 48 hours in percent ", "liquidity_label" => "Liquidity (Percent)", "roller" => "true", "roll_period" => "30");
        break;
        case "48_hours_all_nbt":
        $answer = array("title" => "All exchanges over 48 hours ", "liquidity_label" => "Liquidity (NBT)", "roller" => "true", "roll_period" => "30");
        break;
        case "4h_4h_15min_combined_ma_percent":
        $answer = array("title" => "All exchanges over 4 hours MA combined", "liquidity_label" => "Liquidity (Percent)", "roller" => "false", "roll_period" => "0");
        break;
        case "48h_4h_15min_combined_ma_percent":
        $answer = array("title" => "All exchanges over 48 hours MA combined ", "liquidity_label" => "Liquidity (Percent)", "roller" => "false", "roll_period" => "0");
        break;
        
    }
return $answer;
}

function get_display_buttons($value)
{
    switch($value)
    {
        case "standard":
        $answer=array("Sell", "Buy", "Total");
        break;

        case "combined_ma":
        $answer=array("Sell MA/4h", "Buy MA/4h", "Sell MA/15min", "Buy MA/15min", "Total");
        break;
    }

    return $answer;
}
?>