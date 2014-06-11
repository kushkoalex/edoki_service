<?php
session_start();
require_once("../init.php");
$username = mysql_real_escape_string($_POST['username']);
$password = md5($_POST['password']);
echo($username);
echo($password);
$query = "insert into users (Name, Password) values('$username', '$password')";
mysql_query($query, $connection) or die(mysql_error());
header("Location:login.html");
