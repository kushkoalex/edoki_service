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
            var oldIndex;
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

            $(".editable").click(function () {
                if (!editing) {
                    oldValue = this.innerText;
                    this.innerText = '';
                    var elem = document.createElement('textarea');
                    elem.innerText = oldValue;
                    this.appendChild(elem);
                    elem.focus();
                    editing = true;
                    currentElem = this;
                    $("#buttons").css("display", "block");
                }
            });


            $("#savebtn").click(function () {
                if (editing) {
                    var orderId = currentElem.getAttribute('orderid');

                    var classValue = currentElem.getAttribute('class');
                    var editableField;
                    if (classValue.indexOf('address') > -1) {
                        editableField = "address";
                    } else if (classValue.indexOf('description') > -1) {
                        editableField = "description";
                    } else if (classValue.indexOf('status') > -1) {
                        editableField = "status";
                    }
                    var newValue;

                    if (editableField == "status") {

                        newValue = $(currentElem).children(":first")[0].value;
                        console.log(newValue);
                        switch (newValue)
                        {
                            case '0':
                                currentElem.innerHTML ="Новый";
                                break;
                            case '1':
                                currentElem.innerHTML ="Обработан";
                                break;
                        }
                        //currentElem.innerHTML = newValue;
                    }
                    else {
                        newValue = $(currentElem).children(":first")[0].value;
                        currentElem.innerHTML = newValue;
                    }

                    if (editableField == 'status') {
                        $.post("updateorder.php", {text: newValue, id: orderId, field: editableField });
                    }
                    else if (oldValue != newValue) {
                        $.post("updateorder.php", {text: newValue, id: orderId, field: editableField });
                    }
                    editing = false;
                    $("#buttons").css("display", "none");
                    //return false;
                }
            });

            $("#cancelbtn").click(function () {
                if (editing) {
                    currentElem.innerHTML = oldValue;
                    editing = false;
                    $("#buttons").css("display", "none");
                    //return false;
                }
            });


            $(".status").click(function (e) {

//                //console.log(e.clientX);
//                console.log(this.offsetTop);
//                //$("#contextMenu").css("left",e.screenX+"px");
//                //$("#contextMenu").css("top",e.screenY+"px");
//                $("#contextMenu").css("left",e.clientX+"px");
//                $("#contextMenu").css("top",e.clientY+"px");
//
//
//                $("#contextMenu").fadeIn();


                if (!editing) {

                    oldValue = this.innerText;

                    switch (oldValue) {
                        case "Новый":
                            oldIndex = 0;
                            break;
                        case "Обработан":
                            oldIndex = 1;
                            break;
                    }

                    var elem = document.createElement('select');


                    var opt = document.createElement('option');
                    var att = document.createAttribute("value");
                    att.value = "0";
                    opt.setAttributeNode(att);
                    opt.innerText = "Новый";
                    elem.appendChild(opt);
                    opt = document.createElement('option');
                    att = document.createAttribute("value");
                    att.value = "1";
                    opt.setAttributeNode(att);
                    opt.innerText = "Обработан";
                    elem.appendChild(opt);

                    this.innerText = "";

                    elem.selectedIndex = oldIndex;

                    att = document.createAttribute("class");
                    att.value = "status";
                    elem.setAttributeNode(att);
                    this.appendChild(elem);

                    editing = true;


                    currentElem = this;
                    $("#buttons").css("display", "block");
                }
            });


        });
    </script>
</head>
<body>


<div class="ordersWrapper">
    <div class="adminLinksPanel">
        <a href="logout.php">Выйти</a>
    </div>

    <table>
        <tr class="thead">
            <th>Номер заказа</th>
            <th>Страница</th>
            <th>Номер телефона</th>
            <th>Адрес</th>
            <th>Дата заказа</th>
            <th>Примечания</th>
            <th class="status">Статус</th>
            <th></th>
        </tr>
        <?
        foreach ($orders as $order) {
            ?>
            <tr>
                <td><?= $order->id ?></td>
                <td><?= $order->dishname ?></td>
                <td><?= $order->phone ?></td>
                <td class="editable address" id="address_<?= $order->id ?>"
                    orderid="<?= $order->id ?>"><?= $order->address ?></td>
                <td><?= $order->date ?></td>
                <td class="editable description" orderid="<?= $order->id ?>"><?= $order->description ?></td>
                <td class="editableSelect status" orderid="<?= $order->id ?>"><?
                    switch ($order->status) {
                        case 0:
                            echo 'Новый';
                            break;
                        case 1:
                            echo 'Обработан';
                            break;

                    }
                    $order->status
                    ?></td>
                <td><a href="?action=delete&id=<?= $order->id ?>" onclick="return confirm('Удалить заказ?')">удалить</a>
                </td>
            </tr>
        <? } ?>

    </table>
    <div id="buttons">
        <input type="button" value="Сохранить" id="savebtn">
        <input type="button" value="Отмена" id="cancelbtn">
    </div>

</div>
<!--<form action="index.php" method="post">-->
<!--    <label for="dishname">dishname</label><input type="text" id="dishname" name="dishname"><br>-->
<!--    <label for="address">address</label><input type="text" id="address" name="address"><br>-->
<!--    <label for="phone">phone</label><input type="text" id="phone" name="phone"><br>-->
<!--    <input type="submit" value="Save">-->
<!--</form>-->


</body>
</html>








