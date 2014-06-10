<?php


namespace model;


class Connection
{
    protected  $link;
    private $server, $username, $password, $db;


    public function __construct($server, $username, $password, $db)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        $this->connect();
    }

    private function connect()
    {
        $this->link = mysql_connect($this->server, $this->username, $this->password);
        mysql_select_db($this->db, $this->link);
    }

    public function __sleep()
    {
        return array('server', 'username', 'password', 'db');
    }

    public function __wakeup()
    {
        $this->connect();
    }

    public function executeQuery($query)
    {
        return mysql_query($query,$this->link);
    }

    public function executeNonQuery($query)
    {
        mysql_query($query, $this->link) or die(mysql_error());
    }

    public function __toString()
    {
        return "server: " . $this->server . "<br>" .
        "username: " . $this->username . "<br>" .
        "password: " . $this->password . "<br>" .
        "db: " . $this->db . "<br>" .
        "link: " . (string)$this->link . "<br>";
    }
} 