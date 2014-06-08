<?php
session_start();
if (!isset($_SESSION['login_status'])) {
    header("Location:login.html");
}
require "init.php";

if (isset($_POST['dishname'])
    && isset($_POST['address'])
    && isset($_POST['phone'])
) {

    $dishname = $_POST['dishname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $query = "insert into orders (dishname, phone, address,date) value('$dishname','$phone','$address',now())";
    mysql_query($query, $connection) or die(mysql_error());
}else
{
    echo("variables arent set");
}


$query = "select * from orders";
$result = mysql_query($query, $connection) or die(mysql_error());
?>

<table>
    <?
    while ($row = mysql_fetch_object($result)) {
        ?>
        <tr><?
        echo "<td>" . $row->id . "</td>";
        echo "<td>" . $row->dishname . "</td>";
        echo "<td>" . $row->phone . "</td>";
        ?></tr><?
    }
    ?>
</table>

<form action="index.php" method="post">
    <label for="dishname">dishname</label><input type="text" id="dishname" name="dishname"><br>
    <label for="address">address</label><input type="text" id="address" name="address"><br>
    <label for="phone">phone</label><input type="text" id="phone" name="phone"><br>
    <input type="submit" value="Save">
</form>

