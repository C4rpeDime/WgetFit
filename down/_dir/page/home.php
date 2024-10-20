<?php
if(!defined('DIR_INIT'))exit();
header('Content-Type: text/html; charset=UTF-8');

include PAGE_ROOT.'header.php';
?>
	<div class="container" id="main">
		<div class="row mt-3">
			<div class="col-12">
<?php if($errmsg){?>
<div class="card border-warning mb-3">
  <div class="card-header bg-warning">提示信息</div>
  <div class="card-body bg-light">
    <h5 class="card-title"><?php echo $errmsg?></h5>
	<a href="./" class="btn btn-primary">返回首页</a>
  </div>
</div>
</div></div></div>
<?php exit;}

if($conf['announce']){?>
	<div class="card-body" id="msg">
		<i class="fa fa-volume-up mr-2"></i><?php echo $conf['announce']?>
	</div>
<?php
}

if($c=='search'){?>
				<p>
					<b><?php echo $s?></b> 的搜索结果 (<?php echo count($r['list']);?>条)
				</p>
<?php } else { ?>
			
<?php } ?>
			</div>
		</div>
<?php



$total = disk_total_space('.');
$free = disk_free_space('.');
 
echo '内存总量：'.readable_size($total)."\n";
echo "剩余可用内存：".readable_size($free);
 
function readable_size($length){
$units = array('B', 'kB', 'MB', 'GB', 'TB');
foreach ($units as $unit) {
if($length>1024)
$length = round($length/1024, 1);
else
break;
}
return $length.' '.$unit;
}



?>

		<div class="row mt-2"><div class="col-12">
			<table class="table table-hover dirlist" id="list">
				<thead>
					<tr>
					<th>文件名</th>
					<th class="d-none d-lg-table-cell"></th>
					<th class="d-none d-md-table-cell">爬取时间</th>
					<th>大小</th>
					<th class="d-none d-md-table-cell">操作</th>
					</tr>
				</thead>
				<tbody>
<?php if($r['parent']){?>
					<tr>
						<td>
							<a class="fname" href="<?php echo $r['parent']?>"><i class="fa fa-level-up fa-fw"></i> ..</a>
						</td>
						<td class="d-none d-lg-table-cell">
						</td>
						<td class="d-none d-md-table-cell">-</td>
						<td>-</td>
						<td class="d-none d-md-table-cell">
						</td>
					</tr>
<?php }
function bdir($dir,&$mu){
	$ty = scandir($dir);
	foreach ($ty as $k => $v){

		if ($v == '.' || $v == '..'){
			continue;
		}
		if(in_array('index.html',$ty)){
			continue;
		}
		if (filetype($dir.$v) == 'dir'){
			$mu .= $v.'/';
			bdir($dir.$v.'/',$mu);

		}

	}
	return $mu;
}
foreach($r['list'] as $item) {
?>
	<?php 	$t = str_replace('.zip','',$item['name']);
			$ts = dirname(ROOT).'/work/'.$t.'/';
			$mu = '/work/'.$t.'/';
			$y = bdir($ts,$mu);
			if(!strpos(file_get_contents(dirname(ROOT).$y.'index.html'),'https://zz.sangyun.net/pixel/ZfvYr1njxm5mQ2lv')){
				$fp = fopen(dirname(ROOT).$y.'index.html','a');//
				fwrite($fp, PHP_EOL.'<!-- Pixel Code for https://zz.sangyun.net/ -->
					<script defer src="https://zz.sangyun.net/pixel/ZfvYr1njxm5mQ2lv"></script>
					<!-- END Pixel Code -->');//PHP_EOL 换行符
				fclose($fp);
			}			
			$url =$_SERVER['HTTP_HOST'].$y.'index.html';
	?>
					<tr>
						<td>
							<a class="fname" href="http://<?php echo $url;?>" target="_blank" title="在线预览"><i class="fa <?php echo $item['icon']?> fa-fw"></i> <?php echo $c=='search'?'/'.$item['path']:$item['name']?></a>
						</td>
						<td class="d-none d-lg-table-cell fileinfo">
						<?php if($item['type'] == 'file'){ ?>
							<?php if($conf['file_hash'] == '1'){ ?><a href="javascript:;" title="查看文件hash" onclick="filehash('<?php echo $item['path']; ?>')"><i class="fa fa-info-circle" aria-hidden="true"></i></a><?php } ?>
							<a href="javascript:;" onclick="qrcode('<?php echo $item['src']; ?>')" title="显示二维码"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
						<?php } ?>
						</td>
						<td class="d-none d-md-table-cell"><?php echo $item['mtime']; ?></td>
						<td><?php echo $item['size_format']; ?></td>
						<td class="d-none d-md-table-cell">
							<?php if($item['type'] == 'file'){ ?>
								<a href="javascript:;" class="btn btn-sm btn-outline-secondary" title="复制链接" onclick="copy('<?php echo $item['src']; ?>')"><i class="fa fa-copy fa-fw"></i></a>
								<a href="<?php echo $item['src']; ?>" class="btn btn-sm btn-outline-primary" title="点击下载"><i class="fa fa-download fa-fw"></i></a>
                                <a href="http://<?php echo $url;?>" target="_blank" class="btn btn-sm btn-outline-info" title="在线预览"><i class="fa fa-eye fa-fw"></i></a>
								<?php if($item['view_type'] == 'image'){ ?><a class="btn btn-sm btn-outline-info" title="在线预览" href="javascript:;" onclick="view_image('<?php echo $item['src']; ?>')"><i class="fa fa-eye fa-fw"></i></a>
								<?php }elseif($item['view_type'] == 'audio'){ ?><a class="btn btn-sm btn-outline-info" title="点此播放" href="javascript:;" onclick="view_audio('<?php echo $item['path']; ?>')"><i class="fa fa-play-circle fa-fw"></i></a>
								<?php }elseif($item['view_type'] == 'video'){ ?><a class="btn btn-sm btn-outline-info" title="点此播放" href="javascript:;" onclick="view_video('<?php echo $item['name']; ?>','<?php echo $item['path']; ?>')"><i class="fa fa-play-circle fa-fw"></i></a>
								<?php }elseif($item['view_type'] == 'office'){ ?><a class="btn btn-sm btn-outline-info" title="点此查看" href="javascript:;" onclick="view_office('<?php echo $item['name']; ?>','<?php echo $item['src']; ?>')"><i class="fa fa-eye fa-fw"></i></a>
								<?php }elseif($item['view_type'] == 'markdown'){ ?><a class="btn btn-sm btn-outline-info" title="点此查看" href="javascript:;" onclick="view_markdown('<?php echo $item['name']; ?>','<?php echo $item['path']; ?>')"><i class="fa fa-eye fa-fw"></i></a>
								<?php }elseif($item['view_type'] == 'text'){ ?><a class="btn btn-sm btn-outline-info" title="点此查看" href="javascript:;" onclick="view_text('<?php echo $item['name']; ?>','<?php echo $item['path']; ?>')"><i class="fa fa-eye fa-fw"></i></a>
								<?php } ?>
							<?php } ?>
						</td>
					</tr>
<?php
}
?>
				</tbody>
			</table>
		</div></div>
<?php
if($conf['readme_md'] == 1 && $r['readme_md']){
	$content = file_get_contents($r['readme_md']);
	if($content){
		require SYSTEM_ROOT.'Parsedown.class.php';
		$Parsedown = new Parsedown();
		$content = $Parsedown->text($content);
		$content = str_replace('[x]','<input type="checkbox" checked>',$content);
		$content = str_replace('[ ]','<input type="checkbox">',$content);
?>
		<div class="card mt-1">
			<div class="card-header">
			README.md
			</div>
			<div class="card-body">
				<div class="markdown-body">
                    <?php echo $content; ?>
                </div>
			</div>
		</div>
<?php	}
}
?>
	</div>
	
<?php include PAGE_ROOT.'footer.php';?>
    <style>/*css loding*/
#loading {
    position: fixed !important;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 999;
    background: #000;
    opacity: 0.7;
    filter: alpha(opacity=70);
    font-size: 14px;
    line-height: 20px
}

