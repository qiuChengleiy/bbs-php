<?php
session_start();

include '../init.php';

include DIR_CORE . 'mysqlDB.php';



//判断用户是否已经登录了 1.登录了跳转发帖页面 2.否则跳转登录界面后再回复
if ( isset($_SESSION['USER']) ) {
		//回复楼主的ID
	$pub_id = $_GET['pub_id'];

	$sql = "select * from publish where pub_id=$pub_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$conn->close();

	include DIR_VIEW . 'reply.html';
}else {
	jump('./show.php', '暂不支持匿名回复 !~');
}

