<?php

require_once("../init.php");
require_once ("../model/order.php");
require_once ("../model/orderfactory.php");

session_start();
$text = $_POST['text'];
$id = $_POST['id'];

$orderFactory = new \model\OrderFactory($connection);
$order = $orderFactory->getOrderById($id);
$order->address = $text;
$orderFactory->update($order);


