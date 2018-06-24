# bbs论坛项目
因为重点放在了PHP编写上，所以前端部分没有特别写~~ 数据提交都是采用FORM表单的形式

#### 项目结构 /bbs/

``` sh
├── config/ # 项目配置文件
	├── config.php # 配置文件
├── core/ # 核心文件
	├── mysqlDB.php # 数据库连接文件
	├── upload.php # 上传文件操作
├── model/ # 业务模块
	├── list_father.php
	├── login.php
	├── publish.php
	├── publish_deal.php
	├── register.php
	├── reply.php
	├── reply_deal.php
	├── show.php
	├── userInfo.php
	├── validate.php
├── public/ # 静态资源存放目录
├── uploads/  # 文件上传目录
├── views/ # 视图目录
    ├── index.html   
    ├── list_father.html
    ├── list_son.html
    ├── login.html
    ├── publish.html
    ├── quote.html
    ├── register.html
    ├── reply.html
    ├── show.html
├── index.php # 项目入口文件
├── init.php # 项目初始化文件

```

### 项目介绍

* 项目进行了简单的前后端分离

* 功能实现：登录(结合session和cookie)、注册、注销、查看帖子、发表帖子、帖子分页、点赞、帖子回复、头像、删除帖子

```php

// index.php
<?php

// 1  加载项目初始化文件
include './init.php';

// 2  加载视图文件
include './view/index.html';


//init.php

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

```

```php

<?php

//分页功能


include '../init.php';

include DIR_CORE . 'mysqlDB.php';

// 以下的代码跟分页有关
// 接收当前页码数
$pageNum = isset($_GET['num']) ? $_GET['num'] : 1;
// 定义每一页显示的记录数
$rowsPerPage = 5;
// 查询总记录数
$sql = "select count(*) as sum from publish";
$result = $conn->query($sql);
$row = $result->fetch_assoc(); // 还是一个数组
$rowCount = $row['sum']; // 得到总记录数
// 计算总页数
$pages = ceil($rowCount / $rowsPerPage);

// 拼凑出页码字符串
$strPage = '';
// 拼凑出“首页”
$strPage .= "<a href='./list_father.php?num=1'>首页</a>";
// 拼凑出“上一页”
$preNum = $pageNum == 1 ? 1 : $pageNum - 1;
if($pageNum != 1) {
	$strPage .= "<a href='./list_father.php?num=$preNum'><<上一页</a>";
}

// 确定显示的第1个页码$startNum的值
if($pageNum <= 3) {
	$startNum = 1;
}else {
	$startNum = $pageNum - 2;
}
// 确定$startNum的最大值
if($startNum > $pages - 4) {
	$startNum = $pages - 4;
}
// 防止$startNum出现负值
if($startNum <= 1) {
	$startNum = 1;
}
// 确定显示的最后1个页码$endNum的值
$endNum = $startNum + 4;
// 防止$endNum越界
if($endNum > $pages) {
	$endNum = $pages;
}

// 拼凑出中间的页码
for($i=$startNum;$i<=$endNum;$i++) {
	if($i == $pageNum) {
		$strPage .= "<a href='./list_father.php?num=$i' style='background:#105cb6;color:white;'>$i</a>";
	}else {
		$strPage .= "<a href='./list_father.php?num=$i'>$i</a>";
	}	
}
// 拼凑出“下一页”
$nextNum = $pageNum == $pages ? $pages : $pageNum + 1;
if($pageNum != $pages) {
	$strPage .= "<a href='./list_father.php?num=$nextNum'>下一页>></a>";
}
// 拼凑出“尾页”
$strPage .= "<a href='./list_father.php?num=$pages'>尾页</a>";
// 拼凑出“总页数”
$strPage .= "总页数:$pages";

// 分页到此结束

// 3, 提取publish表的数据
$offset = ($pageNum - 1) * $rowsPerPage;
$sql = "select * from publish order by pub_time desc limit $offset, $rowsPerPage";
$result = $conn->query($sql);

if ( $result->num_rows == 0 ) {
	$row  = array('pub_content' => '信息消失了~', 'pub_owner' => '');
}


include DIR_VIEW . 'list_father.html';



//logout.php 账号注销功能

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


```

#### 数据库方面

* create database bbs;  创建数据库

* use bbs; 使用它

* 创建用户表
```sql
create table user(
	user_id int unsigned primary key auto_increment comment '主键ID',
	user_name varchar(20) not null unique key comment '用户名',
	user_password char(32) not null comment '用户密码'
);
```

* 创建发帖表
```sql
create table publish(
	pub_id int unsigned primary key auto_increment comment '主键ID',
	pub_title varchar(50) not null comment '发帖标题',
	pub_content text not null comment '发帖内容',
	pub_owner varchar(20) not null comment '发帖者',
	pub_time int unsigned not null comment '发帖时间',
	pub_hits int unsigned not null comment '浏览次数'
);
```

* 创建回帖表
```sql
create table reply(
	rep_id int unsigned primary key auto_increment comment '主键ID',
	rep_pub_id int unsigned not null comment '外键,指向回帖人的ID',
	rep_user varchar(20) not null comment '回复者',
	rep_content text not null comment '回复内容',
	rep_time int unsigned not null comment '回复的时间戳'

);
```

* 连接数据库

```php

<?php

$config = include '../config/config.php';

//数据库连接
$conn = new mysqli($config['servername'],$config['username'],$config['password'],$config['db']);

if ( $conn->connect_error ) {
	die('数据库连接失败');
}

```

#### 项目总结

* 本项目是初学PHP写的第一个综合性项目，写的过程中遇到很多问题，发现PHP基础这一块很重要，其次就是后端方面要多去了解
* 写的大多都是纯业务的代码，代码质量有待提高
* 总结： 继续采坑(＾－＾)V

* 项目适合入门的小伙伴哦~

##### 始终坚信

敢于尝试的你 其实已经进步了 ^_^







