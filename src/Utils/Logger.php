<?php

namespace App\Utils;

class Logger
{
    private static $logDir = __DIR__ . '/../../storage/logs/';

    public static function debug(string $msg, array $context = []): void
    {
        self::log('DEBUG', $msg, $context);
    }

    public static function info(string $msg, array $context = []): void
    {
        self::log('INFO', $msg, $context);
    }

    public static function warning(string $msg, array $context = []): void
    {
        self::log('WARNING', $msg, $context);
    }

    public static function error(string $msg, array $context = []): void
    {
        self::log('ERROR', $msg, $context);
    }

    public static function critical(string $msg, array $context = []): void
    {
        self::log('CRITICAL', $msg, $context);
    }

    public static function alert(string $msg, array $context = []): void
    {
        self::log('ALERT', $msg, $context);
    }

    public static function emergency(string $msg, array $context = []): void
    {
        self::log('EMERGENCY', $msg, $context);
    }

    public static function notice(string $msg, array $context = []): void
    {
        self::log('NOTICE', $msg, $context);
    }
    
    private static function log(string $level, string $msg, array $context): void
    {
        $level = strtoupper($level);
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[2] ?? $backtrace[1] ?? null;
        $sourceFile = $caller['file'] ?? 'unknown file';
        $line = $caller['line'] ?? 'unknown line';
        $time = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? " | Context: " .
            json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $entry_log = "[$time] [$level] $msg $contextStr [In $sourceFile:$line]" . PHP_EOL;

        $fileName = date('Y-m') . '.txt';
        $filePath = self::$logDir . $fileName;

        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        file_put_contents($filePath, $entry_log, FILE_APPEND | LOCK_EX);
    }
}
