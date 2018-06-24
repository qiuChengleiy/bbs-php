<?php 

session_start();

$arr = $_SESSION['USER'];

echo '欢迎会员'  . $arr["user_name"] . ' 以下是您的个人信息~';
