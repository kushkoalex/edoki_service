<?php

require_once "../orders/init.php";

try
{
    $data = json_decode(file_get_contents('php://input'), true);
    $phone = $data['phone'];
    $from = $data['from'];
    $sendmail = mail("kushko.alex@gmail.com", "order", "phone=".$phone." from=".$from, "From: edoki\n");



    $query = "insert into orders (dishname, phone, date) value('$from','$phone',now())";
    mysql_query($query, $connection) or die(mysql_error());


}
catch (Exception $e)
{
    throw new Exception('невозможно отправить письмо'. $e->getMessage());
}

