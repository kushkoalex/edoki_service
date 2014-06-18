<?php
session_start();
if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] == false) {
    header("Location:login.html");
}
require_once "../init.php";
require_once "../model/email.php";
require_once "../model/emailfactory.php";
$emailFactory = new \model\EmailFactory($connection);

if (isset($_GET['action']) && $_GET['action'] == 'add') {

    if($_POST['newEmail']!='')
    {
    $email = new \model\Email();
    $email->email = $_POST['newEmail'];
    $emailFactory->save($email);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $email = $emailFactory->getEmailById($_GET['id']);
    if ($email != null) {
        $emailFactory->delete($email);
    }
}
?>



<html>
<head>
    <meta charset="UTF-8">
    <title>Edoki - Администрирование email адресов</title>
    <link rel="stylesheet" href="css/main.css?v=0.0.14">
    <script type="text/javascript" src="../vendor/js/jquery-1.11.1.min.js"></script>
    <script></script>
</head>
<body>
<div class="ordersWrapper">
    <div class="adminLinksPanel">
        <a href="logout.php">Выйти</a>
        &nbsp;
        <a href="index.php">Заказы</a>
    </div>
    <div id="orders">
        <table>
            <?
            $emails = $emailFactory->getAll();

            foreach ($emails as $email) {
                ?>
                <tr>
                    <td><?= $email->email ?></td>
                    <td><a href="?action=delete&id=<?= $email->email ?>"
                           onclick="return confirm('Удалить email?')">удалить</a></td>
                </tr>
            <?
            }
            ?>
        </table>
    </div>
    <div style="text-align: center; padding-top: 20px;">
        <form action="emails.php?action=add" method="post">
            <input type="text" value="" name="newEmail">
            <input type="submit" value="Сохранить">
        </form>
    </div>
</div>
</body>
</html>
