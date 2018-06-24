<?php
//开启session会话机制
session_start();
//登录页验证业务逻辑

include '../init.php';

include  DIR_CORE . 'mysqlDB.php';

//接收登录信息
$user_name = $_POST['user_name'];
$user_password = $_POST['user_password'];

//提取数据库用户信息并查找当前提交的用户名
$sql = "select * from user where user_name = '$user_name' ";
$result = $conn->query($sql);

//提取当前用户的密码
$row = $result->fetch_assoc();
$true_password = $row['user_password'];
$conn->close();

//验证规则
if ( empty( $user_name ) || empty($user_password) ) {

	jump('./login.php', '用户名和密码不能为空 请重新登录 ！~~');

}else if ( $result->num_rows == 0 ) {

	jump('./login.php', '用户名不存在 请重新登录 ！~');

}else if ( md5($user_password) === $true_password ) {

	$_SESSION['USER'] = $row;

	setcookie("user","$user_name",time()+3600,"/xiang_mu/bbs/","localhost" );

	jump('../index.php', '欢迎来到BBS论坛 ！~~');

}else{

	jump('./login.php', '您输入的密码不正确 请重新输入！~');

}




