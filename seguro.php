<?
//include_once("imysql.php");
function imysql_real_escape_string($cad){

 return $cad;
}
function security($cad) {
  //$securxxx = mysql_connect($_SERVER["serverdata"],"hugo","hcmsoft");
  // revisamos que no tenga comillas
  $cad1=str_replace("'","",$cad);
  $cad1=str_replace('"','',$cad1);
  $cad1=str_replace('%','',$cad1);
  $cad1=str_replace('select','',$cad1);
  $cad1=str_replace('insert','',$cad1);
  $cad1=str_replace('delete','',$cad1);
  $cad1=str_replace('update','',$cad1);
  $cad1=str_replace('show','',$cad1);
  $cad2 = htmlspecialchars(trim(addslashes(strip_tags($cad1))));  
  $cad1=imysql_real_escape_string(addslashes($cad2));
  $cad2=htmlentities ($cad1);
  $cad1=trim(($cad2));
  return $cad1;
}
?>