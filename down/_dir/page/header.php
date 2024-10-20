<?php
if(!defined('DIR_INIT'))exit();
?><!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php echo $conf['title']?></title>
	<meta name="keywords" content="在线扒站,手机扒站,扒站工具,扒站软件,扒网站工具,扒站,仿站,在线仿站,一键扒站,网站下载器" />
	<meta name="description" content="本工具永久免费使用!只需要一个浏览器,一键将目标网站的前端代码扒下来,自动将指定网页的HTML、CSS、JS、图片等前端资源分类,自动更改资源路径为本地路径,支持一键打包在线下载。" />
	<link rel="stylesheet" href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="//cdn.staticfile.org/twitter-bootstrap/4.6.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="//cdn.staticfile.org/github-markdown-css/5.1.0/github-markdown.min.css">
    <link rel='stylesheet' href='./_dir/static/css/style.css?v=1002'>

</head>
<body>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white border-bottom" id="navbar">
	<div class="container big-nav">
		<a class="navbar-brand" href="/">
		<img src="../logo.png" width="180" height="40" class="d-inline-block align-top mr-2" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
		
<?php
$navs = explode('|', $conf['nav']);
foreach($navs as $nav){
	$nav_arr = explode('*', $nav);
	echo '<li class="nav-item"><a class="nav-link" href="'.$nav_arr[1].'">'.$nav_arr[0].'</a></li>';
}
?>
			</ul>

			<form class="form-inline my-2 my-lg-0" action="./" method="GET">
				<input type="text" name="c" required lay-verify="required" autocomplete="off" value = "search" style = "display: none;">
				<input name="s" class="form-control mr-sm-2" type="search" placeholder="请输入网址关键字" aria-label="Search" value="">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">	<i class="fa fa-search" aria-hidden="true"></i> 搜索</button>
			</form>
												
		</div>
	</div>
</nav>