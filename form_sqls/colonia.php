<?php
require("link.php");
include("seguro.php");
$var=security($_GET["e"]);
$var1=security($_GET["m"]);
$var2=security($_GET["c"]);
?>
<label for="colo">Colonia: </label>
<select class="form-control input-sm" name="colo" id="colo" onChange='rcodigo();'>
	<option value="" selected>Colonia...</option>
  <?
	mysql_select_db("recargas", $link) or die ("Problemas...");
	mysql_set_charset('utf8');
	$sql="SELECT DISTINCT(colonia), codigo FROM recargas.sepomex WHERE estado='".$var."' AND municipio='".$var1."' AND ciudad='".$var2."' ORDER BY colonia";
	$result = mysql_query($sql, $link);
	if ($row = mysql_fetch_array($result)) {
  do {
	  echo '<option value="'.$row["codigo"]."|".$row["colonia"].'">'.$row["colonia"].'</option>';
	    }while ($row = mysql_fetch_array($result));
	}
	?>
</select>