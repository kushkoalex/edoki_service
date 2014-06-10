<?php
session_start();
if (!isset($_SESSION['login_status'])) {
    header("Location:login.html");
}

require_once "../init.php";
require_once "../model/order.php";
require_once "../model/orderfactory.php";
require_once "../model/mailhelper.php";

$orderFactory = new \model\OrderFactory($connection);


if (isset($_POST['dishname']) && isset($_POST['address']) && isset($_POST['phone'])) {

    $order = new \model\Order();
    $order->dishname = $_POST['dishname'];
    $order->address = $_POST['address'];
    $order->phone = $_POST['phone'];

    $orderFactory->save($order);
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $order = $orderFactory->getOrderById($_GET['id']);
    $orderFactory->delete($order);
}



$orders = $orderFactory->getAllOrders();

?>

<table border="1" style="border-collapse: collapse;">
    <tr>
        <th>id</th>
        <th>page</th>
        <th>phone</th>
        <th>address</th>
        <th>date</th>
        <th></th>
    </tr>
    <?
    foreach ($orders as $order) {
        ?>
        <tr><?
        echo "<td>" . $order->id . "</td>";
        echo "<td>" . $order->dishname . "</td>";
        echo "<td>" . $order->phone . "</td>";
        echo "<td>" . $order->address . "</td>";
        echo "<td>" . $order->date . "</td>";
        echo "<td><a href=\"?action=delete&id=" . $order->id . "\" onclick=\"return confirm('Удалить заказ?')\">удалить</a></td>";
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

