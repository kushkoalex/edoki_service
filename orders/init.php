<?php
require_once "config.php";
require_once "../model/connection.php";
$conn = new \model\Connection($db_host, $db_login, $db_password,$db_name);

//$connection = mysql_connect($db_host, $db_login, $db_password) or die(mysql_error());
//mysql_select_db($db_name, $connection);