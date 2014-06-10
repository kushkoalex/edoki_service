<?php


namespace model;


class Order
{
    public $id;
    public $dishname;
    public $phone;
    public $address;
    public $status;
    public $date;

    private $_connection;

    function __construct($connection = null)
    {
        $this->_connection = $connection;
    }

    public static function Init($row)
    {
        $order = new Order();
        $order->id = $row->id;
        $order->dishname = $row->dishname;
        $order->phone = $row->phone;
        $order->address = $row->address;
        $order->status = $row->status;
        $order->date = $row->date;
        return $order;
    }




}