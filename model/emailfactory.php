<?php
/**
 * Created by PhpStorm.
 * User: o.kushko
 * Date: 18.06.14
 * Time: 12:34
 */

namespace model;


class EmailFactory {
    private $_connection;

    function __construct($connection)
    {
        $this->_connection = $connection;
    }

    public function getEmailById($id)
    {
        $query = "select * from emails where email='$id'";
        $result = $this->_connection->executeQuery($query) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            return $order = Email::Init($row);
        }
    }
    public function getAll()
    {
        $emails = array();
        $query = "select * from emails";
        $result = $this->_connection->executeQuery($query) or die(mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $email = Email::Init($row);
            $emails[$email->email] = $email;
        }
        return $emails;
    }

    public function save($mail)
    {
        $query = "insert into emails (email, active) value('$mail->email',1)";
        $this->_connection->executeNonQuery($query); // mysql_query($query, $this->_connection) or die(mysql_error());
    }

    public function delete($mail)
    {
        $query = "delete from emails where email='$mail->email'";
        $this->_connection->executeNonQuery($query);
    }
}