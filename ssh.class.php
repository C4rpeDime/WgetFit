<?php
class Components_Ssh {
    private $connection;
    private $log;

    // 构造函数，初始化SSH连接
    public function __construct($host, $user, $pass, $port = 22, $log = false) {
        $this->log = $log;
        $this->connection = ssh2_connect($host, $port);

        if (!$this->connection) {
            $this->log_error("无法连接到SSH主机 $host:$port");
            throw new Exception("无法连接到SSH主机，请检查网络连接或主机配置。");
        }

        $authSuccess = ssh2_auth_password($this->connection, $user, $pass);
        if (!$authSuccess) {
            $this->log_error("SSH身份验证失败：$user@$host");
            throw new Exception("SSH身份验证失败，请检查用户名或密码。");
        }

        $this->log_info("SSH连接已建立：$user@$host:$port");
    }

    // 执行命令
    public function cmd($command) {
        $this->log_info("SSH命令：$command");

        // 验证输入命令的安全性
        if (!$this->validate_command($command)) {
            $this->log_error("命令验证失败：$command");
            throw new Exception("不安全的命令：$command");
        }

        $stream = ssh2_exec($this->connection, $command);
        if (!$stream) {
            $this->log_error("SSH命令执行失败：$command");
            throw new Exception("SSH命令执行失败，请检查命令格式或权限。");
        }

        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        $this->log_info("命令输出：$output");

        return $output;
    }

    // 关闭SSH连接
    public function close() {
        $this->log_info("关闭SSH连接");
        $this->connection = null; // 修正错误的变量引用
    }

    // 日志记录函数
    private function log_info($message) {
        if ($this->log) {
            error_log("[INFO] $message", 0);
        }
    }

    private function log_error($message) {
        error_log("[ERROR] $message", 0);
    }

    // 验证命令安全性的方法
    private function validate_command($command) {
        // 增加命令白名单或过滤规则，确保安全
        $allowed_commands = ['ls', 'pwd', 'whoami', 'bash', 'wget'];
        foreach ($allowed_commands as $allowed) {
            if (strpos($command, $allowed) !== false) {
                return true;
            }
        }
        return false;
    }
}
