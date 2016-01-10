<?php
include("config.php");
$content = file_get_contents($data_munched);
$data = json_decode($content, true);


function sort_by_order ($a, $b)
{
    return $b['amount']['total'] - $a['amount']['total'];
}

usort($data["exchanges"], 'sort_by_order');

print_r($data);
?>