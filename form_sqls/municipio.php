<?php
require("link.php");
include("seguro.php");
$var=security($_GET["e"]);
?>
<label for="ciud"><small>Ciudad:</small></label>
<select class="form-control input-sm" name="ciud" id="ciud">
  <option value="" selected>Ciudad...</option>
  <?
	mysqli_select_db($link, "recargas") or die ("Problemas...");
	mysqli_set_charset('utf8');
	$sql="SELECT DISTINCT(municipio) FROM recargas.sepomex WHERE estado='".$var."' ORDER BY municipio";
	
	$result = mysqli_query($link, $sql);
	if ($row = mysqli_fetch_array($result)) {
  	do {
	  	echo '<option value="'.$row["municipio"].'">'.$row["municipio"].'</option>';
	  } while ($row = mysqli_fetch_array($result));
	}
	?>
</select>
