<?php
namespace PHPMailer;

require_once("PHPMailer/PHPMailer.php");
require_once("PHPMailer/class.smtp.php");

$mail = new PHPMailer();
$mail->SMTPDebug = 1;
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.163.com';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->CharSet = 'UTF-8';

//这里需要自己配置
$mail->Username = 'wgetfit@163.com';  //邮箱账号
$mail->Password = 'GTERAKXOQTTZVDGW';    //邮箱密码，QQ邮箱是授权码
$mail->From = 'wgetfit@163.com';      //邮箱地址

//这里是提交的内容
$content=$_GET['content'];  //邮件内容
$isHTML=$_GET['isHTML'];  //是否为html格式
$mailTitle=$_GET['title'];     //邮件标题
$Adress=$_GET['adress'];   //收件人邮箱地址

$mail->FromName = 'Wget.fit';
// 邮件正文是否为html编码 注意此处是一个方法
$mail->isHTML(false);
// 设置收件人邮箱地址
$mail->addAddress($Adress);
// 添加该邮件的主题
$mail->Subject = $mailTitle;
// 添加邮件正文
$mail->Body = $content;
// 为该邮件添加附件
//$mail->addAttachment('C:\Users\maolimeng\Desktop\PHPMailer-master.zip');
// 发送邮件 返回状态
$status = $mail->send();

?>