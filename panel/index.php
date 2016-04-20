<?php
include("static/header.html"); 
$query = http_build_query($_GET);

if(!isset($_GET["load"])) {
$selected_page="load=liquidity";
}

else { $selected_page=$query; }
//sidebar
?>
<div id="sidebar" style="display: block;">
<table class ='t01'>
<?php
echo"<th>ALix Panel</th></tr><th>Liquidity</tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('load=liquidity')\";><p class=\"black_link\">Network Liquidity</p></a></td></tr>";
echo"<th>Voting</th></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('load=current_motions')\";><p class=\"black_link\">Motions</p></a></td></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('load=current_custodians')\";><p class=\"black_link\">Custodians</p></a></td></tr>";
echo"<td><a href=\"#\" onclick=\"setSelect('load=elected_custodians')\";><p class=\"black_link\">Elected Custodians</p></a></td></tr>";
echo "</table>";
echo "</div>"; #end of sidebar
?>
<div class="container-fluid" id="container" style="margin: 50px; text-align: center;">
	<div class="col-md-12">
		<?php echo "<script>setSelect(\"$selected_page\"); </script>"; ?>
	</div>
</div>
<footer align="center">
	<a href="https://alix.coinerella.com/walls"><p class="footer_link">ALix Walls -</p></a>
	<a href="https://alix.coinerella.com/volume"><p class="footer_link"> ALix Volume -</p></a>
	<a href="https://alix.coinerella.com/charts"><p class="footer_link"> ALix Charts -</p></a>
	<a href="https://alix.coinerella.com/panel"><p class="footer_link"> ALix Panel -</p></a>
	<a href="https://www.coinerella.com/index.php/ALix" target="_blank"><p class="footer_link"> About ALix</p></a>
</footer>
</body>
</html>