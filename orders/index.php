<?php
session_start();
if (!isset($_SESSION['login_status'])) {
    header("Location:login.html");
}
require_once "init.php";
require_once "../model/order.php";
require_once "../model/connection.php";


$conn = new \model\Connection($db_host,$db_login,$db_password,$db_name);
echo("conn:". $conn);


if (isset($_POST['dishname'])
    && isset($_POST['address'])
    && isset($_POST['phone'])
) {







    $order = new \model\Order($conn);
    $order->dishname = $_POST['dishname'];
    $order->address = $_POST['address'];
    $order->phone = $_POST['phone'];
    $order->Save();

//    $dishname = $_POST['dishname'];
//    $address = $_POST['address'];
//    $phone = $_POST['phone'];

//    $query = "insert into orders (dishname, phone, address,date) value('$dishname','$phone','$address',now())";
//    mysql_query($query, $connection) or die(mysql_error());
}


//$query = "select * from orders";
//$result = mysql_query($query, $conn) or die(mysql_error());


//$sendmail = mail("kushko.alex@gmail.com", "subj1", "textttttt", "From: edoki\n");

$orders = \model\Order::getAllOrders($conn);

?>

<table>
    <?
    foreach($orders as $order) {
        ?>
        <tr><?
        echo "<td>" . $order->id . "</td>";
        echo "<td>" . $order->dishname . "</td>";
        echo "<td>" . $order->phone . "</td>";
        echo "<td>" . $order->date . "</td>";
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

