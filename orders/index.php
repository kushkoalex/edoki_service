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


if (isset($_POST['page']) && isset($_POST['phone'])) {

    $order = new \model\Order();
    $order->dishname = $_POST['page'];
    $order->address = $_POST['address'];
    $order->phone = $_POST['phone'];
    $order->description = $_POST['description'];
    $orderFactory->save($order);
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $order = $orderFactory->getOrderById($_GET['id']);
    if ($order != null) {
        $orderFactory->delete($order);
    }
}


$orders = $orderFactory->getAllOrders();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edoki - Заказы</title>
    <link rel="stylesheet" href="css/main.css?v=0.0.14">
    <script type="text/javascript" src="../vendor/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var editing = false;
            var currentElem;
            var oldValue;
            var oldIndex;



//            function getNewOrders() {
//
//                if (!editing) {
//                    console.log("started");
//                    $.ajax({
//                        url: "/service/getorders.php",
//                        cache: false,
//                        success: function (html) {
//                            console.log("ok");
//                            $("#orders").html(html);
//                        }
//                    });
//                }
//            }


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
                        switch (newValue) {
                            case '0':
                                currentElem.innerHTML = "Новый";
                                break;
                            case '1':
                                currentElem.innerHTML = "Обработан";
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

            $("#newOrderBtn").click(function(){
                   $(this).css("display","none");
                   $("#newOrder").css("display","block");
                   editing= true;
                }
            );

            $("#cancelNewOrder").click(function(){
                $("#newOrder").css("display","none");
                $("#newOrderBtn").css("display","block");
                editing= false;
            });

            function refreshPage() {
                if(!editing)
                {
                    location.reload();
                }
            }

            setInterval(refreshPage, 60000);

//            setInterval(getNewOrders, 20000);


        });
    </script>
</head>
<body>


<div class="ordersWrapper">
    <div class="adminLinksPanel">
        <a href="logout.php">Выйти</a>
    </div>
    <div id="orders">

        <div id="newOrderBtn">добавить заказ вручную</div>

        <div id="newOrder">
            <form action="index.php" method="post">
                <div class="block" style="padding-top: 7px">
                    <label for="page" style="padding-left: 6px">Блюдо</label>
                    <select id="page" name="page">
                        <option value="Сытый матафуку">Сытый матафуку</option>
                        <option value="Утренний салат">Утренний салат</option>
                    </select><br>
                    <label for="phone">Телефон</label><input type="text" id="phone" name="phone"><br>
                </div>
                <div class="block">
                    <label for="address">Адрес</label><br>
                    <textarea id="address" name="address"></textarea><br>
                </div class="block">

                <div>
                    <label for="description">Примечания</label><br>
                    <textarea id="description" name="description"></textarea><br>
                </div>

                <div style="padding-left: 200px">
                    <input type="submit" id="saveNewOrder" value="">
                    <input type="button" id="cancelNewOrder">
                </div>


            </form>
        </div>


        <table>
            <?
            foreach ($orders as $order) {
                ?>
                <tr>
                    <td><?= $order->id ?></td>
                    <td class="date"><?= $order->date ?></td>
                    <td class="dishname"><?= $order->dishname ?></td>
                    <td class="phone"><?= $order->phone ?></td>
                    <td class="editable address" id="address_<?= $order->id ?>"
                        orderid="<?= $order->id ?>"><?= $order->address ?></td>

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
                    <td><a href="?action=delete&id=<?= $order->id ?>"
                           onclick="return confirm('Удалить заказ?')">удалить</a>
                    </td>
                </tr>
            <? } ?>

        </table>

    </div>

    <div id="buttons">
        <input type="button" id="savebtn">
        <input type="button" id="cancelbtn">
    </div>

</div>


</body>
</html>








