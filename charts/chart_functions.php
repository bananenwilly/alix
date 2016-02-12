<?php
function chart_filename_to_labels($pairname)
{
    switch($pairname)
    {
        case "4_hours_all_nbt":
        $answer = array("title" => "All exchanges over 4 hours ", "liquidity_label" => "Liquidity (NBT)", "roller" => "true", "roll_period" => "5");
        break;   
        case "48_hours_all_nbt":
        $answer = array("title" => "All exchanges over 48 hours ", "liquidity_label" => "Liquidity (NBT)", "roller" => "true", "roll_period" => "30");
        break;
        case "48_hours_nupool_poloniex":
        $answer = array("title" => "NuPool - Poloniex over 48 hours ", "liquidity_label" => "Liquidity (NBT)", "roller" => "true", "roll_period" => "35");
        break;
        case "4h_4h_15min_combined_ma_percent":
        $answer = array("title" => "All exchanges over 4 hours MA combined", "liquidity_label" => "Liquidity (Percent)", "roller" => "false", "roll_period" => "0");
        break;
        case "48h_4h_15min_combined_ma_percent":
        $answer = array("title" => "All exchanges over 48 hours MA combined ", "liquidity_label" => "Liquidity (Percent)", "roller" => "false", "roll_period" => "0");
        break;
        case "360d_volume":
        $answer = array("title" => "ALix Volume MA historic", "liquidity_label" => "ALix (NBT)", "roller" => "false", "roll_period" => "0");
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

        case "360d_volume":
        $answer=array("ALix 1", "ALix 3", "ALix 7","ALix 30", "ALix 90");
        break;
    }

    return $answer;
}
?>