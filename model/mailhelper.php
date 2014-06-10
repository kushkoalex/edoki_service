<?php

namespace model;


class MailHelper {
    public function sendMail($to,$from,$subj,$text)
    {
        return mail($to, $subj, $text, "From: ".$from."\n");
    }
} 