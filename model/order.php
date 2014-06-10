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

    function __construct($connection)
    {
        $this->_connection = $connection;
    }

    private function _init($row)
    {
        $this->id = $row->id;
        $this->dishname = $row->dishname;
        $this->phone = $row->phone;
        $this->address = $row->address;
        $this->status = $row->status;
        $this->date = $row->date;
        return $this;
    }

    public function getOrderById($id)
    {
        $orders = array();
        $query = "select * from orders where id=$id";
        $result = mysql_query($query, $this->_connection) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order =  $this->_init($row);
            $orders[$this->id] = $order;
        }
    }

    public function getAll()
    {

    }

    public function getOrdersByDate($date)
    {

    }


}