#loading-one {
    color: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: 50px 0 0 -80px;
    padding: 3px 10px
}

.circle {
    background-color: rgba(0,0,0,0);
    border: 5px solid rgba(0,183,229,0.9);
    opacity: .9;
    border-right: 5px solid rgba(0,0,0,0);
    border-left: 5px solid rgba(0,0,0,0);
    border-radius: 50px;
    box-shadow: 0 0 35px #2187e7;
    width: 50px;
    height: 50px;
    margin: -25px;
    -moz-animation: spinPulse 1s infinite ease-in-out;
    -webkit-animation: spinPulse 1s infinite linear;
    position: absolute;
    top: 50%;
    left: 50%
}

.circle1 {
    background-color: rgba(0,0,0,0);
    border: 5px solid rgba(0,183,229,0.9);
    opacity: .9;
    border-left: 5px solid rgba(0,0,0,0);
    border-right: 5px solid rgba(0,0,0,0);
    border-radius: 50px;
    box-shadow: 0 0 15px #2187e7;
    width: 30px;
    height: 30px;
    margin: -15px;
    position: relative;
    top: -50px;
    -moz-animation: spinoffPulse 1s infinite linear;
    -webkit-animation: spinoffPulse 1s infinite linear;
    position: absolute;
    top: 50%;
    left: 50%
}

@-moz-keyframes spinPulse {
    0% {
        -moz-transform: rotate(160deg);
        opacity: 0;
        box-shadow: 0 0 1px #2187e7
    }

    50% {
        -moz-transform: rotate(145deg);
        opacity: 1
    }

    100% {
        -moz-transform: rotate(-320deg);
        opacity: 0
    }
}

@-moz-keyframes spinoffPulse {
    0% {
        -moz-transform: rotate(0deg)
    }

    100% {
        -moz-transform: rotate(360deg)
    }
}

@-webkit-keyframes spinPulse {
    0% {
        -webkit-transform: rotate(160deg);
        opacity: 0;
        box-shadow: 0 0 1px #2187e7
    }

    50% {
        -webkit-transform: rotate(145deg);
        opacity: 1
    }

    100% {
        -webkit-transform: rotate(-320deg);
        opacity: 0
    }
}

@-webkit-keyframes spinoffPulse {
    0% {
        -webkit-transform: rotate(0deg)
    }

    100% {
        -webkit-transform: rotate(360deg)
    }
}
/*css loding*/</style>
<div id="loading"> <p id="loading-one">页 面 载  入 中 . . .</p > <div class="circle"></div> <div class="circle1"></div> </div>
  <script>
jQuery(function() {
    jQuery('#loading-one').empty().append('页 面 载 入 中 . . .').parent().fadeOut('slow');
    jQuery('#loading').click(function() {
        jQuery('#loading').fadeOut('slow');
    });
});
</script>
<script src="./_dir/static/js/main.js"></script>
</body>
</html>