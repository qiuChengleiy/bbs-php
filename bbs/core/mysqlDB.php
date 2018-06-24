<?php

$config = include '../config/config.php';

//数据库连接
$conn = new mysqli($config['servername'],$config['username'],$config['password'],$config['db']);

if ( $conn->connect_error ) {
	die('数据库连接失败');
}