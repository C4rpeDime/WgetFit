<?php
// 检查并包含 Config.php 文件
if (file_exists('Config.php')) {
    include 'Config.php';
} else {
    error_log("Config.php 文件未找到。", 0);
    exit("服务器配置错误，请联系管理员。");
}

// 检查并包含 ssh.class.php 文件
if (file_exists('./ssh.class.php')) {
    include './ssh.class.php';
} else {
    error_log("ssh.class.php 文件未找到。", 0);
    exit("服务器配置错误，请联系管理员。");
}
?>
