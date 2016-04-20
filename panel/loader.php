<?php
$site_to_load=$_GET["load"];
$query = http_build_query($_GET);
include("$site_to_load.php");

echo "<div align=\"right\">";
echo "<a href=\"?$query\">link to this site</a>";
echo "</div>";
?>