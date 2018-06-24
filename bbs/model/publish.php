<?php
//发帖页

session_start();

include '../init.php';

//判断用户是否已经登录了 1.登录了跳转发帖页面 2.否则跳转登录界面后再发帖
if ( isset($_SESSION['USER']) ) {
	include DIR_VIEW . 'publish.html';
}else {
	jump('./login.php', '您还没有登录 !~');
}

