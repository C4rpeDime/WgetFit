var main = true;
$('#submit').click(function () {
		var url=$("input[name='url']").val()
		var email=$("input[name='email']").val()
		if ($(this).attr("data-lock") === "true") return;
		if(url==''){alert('请输入网址信息！');return false;}
	
		$("#running_class").attr("class","alert alert-info alert-dismissable");
		$("#running_class").text("正在爬取站点："+url);
		$("#running_alert").show();
		$("#submit").attr('disabled',true);
		$("#url").attr('disabled',true);
		$.ajax({
			type : "POST",
			url : "api.php",
			data : {url,email},
			dataType : 'json',
			success : function(data) {
				//layer.close(ii);
				$("#submit").attr('disabled',false);
				$("#url").attr('disabled',false);
				if(data.code == 1){
                    console.log(data);
					$("#running_class").attr("class","");
					$("#running_class").html(data.msg+" <a class=\"btn btn-primary btn-lg btn-block\" target=\"_blank\" href=\""+data.down+"\">点我下载</a>"+" <a class=\"btn btn-default btn-lg btn-block\" target=\"_blank\" href=\""+data.yulan+"\">点我预览</a>");
					$("#running_alert").show();
				}else{
					$("#running_class").attr("class","alert alert-info alert-dismissable");
					$("#running_class").text(data.msg);
					$("#running_alert").show();
				}
			}
		});
});