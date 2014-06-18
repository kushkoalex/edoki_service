<?php
session_start();
if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] == false) {
    header("Location:login.html");
}

require_once "../init.php";
require_once "../model/order.php";
require_once "../model/email.php";
require_once "../model/orderfactory.php";
require_once "../model/emailfactory.php";
require_once "../model/mailhelper.php";

$orderFactory = new \model\OrderFactory($connection);
$emailFactory = new \model\EmailFactory($connection);


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
$emails = $emailFactory->getAll();
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


    $("#sendEmail").click(function () {

        var checked_emails = [];
        var checked_orders = [];
        $(".email_checkboxes").each(function(){
            if ($(this).prop("checked")) {
                checked_emails.push($(this).val());
            }
        });

        $(".order_checkboxes").each(function(){
            if ($(this).prop("checked")) {
                checked_orders.push($(this).val());
            }
        });





//        for(var prop in checked_emails)
//        {
//            console.log(checked_emails[prop]);
//        }
//
//        for(var prop in checked_orders)
//        {
//            console.log(checked_orders[prop]);
//        }

        var emaildata = {};
        emaildata.checked_emails = checked_emails;
        emaildata.checked_orders = checked_orders;

        $.ajax({
            url: "/service/sendemail.php",
            contentType: "application/json",
            accepts: "application/json",
            type: "POST",
            data: JSON.stringify(emaildata),
            success: function (message) {
                console.log(message);
            },

            error: function (error) {
                console.log("Ошибка при отправке запроса" +error);
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

    $("#newOrderBtn").click(function () {
            $(this).css("display", "none");
            $("#newOrder").css("display", "block");
            editing = true;
        }
    );

    $("#cancelNewOrder").click(function () {
        $("#newOrder").css("display", "none");
        $("#newOrderBtn").css("display", "block");
        editing = false;
    });

    function refreshPage() {
        if (!editing) {
            location.reload();
        }
    }

    setInterval(refreshPage, 60000);

//            setInterval(getNewOrders, 20000);


    $(".order_checkboxes").click(function () {

        var i = 0;

        $(".order_checkboxes").each(function () {
            if ($(this).prop("checked")) {
                i++;
            }
        });

        if (i > 0) {
            $("#sendEmailPanel").fadeIn();
        } else {
            $("#sendEmailPanel").fadeOut();
        }


    });

    $("#sendEmailCancel").click(function(){
        $(".order_checkboxes").each(function () {
            if ($(this).prop("checked")) {
                $(this).removeAttr('checked');
            }
        });
        $("#sendEmailPanel").fadeOut();
    });



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
                    <input type="submit" id="saveNewOrder" value="" class="btnOk">
                    <input type="button" id="cancelNewOrder" class="btnCancel">
                </div>


            </form>
        </div>


        <table>
            <?
            foreach ($orders as $order) {
                ?>
                <tr>
                    <td>
                        <input type="checkbox" class="order_checkboxes" value="<?=$order->id?>">
                    </td>
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
        <input type="button" id="savebtn" class="btnOk">
        <input type="button" id="cancelbtn" class="btnCancel">
    </div>

    <div id="sendEmailPanel">
        <div class="title">
Отправка заказа по email
        </div>
        <table>
            <?
            foreach ($emails as $mail) {
                ?>
            <tr>
                <td>
                    <input type="checkbox" class="email_checkboxes" <?=$mail->active?"checked":""?> value="<?=$mail->email?>">
                </td>
                <td><?=$mail->email?></td>
            </tr>
            <?
            }

            ?>
        </table>
        <div id="sendEmailButtons">
        <input type="button" id="sendEmail" class="btnOk">
        <input type="button" id="sendEmailCancel" class="btnCancel">
        </div>
    </div>

</div>


</body>
</html>








