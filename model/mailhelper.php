<?php

namespace model;


class MailHelper {
    public function sendMail($to,$from,$subj,$text)
    {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: '.$from. "\r\n";
        return mail($to, $subj, $text, $headers);
    }
} 