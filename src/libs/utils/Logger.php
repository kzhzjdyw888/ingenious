<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
 */
namespace ingenious\libs\utils;

use DateTime;
use ingenious\core\ServiceContext;
use ingenious\ex\LFlowException;
use ingenious\interface\ConfigurationInterface;
use ingenious\interface\IConfiguration;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Logs;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * 日志处理类
 *
 * @author Mr.April
 * @since  1.0
 * @method static debug(string $string)
 */
class Logger
{
    private static ?Logs $logger = null;
    private static ?string $logBasePath = null;

    /**
     * 静态调用不允许外面实例化
     */
    private function __construct()
    {
    }

    /**
     * 初始化
     */
    private static function initLogger(): void
    {

        $list              = ServiceContext::findList(ConfigurationInterface::class);
        $config            = end($list);
        self::$logBasePath = $config->getConfig('log_path', dirname(__DIR__, 2) . '/log/');

        $currentLogPath = self::$logBasePath;

        // Ensure the logs directory exists
        if (!is_dir($currentLogPath)) {
            mkdir($currentLogPath, 0755, true);
        }

        $date         = new DateTime();
        $currentDate  = $date->format('Ymd');
        self::$logger = new Logs('ingenious');
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_debug.log', Logs::DEBUG, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_info.log', Logs::INFO, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_notice.log', Logs::NOTICE, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_warning.log', Logs::WARNING, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_error.log', Logs::ERROR, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_critical.log', Logs::CRITICAL, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_alert.log', Logs::ALERT, false, 0777));
        self::$logger->pushHandler(new StreamHandler($currentLogPath . $currentDate . '_emergency.log', Logs::EMERGENCY, false, 0777));
    }

    /**
     * 静态调用
     *
     * @param $name
     * @param $arguments
     *
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name == 'debug' && !self::isDeBug()) {
            //如果非调试模式拦截
            return;
        }
        if (self::$logBasePath === null) {
            self::initLogger();
        }
        if (in_array($name, ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'])) {
            self::$logger->$name($arguments[0]);
        }
    }

    /**
     * 删除非当天日志
     */
    public static function deleteOldLogs(): void
    {
        if (self::$logBasePath === null) {
            self::initLogger();
        }
        $currentDate = date('Ymd');
        $directory   = new RecursiveDirectoryIterator(self::$logBasePath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator    = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $fileName = $file->getFilename();

            if (!str_contains($fileName, $currentDate)) {
                unlink($filePath); // Delete the log file
            }
        }
    }

    public static function isDeBug(): bool
    {
        $list   = ServiceContext::findList(ConfigurationInterface::class);
        $config = end($list);
        try {
            return $config->getConfig('is_debug', false);
        } catch (LFlowException $e) {
            return false;
        }
    }
}








