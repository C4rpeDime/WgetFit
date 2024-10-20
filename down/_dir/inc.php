<?php
error_reporting(0);
define("DIR_INIT", true);
define("SYSTEM_ROOT", dirname(__FILE__).'/');
define("ROOT", dirname(SYSTEM_ROOT).'/');
define("PAGE_ROOT", SYSTEM_ROOT.'page/');

date_default_timezone_set("PRC");

require SYSTEM_ROOT.'functions.php';
require SYSTEM_ROOT.'Cache.class.php';
require SYSTEM_ROOT.'DirList.class.php';

$CACHE = new Cache();
$conf = $CACHE->get('config');
if(!$conf){
	if(!$CACHE->set('config', ['admin_username'=>'admin','admin_password'=>md5('123456'),'title'=>'已爬取数据-Wget.Fit', 'keywords'=>'在线扒站,手机扒站,扒站工具,扒站软件,扒网站工具,扒站,仿站,在线仿站,一键扒站,网站下载器','description'=>'本工具永久免费使用！只需要一个浏览器，一键将目标网站的前端代码扒下来支持一键打包在线下载。','announce'=>'','footer'=>'', 'name_encode'=>'utf8', 'file_hash'=>'1', 'cache_indexes'=>'0', 'readme_md'=>'1', 'auth'=>'0', 'nav'=>'首页*/|数据*/down/|测速*/speedtest/|接口*/api/|软件*/soft/|关于*/about/'])){
		sysmsg('配置项初始化失败，可能无文件写入权限');
	}
	$conf = $CACHE->get('config');
}

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = (is_https() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';

if(isset($_COOKIE["admin_session"]))
{
	if($conf['admin_session']===$_COOKIE["admin_session"]) {
		$islogin=1;
	}
}
