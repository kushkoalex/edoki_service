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


    private static function _init($row)
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

    public function getOrderById($id)
    {
        $orders = array();
        $query = "select * from orders where id=$id";
        $result = mysql_query($query, $this->_connection) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order = $this->_init($row);
            $orders[$this->id] = $order;
        }
    }

    public function Save()
    {
        $query = "insert into orders (dishname, phone, address, date) value('$this->dishname','$this->phone','$this->address',now())";
        mysql_query($query, $this->_connection) or die(mysql_error());
    }

    public static function getAllOrders($conn)
    {
        $orders = array();
        $query = "select * from orders";
        //$result = $conn->executeQuery($query);
        echo($conn);
        $result = mysql_query($query, $conn) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order = \model\Order::_init($row);
            $orders[$order->id] = $order;
        }
        return $orders;
    }

    public function getAll()
    {
        $orders = array();
        $query = "select * from orders";
        $result = mysql_query($query, $this->_connection) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order = $this->_init($row);
            $orders[$this->id] = $order;
        }
        return $orders;
    }

    public function getOrdersByDate($date)
    {

    }




}