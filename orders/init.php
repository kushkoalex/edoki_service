<?php
require_once "config.php";
$connection = mysql_connect("mysql301.1gb.ua", "gbua_dev_edoki", "ef0302c10") or die(mysql_error());
mysql_select_db("gbua_dev_edoki", $connection);