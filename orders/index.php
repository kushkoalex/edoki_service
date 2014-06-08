<?php
session_start();
if (!isset($_SESSION['login_status'])) {
    header("Location:login.html");
}
require "init.php";
$query = "select * from orders";
$result = mysql_query($query, $connection);
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




