<?php

require_once "../init.php";
require_once "../model/mailhelper.php";
require_once "../model/order.php";
require_once "../model/orderfactory.php";

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $orderFactory = new \model\OrderFactory($connection);
    $order = new \model\Order();
    $order->dishname = $data['from'];
    $order->phone = $data['phone'];
    $orderFactory->save($order);

//    $mailHelper = new \model\MailHelper();
//    $mailSubj = "Edoki - Заказ - " . $data['from'];
//    $message = '<html><head><title>Edoki - Заказ - ' . $data['from'] . '</title></head><body>
//    <div>Телефон: ' . $data['phone'] . '</div><div>Страница: ' . $data['from'] . '</div>
//    </body></html>';
//    $mailHelper->sendMail($mailTo, $mailTitleFrom, $mailSubj, $message);
} catch (Exception $e) {
    //throw new Exception('невозможно отправить письмо' . $e->getMessage());
}

