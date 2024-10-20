<?php
include "./common.php";
include "./Config.php"; // 引入配置文件
ignore_user_abort(true);
ini_set('max_execution_time', '0');
date_default_timezone_set('Asia/Shanghai');
@header('Content-Type: application/json; charset=UTF-8');

// 日志文件路径
$log_file = "./error_log.txt";

// 捕获错误并记录详细日志的函数
function log_error($error_message) {
    global $log_file;
    $timestamp = date("Y-m-d H:i:s");
    
    // 检查错误信息是否在已定义的内容中
    $defined_errors = [
        '爬取失败，请检查网址是否正确！',
        '域名请带上协议头！如(http:// 或 https://)',
        '爬取失败！',
        '系统出错，请稍后重试！'
    ];

    // 如果错误信息不在已定义的列表中，则使用统一的提示
    if (!in_array($error_message, $defined_errors)) {
        $error_message = "系统错误，请联系管理员";
    }

    file_put_contents($log_file, "[$timestamp] $error_message\n", FILE_APPEND);
}

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(array('code' => '-1', 'msg' => 'Web front-end crawling API - Author: C4rpeDime, Blog: www.1042.net.'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

if (isset($_POST['url'])) {
    $url = $_POST['url'];
    $email = $_POST['email'];
    
    try {
        // 验证URL返回状态码
        if (get_code($url) != 200) {
            log_error("URL request failed for $url with status code not 200.");
            exit(json_encode(array('code' => '-1', 'msg' => '爬取失败，请检查网址是否正确！'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        // 检查URL格式
        $preg = "/^http(s)?:\\/\\/.+/";
        if (!preg_match($preg, $url)) {
            log_error("Invalid URL format: $url");
            exit(json_encode(array('code' => '-1', 'msg' => '域名请带上协议头！如(http:// 或 https://)'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        // 生成文件名
        $file = parse_url($url)['host'] . '-' . mt_rand(10000, 99999);

        // 执行SSH命令（捕获可能的错误）
        $ssh = new Components_Ssh($host, $user, $pass, $port, $log);
        $command = escapeshellcmd("bash /www/wwwroot/{$site_url}/wget_site.sh {$url} {$file} >/dev/null && echo \"success\"");
        $ssh->cmd($command);

        // 检查文件是否生成成功
        if (file_exists('./down/' . $file . '.zip')) {
            $content = '你在' . $site_url . '提交的前端爬取请求已结束，下载链接：' . $site_url . '/down/' . $file . '.zip';
            $wz = $smtpapi . "?adress=" . urlencode($email) . "&isHTML=false&title={$site_url}爬取成功&content=" . urlencode($content);
            
            // 检查 URL 是否可访问
            $response = @file_get_contents($wz);
            if ($response === false) {
                log_error("Failed to send email notification: " . error_get_last()['message']);
            }

            exit(json_encode(array(
                'code' => '1',
                'msg' => '爬取成功！',
                'down' => $site_url . '/down/' . $file . '.zip',
                'yulan' => $site_url . '/work/' . $file . '/' . parse_url($url)['host']
            ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        } else {
            log_error("File not generated: $file");
            exit(json_encode(array('code' => '-1', 'msg' => '爬取失败！'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

    } catch (Exception $e) {
        // 捕获所有异常并记录详细信息
        log_error("Error processing URL: " . $url . " - " . $e->getMessage());
        exit(json_encode(array('code' => '-1', 'msg' => '系统出错，请稍后重试！'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
} else {
    // 转换字节大小的函数保持不变
    function trans_byte($byte) {
        $KB = 1024;
        $MB = 1024 * $KB;
        $GB = 1024 * $MB;
        $TB = 1024 * $GB;

        if ($byte < $KB) {
            return $byte . "B";
        } elseif ($byte < $MB) {
            return round($byte / $KB, 2) . "KB";
        } elseif ($byte < $GB) {
            return round($byte / $MB, 2) . "MB";
        } elseif ($byte < $TB) {
            return round($byte / $GB, 2) . "GB";
        } else {
            return round($byte / $TB, 2) . "TB";
        }
    }

    // 处理分页请求（无更改）
    $list = glob('./down/*.zip');
    $count = count($list);
    $page_num = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $pages = ceil($count / $page_num);
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startpos = ($page - 1) * $page_num;
    $json['code'] = '0';

    // 返回数据
    $arr = []; // Initialize $arr to avoid undefined variable notice
    for ($i = $startpos; $i < min($startpos + $page_num, $count); $i++) {
        $arr[] = basename($list[$i]); // 仅返回文件名
    }
    $json['data'] = $arr;
    exit(json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// 获取URL状态码的函数保持不变
function get_code($url) {
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpcode;
}
?>
