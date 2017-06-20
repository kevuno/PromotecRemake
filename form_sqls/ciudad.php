<?php
require("link.php");
include("seguro.php");
$var=security($_GET["e"]);
$var1=security($_GET["m"]);
?>
<label for="ciud">Ciudad: </label>
<select class="form-control input-sm" name="ciud" id="ciud" onChange='rcol();'>
	<option value="" selected>Ciudad...</option>
  <?
	mysql_select_db("recargas", $link) or die ("Problemas...");
	mysql_set_charset('utf8');
	$sql="SELECT DISTINCT(ciudad) FROM recargas.sepomex WHERE estado='".$var."' AND municipio='".$var1."' ORDER BY ciudad";
	$result = mysql_query($sql, $link);
	if ($row = mysql_fetch_array($result)) {
  do {
	  echo '<option value="'.$row["ciudad"].'">'.$row["ciudad"].'</option>';
	    }while ($row = mysql_fetch_array($result));
	}
	?>
</select>