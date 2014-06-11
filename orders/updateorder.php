<?php

require_once("../init.php");
require_once("../model/order.php");
require_once("../model/orderfactory.php");

session_start();
$text = $_POST['text'];
$field = $_POST['field'];
$id = $_POST['id'];

$orderFactory = new \model\OrderFactory($connection);
$order = $orderFactory->getOrderById($id);
if ($field == 'description') {
    $order->description = $text;
} else if ($field == 'address') {
    $order->address = $text;
} else if($field == 'status') {
    $order->status = $text;
}

$orderFactory->update($order);


