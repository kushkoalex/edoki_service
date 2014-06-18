<?php

require_once "../init.php";
require_once "../model/mailhelper.php";
require_once "../model/order.php";
require_once "../model/orderfactory.php";
require_once "../model/email.php";
require_once "../model/emailfactory.php";

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $orderFactory = new \model\OrderFactory($connection);
    //$order = new \model\Order();
    //$order->dishname = $data['from'];
    //$order->phone = $data['phone'];
    //$orderFactory->save($order);

    $checked_emails = $data['checked_emails'];
    $checked_orders = $data['checked_orders'];

    $body = '<body>';

    foreach ($checked_orders as $order_id) {
        $order = $orderFactory->getOrderById($order_id);
        $body .= '<div>Телефон: ' . $order->phone . '</div><div>Страница: ' . $order->dishname . '<br><br><br></div>';
    }

    $body .= '</body>';

    //    echo(json_encode($checked_emails[0]));


    $mailHelper = new \model\MailHelper();
    $mailSubj = "Edoki - Заказ";
    $message = '<html><head><title>Edoki - Заказ</title></head>';
    $message .= $body;
    $message .= '</html>';

    foreach ($checked_emails as $email) {
        echo($email);
        $mailHelper->sendMail($email, $mailTitleFrom, $mailSubj, $message);
    }

} catch (Exception $e) {
    //throw new Exception('невозможно отправить письмо' . $e->getMessage());
}


