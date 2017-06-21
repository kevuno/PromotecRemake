<?php
/*$link=mysql_connect($_SERVER["serverdata"],"samtec","sam33");
mysql_set_charset('utf8',$link);*/
$link = new mysqli($_SERVER["serverdata"],"samtec","sam33");
$link->set_charset("utf8");
?>