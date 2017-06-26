<?php
require("../link.php");
include("../seguro.php");
$var=security($_POST["e"]);
$var1=security($_POST["m"]);
$var2=security($_POST["c"]);
mysql_select_db("recargas", $link) or die ("Problemas...");
mysql_set_charset('utf8');
$sql="SELECT DISTINCT(colonia), codigo FROM recargas.sepomex WHERE estado='".$var."' AND municipio='".$var1."' AND ciudad='".$var2."' ORDER BY colonia";
$result = mysql_query($sql, $link);
$colonias = [];

if ($row = mysql_fetch_array($result)){

	do{
		// Crear un array por cada resultado poder generar Json como una lista de objectos
		$colonia[] = array('code' => _POST$row["codigo"], 'name' => $row["colonia"]);
	}while ($row = mysql_fetch_array($result));
	echo json_encode($ciudades);
}
?>