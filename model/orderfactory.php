<?php


namespace model;


class OrderFactory
{
    private $_connection;

    function __construct($connection)
    {
        $this->_connection = $connection;
    }

    public function getOrderById($id)
    {
        $query = "select * from orders where id=$id";
        $result = $this->_connection->executeQuery($query) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            return $order = Order::Init($row);
        }
    }

    public function getAllOrders()
    {
        $orders = array();
        $query = "select * from orders";
        $result = $this->_connection->executeQuery($query) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order = Order::Init($row);
            $orders[$order->id] = $order;
        }
        return $orders;
    }

    public function getAll()
    {
        $orders = array();
        $query = "select * from orders";
        $result = $this->_connection->executeQuery($query) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $order = Order::Init($row);
            $orders[$order->id] = $order;
        }
        return $orders;
    }

    public function getOrdersByDate($date)
    {

    }

    public function save($order)
    {
        $query = "insert into orders (dishname, phone, address, date) value('$order->dishname','$order->phone','$order->address',now())";
        $this->_connection->executeNonQuery($query); // mysql_query($query, $this->_connection) or die(mysql_error());
    }

    public function delete($order)
    {
        $query = "delete from orders where id=$order->id";
        $this->_connection->executeNonQuery($query);
    }

    public function update($order)
    {
        $query = "update orders set address='" . $order->address . "', status=" . $order->status . " where id=" . $order->id;
        $this->_connection->executeNonQuery($query);
    }
} 