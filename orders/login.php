<?php
session_start();

require_once("init.php");
//require( "../model/connection.php");

$username = mysql_real_escape_string($_POST['username']);
$password = md5($_POST['password']);

$query = "select id from users where Name='$username' and Password='$password'";
//$q_result = mysql_query($query, $connection) or die(mysql_error());
$q_result = $connection->executeQuery($query);
$rows = mysql_num_rows($q_result);
if ($rows == 1) {
    header("Location:index.php");
    $_SESSION['login_status']=true;
} else {
    echo("Incorrect login or password");
}

