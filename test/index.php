<?php
require_once "../orders/init.php";


echo($connection."<hr>");

//require_once "../orders/config.php";
$query = "select curdate() as 'curdate'";
//$conn = mysql_connect($db_host, $db_login, $db_password);
//mysql_select_db($db_name, $connection);

//$result = mysql_query($query, $connection->link);
$result = $connection->executeQuery($query);
//$connection->executeNonQuery($query);


//while($row = mysql_fetch_object($result))
//{
//    echo $row->curdate . "<br />";
//}