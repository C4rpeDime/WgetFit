<?php
include "./common.php";
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?php echo $title?> </title>
  <meta name="keywords" content="在线扒站,手机扒站,扒站工具,扒站软件,扒网站工具,扒站,仿站,在线仿站,一键扒站,网站下载器">
<meta name="description" content="本工具永久免费使用!只需要一个浏览器,一键将目标网站的前端代码扒下来,自动将指定网页的HTML、CSS、JS、图片等前端资源分类,自动更改资源路径为本地路径,支持一键打包在线下载。">
<meta name="author" content="Wget.Fit">

 <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>

	<link rel="stylesheet" href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="//cdn.staticfile.org/twitter-bootstrap/4.6.1/css/bootstrap.min.css">

    <link rel='stylesheet' href='./static/css/style.css?v=1002'>
<!-- Pixel Code for https://zz.sangyun.net/ -->
<script defer src="https://zz.sangyun.net/pixel/ZfvYr1njxm5mQ2lv"></script>
<!-- END Pixel Code --></head>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white border-bottom" id="navbar">
	<div class="container big-nav">
		<a class="navbar-brand" href="./">
		<img src="/logo.png" width="180" height="40" class="d-inline-block align-top mr-2" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
				<a class="nav-link" href="/">首页</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="/down">数据</a>
				</li>
		
			</ul>

			<form class="form-inline my-2 my-lg-0" action="./down/" method="GET">
				<input type="text" name="c" required lay-verify="required" autocomplete="off" value = "search" style = "display: none;">
				<input name="s" class="form-control mr-sm-2" type="search" placeholder="请输入网址关键字" aria-label="Search" value="">
				<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">	<i class="fa fa-search" aria-hidden="true"></i> 搜索</button>
			</form>
												
		</div>
	</div>
</nav>
    
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block " style="float: none;">
<img src="./assets/simple/img/userbg.jpg" width="100%" >

        <div id="main">
            <br/>
              <input type="text" id="url" name="url" value="" class="form-control" required="required" placeholder="请输入一个有效的网址"/><br/>
              <input type="text" id="email" name="email" value="" class="form-control" required="required" placeholder="请输入一个有效的邮箱"/>
              </div><br/>
            <div class="form-group">
              <input id="submit" type="button" value="提交任务" class="btn btn-primary btn-block"/>
            </div>
            
            
            <div id="running_alert">
            	<div id="running_class" class="alert alert-info alert-dismissable">
				
					当前暂未提交爬取任务！
					
			</div>
            </div>
  
    </div>
  
  </div>
<script src="./assets/js/common.js"></script>
<footer class="footer card-footer mt-3" id="footer">
<span> <?php echo $copyright?>丨<a href="https://wget.fit/WgetFitCode.zip">Code download</a></span>
</footer>

<script src="//cdn.staticfile.org/twitter-bootstrap/4.6.1/js/bootstrap.min.js"></script>
<script src="//cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="//cdn.staticfile.org/layer/3.1.1/layer.js"></script>
<script src="./static/js/clipBoard.min.js"></script>

</body>
</html>