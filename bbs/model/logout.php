<?php
session_start();

include '../init.php';

$dump_path = dirname(_FILE_) . '/' . $_GET['page'] . '.php';


if( isset($_SESSION['USER']) ) {
	//删除指定session
	unset($_SESSION['USER']);
	//销毁session
	//session_destroy();

	//判断客户端cookie信息
	if (isset($_COOKIE['user']) ) {
	   setcookie('user','',time()-3600,'/xiang_mu/bbs/','localhost');
	   	//根据传过来的值判断跳转
	   if( $_GET['page'] == 'publish' || $_GET['page'] == 'index' ) {
	   	  jump('../index.php', '账号已成功退出！~');
	   }else {
	   	 jump( "$dump_path", '账号已成功退出！~ ');

	   }	  
    }else {
       jump('../index.php', '请登录 ！~');
  }
}else {
	jump('../index.php', '请登录 ！~');
}

