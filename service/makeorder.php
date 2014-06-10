<?php

require_once "../init.php";
require_once "../model/mailhelper.php";
require_once "../model/order.php";
require_once "../model/orderfactory.php";

try
{
    $data = json_decode(file_get_contents('php://input'), true);
    $orderFactory = new \model\OrderFactory($connection);
    $order = new \model\Order();
    $order->dishname =$data['from'];
    $order->phone = $data['phone'];
    $orderFactory->save($order);

    $mailHelper = new \model\MailHelper();
    $text = "aaaa";
    $mailHelper->sendMail($mailTo, $mailTitleFrom, $mailSubj, $text);
}
catch (Exception $e)
{
    throw new Exception('невозможно отправить письмо'. $e->getMessage());
}

