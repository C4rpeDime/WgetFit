<?php
// 使用环境变量加载敏感信息，避免将信息硬编码到文件中
$title = "在线仿站工具-Wget.Fit";
$copyright = "Copyright © 2021-2023 WgetFit All Rights Reserved."; // 底部版权

// SSH 连接信息，通过环境变量加载
$host = getenv('SSH_HOST') ?: 'localhost';
$user = getenv('SSH_USER') ?: 'root';
$pass = getenv('SSH_PASS') ?: 'Your_password'; // 仅作默认值，应从环境变量中获取
$port = getenv('SSH_PORT') ?: '22';

// 日志配置，增加日志级别控制
$log = getenv('LOG_LEVEL') ?: 'error'; // 默认记录错误日志

// 邮件 API 地址，从环境变量加载或使用默认值
$smtpapi = getenv('SMTP_API') ?: 'https://Your_domain_name/smtp/api.php';

// 网站 URL
$site_url = "1.1042.net"; // 网站域名与wwwroot内网站目录须一致
?>
