## 引言

项目的核心是使用PHP处理用户请求，通过SSH连接服务器执行爬取命令，并将结果发送到用户邮箱。

## 功能概述

该工具具备以下功能：

1. 输入有效的URL和邮箱。
2. 验证URL格式。
3. 通过SSH连接执行Wget命令抓取网页。
4. 生成ZIP文件并通过邮箱通知用户。

## 页面结构

页面使用Bootstrap框架实现响应式设计。以下是页面的基本HTML结构示例：

```html
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>在线仿站工具</title>
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="./">在线仿站工具</a>
  </div>
</nav>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <input type="text" id="url" class="form-control" placeholder="请输入有效的网址" required/>
      <input type="text" id="email" class="form-control mt-2" placeholder="请输入有效的邮箱" required/>
      <input id="submit" type="button" value="提交任务" class="btn btn-primary btn-block mt-3"/>
    </div>
  </div>
</div>

<script src="./assets/js/common.js"></script>
</body>
</html>
```

![微信图片_20241019230833.png][1]

## 后端逻辑

后端使用PHP实现，主要功能集中在`api.php`文件中。以下是该文件的核心代码示例：

### 1. 请求处理

首先，我们检查请求方法是否为POST，并获取URL和邮箱：

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(array('code' => '-1', 'msg' => '仅支持POST请求'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$url = $_POST['url'];
$email = $_POST['email'];
```

### 2. URL验证

使用正则表达式验证输入的URL格式，确保用户输入的是有效的URL：

```php
$preg = "/^http(s)?:\\/\\/.+/";
if (!preg_match($preg, $url)) {
    log_error("Invalid URL format: $url");
    exit(json_encode(array('code' => '-1', 'msg' => '域名请带上协议头！如(http:// 或 https://)'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
```

### 3. 生成文件名

生成唯一的文件名以便于后续的下载：

```php
$timestamp = time();
$file = "website_$timestamp.zip";
```

### 4. 执行SSH命令

通过自定义的SSH类连接到服务器并执行Wget命令：

```php
$ssh = new Components_Ssh($host, $user, $pass, $port, $log);
$command = escapeshellcmd("bash /www/wwwroot/{$site_url}/wget_site.sh {$url} {$file} >/dev/null && echo \"success\"");
$result = $ssh->cmd($command);
```

### 5. 检查文件生成状态

检查文件是否成功生成，若未生成，则记录错误信息：

```php
if (!file_exists('./down/' . $file)) {
    log_error("File generation failed for: $url");
    exit(json_encode(array('code' => '-1', 'msg' => '爬取失败，请稍后再试。'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
```

### 6. 发送邮件通知

发送爬取结果到用户邮箱的代码如下：

```php
$content = '爬取成功，下载链接：' . $site_url . '/down/' . $file;
$wz = $smtpapi . "?adress=" . urlencode($email) . "&isHTML=false&title={$site_url}爬取成功&content=" . urlencode($content);
$response = @file_get_contents($wz);
```

### 7. 错误日志记录

使用自定义的`log_error`函数记录所有错误信息，确保系统的可维护性：

```php
function log_error($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . "\n", 3, '/path/to/error.log');
}
```

### 8. SSH连接类

以下是一个简单的SSH连接类示例，供后续使用：

```php
class Components_Ssh {
    private $connection;

    public function __construct($host, $user, $pass, $port = 22) {
        $this->connection = ssh2_connect($host, $port);
        ssh2_auth_password($this->connection, $user, $pass);
    }

    public function cmd($command) {
        $stream = ssh2_exec($this->connection, $command);
        stream_set_blocking($stream, true);
        return stream_get_contents($stream);
    }
}
```

### 9. Wget脚本示例

`wget_site.sh`脚本负责执行实际的爬取操作，代码示例如下：

```bash
#!/bin/bash
url=$1
file=$2
wget --mirror --convert-links --adjust-extension --page-requisites --no-parent $url -P /www/wwwroot/your_site/down/
zip -r /www/wwwroot/your_site/down/$file /www/wwwroot/your_site/down/*
```

### 10. 处理返回结果

处理执行命令后的返回结果以便后续使用：

```php
if ($result === 'success') {
    exit(json_encode(array('code' => '1', 'msg' => '爬取任务已提交，请查看邮箱。'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
} else {
    log_error("Command execution failed: $command");
    exit(json_encode(array('code' => '-1', 'msg' => '命令执行失败，请重试。'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
```

### 11. 提交任务前的提示

在前端添加任务提交前的提示，增强用户体验：

```javascript
document.getElementById('submit').onclick = function() {
    const url = document.getElementById('url').value;
    const email = document.getElementById('email').value;

    if (url === '' || email === '') {
        alert('请填写所有字段！');
        return;
    }

    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `url=${encodeURIComponent(url)}&email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.code === '-1') {
            alert(data.msg);
        } else {
            alert('任务已提交，请查看您的邮箱！');
        }
    });
};
```

### 12. 邮件发送状态检查

检查邮件发送状态以确保用户能够及时收到通知：

```php
if ($response === false) {
    log_error("Email failed to send to: $email");
    exit(json_encode(array('code' => '-1', 'msg' => '邮件发送失败，请检查邮箱地址。'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
```

## 核心技术

1. **PHP**：后端逻辑处理，包括请求处理和SSH命令执行。
2. **SSH**：使用SSH连接到服务器进行远程操作。
3. **Wget**：爬取网页及其资源的工具。
4. **Bootstrap**：用于简化前端布局和样式。

## 总结

这个在线仿站工具允许用户快速爬取并下载网站资源。关键在于安全地处理用户输入、稳定地执行后端爬取操作，并确保系统的可维护性。

## 最后

服务器需要安装ssh2扩展
同目录下需要创建down和work目录

### bat对接Api进行爬取

```bash
@echo off
chcp 65001
setlocal enabledelayedexpansion

:: 输入要爬取的URL
set /p url=请输入要爬取的URL（http/https）：

:: 输入邮箱
set /p email=请输入你的邮箱：

:: 调用 API，并将结果保存到临时文件
echo 正在发送请求到 https://1.1042.net/api.php ...
curl -X POST -d "url=!url!" -d "email=!email!" "https://1.1042.net/api.php" -H "Content-Type: application/x-www-form-urlencoded" -o response.json

:: 检查响应是否成功
if %errorlevel% neq 0 (
    echo 请求失败，请检查你的网络连接或 API 地址。
    pause
    exit /b
)

:: 解析返回的 JSON
set "found=false"
for /f "delims=" %%i in ('type response.json ^| findstr /R /C:"down" /C:"yulan"') do (
    set "line=%%i"
    set "line=!line:~1,-1!"  :: 去掉引号
    echo !line!
    set "found=true"
)

:: 检查是否找到了链接
if not !found! == true (
    echo 未找到下载链接或预览链接，请检查 API 返回的内容。
    type response.json
) else (
    echo 请求成功，已获取下载链接和预览链接。
)

:: 清理临时文件
del response.json

pause
```

运行结果
![微信图片_20241020010334.png][2]

[1]: https://www.1042.net/usr/uploads/2024/10/3783282244.png

[2]: https://www.1042.net/usr/uploads/2024/10/2768061944.png
