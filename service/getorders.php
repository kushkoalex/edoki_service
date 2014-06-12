<?php

require_once "../init.php";
require_once "../model/order.php";
require_once "../model/orderfactory.php";

$orderFactory = new \model\OrderFactory($connection);
$orders = $orderFactory->getAllOrders();


?>

<table>
        <tr class="thead">
            <th>Номер заказа111</th>
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
        <td class="editable address" id="address_<?= $order->id ?>" orderid="<?= $order->id ?>"><?= $order->address ?></td>
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
<? }?>

</table>