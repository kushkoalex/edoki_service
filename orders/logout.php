<?php
session_start();
$_SESSION['login_status'] = false;
header("Location:login.html");