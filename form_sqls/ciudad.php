<?php
require("../link.php");
include("../seguro.php");
$var=security($_POST["e"]);
$var1=security($_POST["m"]);
mysql_select_db("recargas", $link) or die ("Problemas...");
mysql_set_charset('utf8');
$sql="SELECT DISTINCT(ciudad) FROM recargas.sepomex WHERE estado='".$var."' AND municipio='".$var1."' ORDER BY ciudad";
$result = mysql_query($sql, $link);
$ciudades = [];

if($row = mysql_fetch_array($result)){
	do{
		// Añadir al array para poder generar codigo json a la vista
	  	$ciudades[] = $row["ciudad"];
	} while ($row = mysql_fetch_array($result));
	echo json_encode($ciudades);
}
	
?>