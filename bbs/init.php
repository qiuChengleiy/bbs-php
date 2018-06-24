<?php 

/**
*项目文件初始化
*/

//相应头设置
header("Content-type:text/html;charset=utf-8");

//定义常量

define("DIR_ROOT", dirname(_FILE_) . '/');

define("DIR_CONFIG", DIR_ROOT . 'config/');

define("DIR_CORE", DIR_ROOT . '/../' . 'core/');

define('DIR_MODEL', DIR_ROOT . '/../' . 'model/');

define("DIR_VIEW", DIR_ROOT . '/../' .'view/');

//引入前端资源时要用相对路径
define("DIR_PUBLIC", DIR_ROOT . 'public');

//登录注册跳转函数封装
/**
 * 封装跳转函数
 * @param string $url 跳转的url
 * @param string $info 跳转时的提示信息
 * @param int $time 跳转的时候等待的时间
 */
function jump($url, $info=NULL, $time=2) {
	if($info == NULL) {
		// 说明是直接跳转
		header("location:$url");
		die("");
	}else {
		// 说明是刷新跳转
		header("refresh:$time;url=$url"); 
		echo "<script>setTimeout( () => alert('<?php $info ?>'),500 )</script>";
		die("");	
	}
}

//用户表单数据过滤
/**
 * 封装跳转函数
 * @param string $str 接收的表单数据
 * @return string 返回数据
 */

function escapeString($str) {
	return addslashes(strip_tags(trim($str)));
}










 ?>