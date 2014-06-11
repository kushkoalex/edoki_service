<?php
session_start();
if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] == false) {
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
<!DOCTYPE html>
<html>
<head>
    <title>Edoki - Заказы</title>
    <link rel="stylesheet" href="css/main.css?v=0.0.11">
    <script type="text/javascript" src="../vendor/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var editing = false;
            var currentElem;
            var oldValue;
            $("#link1").click(function () {

                alert("hello!");

                var order = {};
                order.phone = "123123";
                order.from = "page1";

                $.ajax({
                    url: "/service/makeorder.php",
                    contentType: "application/json",
                    accepts: "application/json",
                    type: "POST",
                    data: JSON.stringify(order),
                    success: function (message) {
                        alert("Спасибо за заказ!\r\n Едоки свяжутся с Вами в ближайшее время :)");
                    },

                    error: function () {
                        alert("Ошибка при отправке запроса");
                    }
                });
            });

            $(".address").click(function () {
                if (!editing) {
                    oldValue = this.innerText;
                    this.innerText = '';
                    var elem = document.createElement('textarea');
                    elem.innerText = oldValue;
                    this.appendChild(elem);
                    elem.focus();
                    editing = true;
                    currentElem = this;
                    $("#buttons").css("display","block");
                }
            });


            $("#savebtn").click(function () {
                if (editing) {

                    var orderid = currentElem.getAttribute('orderid');
                    var newValue = $(currentElem).children(":first")[0].value
                    currentElem.innerHTML = newValue;
                    $.post("updateorder.php", {text: newValue, id: orderid });
                    editing = false;
                    $("#buttons").css("display","none");
                    return false;
                }
            });

            $("#cancelbtn").click(function () {
                if (editing) {
                    currentElem.innerHTML = oldValue;
                    editing = false;
                    $("#buttons").css("display","none");
                    return false;
                }
            });


        });
    </script>
</head>
<body>

<a href="logout.php">Выйти</a>


<table>
    <tr>
        <th>Номер заказа</th>
        <th>Страница</th>
        <th>Номер телефона</th>
        <th>Адрес</th>
        <th>Дата заказа</th>
        <th></th>
    </tr>
    <?
    foreach ($orders as $order) {
        ?>
        <tr>
            <td><?= $order->id ?></td>
            <td><?= $order->dishname ?></td>
            <td><?= $order->phone ?></td>
            <td class="address" id="address_<?=$order->id?>" orderid="<?= $order->id ?>"><?= $order->address ?></td>
            <td><?= $order->date ?></td>
            <td><a href="?action=delete&id=<?= $order->id ?>" onclick="return confirm('Удалить заказ?')">удалить</a>
            </td>
        </tr>
    <? } ?>
</table>
<div id="buttons">
    <input type="button" value="Сохранить" id="savebtn">
    <input type="button" value="Отмена" id="cancelbtn">
</div>


<!--<form action="index.php" method="post">-->
<!--    <label for="dishname">dishname</label><input type="text" id="dishname" name="dishname"><br>-->
<!--    <label for="address">address</label><input type="text" id="address" name="address"><br>-->
<!--    <label for="phone">phone</label><input type="text" id="phone" name="phone"><br>-->
<!--    <input type="submit" value="Save">-->
<!--</form>-->

</body>
</html>








