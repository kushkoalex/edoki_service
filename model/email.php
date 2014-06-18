<?php
/**
 * Created by PhpStorm.
 * User: o.kushko
 * Date: 18.06.14
 * Time: 12:35
 */

namespace model;


class Email
{
    public $email;
    public $active;

    private $_connection;

    function __construct($connection = null)
    {
        $this->_connection = $connection;
    }

    public static function Init($row)
    {
        $mail = new Email();
        $mail->email = $row->email;
        $mail->active = $row->active;
        return $mail;
    }
} 