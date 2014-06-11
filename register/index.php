<?php
session_start();
require_once("../init.php");
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysql_real_escape_string($_POST['username']);
    $password = md5($_POST['password']);
    $query = "insert into users (Name, Password) values('$username', '$password')";
    $connection->executeNonQuery($query);
    header("Location: ../orders/login.html");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>register</title>
</head>
<body>
<form action="index.php" method="post">
    <label> login
        <input type="text" name="username">
    </label><br>
    <label>password
        <input type="password" name="password">
    </label><br>
    <input type="submit" value="Register">
</form>
</body>
</html>