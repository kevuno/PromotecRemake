<?php
/*$link=mysql_connect($_SERVER["serverdata"],"samtec","sam33");
mysql_set_charset('utf8',$link);*/
$ip = "52.42.194.160"; //$_SERVER["serverdata"],
$link = new mysqli($ip,"samtec","sam33");
$link->set_charset("utf8");
?>