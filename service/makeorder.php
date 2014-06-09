<?php


try
{
    $data = json_decode(file_get_contents('php://input'), true);
    $phone = $data['phone'];
    $from = $data['from'];
    $sendmail = mail("kushko.alex@gmail.com", "order", "phone=".$phone." from=".$from, "From: edoki\n");
}
catch (Exception $e)
{
    throw new Exception('невозможно отправить письмо'. $e->getMessage());
}

